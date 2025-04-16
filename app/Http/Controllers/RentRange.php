<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\LogementEncadrement;
use App\Rules\ValidateCoordinates;
use Illuminate\Http\Request;

class RentRange extends Controller
{
    public function getRentRange(Request $request)
    {
        try {
            //the coordinates need custom validation see ValidateCoordinates rule
            $validated = $request->validate([
                       "zip_code" => "nullable|required_without:coordinates|regex:/^\d{5}$/",
                       "coordinates" => ["nullable", "required_without:zip_code", new ValidateCoordinates()],
                       "room_number" => "required|integer|min:1",
                       "construction_period" => "required|in:Avant 1946,1946-1970,1971-1990,Apres 1990",
                       "furnished" => "required|boolean"
                   ]);
            $zip_code = $validated["zip_code"] ?? null;
            $coordinates = $validated["coordinates"] ?? null;
            // dd($validated);
            if (!empty($coordinates)) {
                $logments = LogementEncadrement::where('geographic_point_2d', '=', $coordinates)
                        ->where('room_number', '=', $validated["room_number"])
                        ->where('construction_period', '=', $validated["construction_period"])
                        ->where('furnished_type', '=', $validated["furnished"] ? 'furnished' : 'unfurnished')
                        ->get();
                // dd($logments);
                return    response()->json([
                        "status" => "success",
                        "data" => $logments
                    ]);
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
