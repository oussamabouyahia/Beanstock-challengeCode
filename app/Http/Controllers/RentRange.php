<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Rules\ValidateCoordinates;
use Illuminate\Http\Request;

class RentRange extends Controller
{
    public function getRentRange(Request $request)
    {
        $validated = $request->validate([
            "zip_code" => "required|regex:/^\d{5}$/",
            "coordinates" => ["required",new ValidateCoordinates() ],
            "room_number" => "required|integer|min:1",
            "construction_period" => "required|in:Avant 1946,1946-1970,1971-1990,Apres 1990",
            "furnished" => "required|boolean"
        ]);
    }
}
