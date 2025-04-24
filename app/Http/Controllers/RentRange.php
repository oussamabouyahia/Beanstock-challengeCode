<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LogementEncadrement;
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

    //function to fetch houses if coordinates are provided , query from logment_encadrement table and no need to join with quartiers_paris
    private function getHousesByCoordinate(Request $request)
    {

        $validated = $this->validationRequest($request);
        $houses = LogementEncadrement::where('geographic_point_2d', '=', $validated["coordinates"])
            ->where('room_number', '=', $validated["room_number"])
            ->where('construction_period', '=', $validated["construction_period"])
            ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished')
            ->get();

        if ($houses->isEmpty()) {
            abort(404, 'No logements found for the provided coordinates.');
        }

        return [
            "maxRent" => $houses->max('major_reference'),
            "minRent" => $houses->min('minor_reference'),
            "averageRent" => $houses->avg('reference')
        ];
    }
    //if coordinates not provided, fetch houses by join two tables
    private function getHousesByZipCode(Request $request)
    {
        $validated = $this->validationRequest($request);

        $houses = DB::table('quartiers_paris')
            ->where('zip_code', '=', $validated["zip_code"])
            ->join('logement_encadrements', 'quartiers_paris.geometry_X_Y', '=', 'logement_encadrements.geographic_point_2d')
            ->where('room_number', '=', $validated["room_number"])
            ->where('construction_period', '=', $validated["construction_period"])
            ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished')
            ->get();

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
            // if coordinates provided
            if (!empty($validated["coordinates"])) {
                $houses = $this->getHousesByCoordinate($request);
                return  $this->rentResponse($houses);
            }
            // user provide zip code
            else {
                $houses = $this->getHousesByZipCode($request);
                return $this->rentResponse($houses);
            }

        } catch (\Throwable $e) {

            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $e->getMessage(),
            ], 422);
        }
    }
}
