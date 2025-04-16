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

    //function to fetch houses if coordinates are provided , query from logment_encadrement table
    private function getHousesByCoordinate(Request $request)
    {

        $validated = $this->validationRequest($request);
        $houses = LogementEncadrement::where('geographic_point_2d', '=', $validated["coordinates"])
                                ->where('room_number', '=', $validated["room_number"])
                                ->where('construction_period', '=', $validated["construction_period"])
                                ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished')
                                ->get();
        $maxRent = $houses->max('major_reference');
        $minRent = $houses->min('minor_reference');
        $averageRent = $houses->avg('reference');
        return ["maxRent" => $maxRent, "minRent" => $minRent, "averageRent" => $averageRent];

    }
    public function getRentRange(Request $request)
    {
        try {

            $validated = $this->validationRequest($request);
            $zip_code = $validated["zip_code"] ?? null;
            $coordinates = $validated["coordinates"] ?? null;
            if (!empty($coordinates)) {
                $houses = $this->getHousesByCoordinate($request);
                $maxRent = $houses["maxRent"];
                $minRent = $houses["minRent"];
                $averageRent = $houses["averageRent"];

                return    response()->json([
                        "status" => "success",
                        "min" => $minRent,
                        "max" => $maxRent,
                        "average" => $averageRent,
                    ], 200);
            } else {
                // user provide zip code which exist only in the quartiersParis table
                $houses = DB::table('quartiers_paris')
                  ->where('zip_code', '=', $zip_code)
                  ->join('logement_encadrements', 'quartiers_paris.geometry_X_Y', '=', 'logement_encadrements.geographic_point_2d')
                  ->where('room_number', '=', $validated["room_number"])
                  ->where('construction_period', '=', $validated["construction_period"])
                  ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished')
                  ->get();
                $maxRent = $houses->max('major_reference');
                $minRent = $houses->min('minor_reference');
                $averageRent = $houses->avg('reference');
                return response()->json([
                    "status" => "success",
                    "min" => $minRent,
                    "max" => $maxRent,
                    "average" => $averageRent,
                ], 200);
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
