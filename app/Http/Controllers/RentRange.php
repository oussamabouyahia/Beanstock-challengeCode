<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LogementEncadrement;
use App\Models\QuartiersParis;
use App\Rules\ValidateCoordinates;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RentRange extends Controller
{
    private function validationRequest(Request $request)
    {
        //the coordinates need custom validation see ValidateCoordinates rule
        return $request->validate([
            "zip_code" => "nullable|required_without:coordinates|regex:/^\d{5}$/",
            "coordinates" => ["nullable", "required_without:zip_code", new ValidateCoordinates()],
            "room_number" => "required|integer|min:1",
            "construction_period" => "required|in:Avant 1946,1946-1970,1971-1990,Apres 1990",
            "furnished" => "required|boolean"
        ]);
    }
    //function to fetch houses if coordinates are provided , if coordinates in quartiers table , eager load the corresponding houses
    //second check , if coordinates not in quartiers table I will check the logementEncadrement table
    private function getHousesByCoordinate(Request $request)
    {

        $validated = $this->validationRequest($request);
        $quartierByCoordinates = QuartiersParis::with(['logementEncadrements' => function ($query) use ($validated) {
            $query->where('room_number', '=', $validated["room_number"])
                  ->where('construction_period', '=', $validated["construction_period"])
                  ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished');
        }])->where('geometry_X_Y', '=', $validated["coordinates"])->cursor()->collect() ;

        if ($quartierByCoordinates->isNotEmpty()) {
            $houses = $quartierByCoordinates->pluck('logementEncadrements')->flatten();
        }
        //if coordinates not in quartiers table
        else {
            $houses = LogementEncadrement::where('geographic_point_2d', '=', $validated["coordinates"])
            ->where('room_number', '=', $validated["room_number"])
            ->where('construction_period', '=', $validated["construction_period"])
            ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished')->cursor()->collect();
        }
        if ($houses->isEmpty()) {
            abort(404, 'No logements found for the provided coordinates.');
        }

        return [
            "maxRent" => $houses->max('major_reference'),
            "minRent" => $houses->min('minor_reference'),
            "averageRent" => $houses->avg('reference')
        ];
    }
    //if coordinates not provided, fetch houses by zip code
    private function getHousesByZipCode(Request $request)
    {
        $validated = $this->validationRequest($request);
        // load the quartiers with the logementEncadrements (corresponding houses) based on the zip code

        $quartiersQuery = QuartiersParis::where('zip_code', $validated["zip_code"]);
        if (!$quartiersQuery->exists()) {
            abort(404, 'No matching quartier found.');
        }

        $quartiers = QuartiersParis::with(['logementEncadrements' => function ($query) use ($validated) {
            $query->where('room_number', '=', $validated["room_number"])
                  ->where('construction_period', '=', $validated["construction_period"])
                  ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished');

        }])->where('zip_code', '=', $validated["zip_code"])->cursor()->collect();
        $houses = $quartiers->pluck('logementEncadrements')->flatten();
        if ($houses->isEmpty()) {
            abort(404, 'No logements found for the provided zip code.');
        }

        return [
            "maxRent" => $houses->max('major_reference'),
            "minRent" => $houses->min('minor_reference'),
            "averageRent" => $houses->avg('reference')
        ];
    }
    //reusable response function
    private function rentResponse($houses)
    {
        return response()->json([
            "status" => "success",
            "minimumRent" => $houses['minRent'],
            "maximumRent" => $houses['maxRent'],
            "averageRent" => $houses['averageRent'],
        ], 200);
    }

    public function getRentRange(Request $request)
    {
        try {
            $validated = $this->validationRequest($request);
            //if zip_code provided
            if (!empty($validated["zip_code"])) {
                $houses = $this->getHousesByZipCode($request);

            } else {

                $houses = $this->getHousesByCoordinate($request);

            }
            return $this->rentResponse($houses);

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed' || 'internal server issue',
                'errors' => $e->getMessage(),
            ], 422);
        }
    }
}
