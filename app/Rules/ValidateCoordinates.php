<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidateCoordinates implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */


    //customised validation rule to check if the coordinates are in the format latitude,longitude

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $coordinatesParts = explode(',', $value);
        if (count($coordinatesParts) !== 2) {
            $fail('The :attribute must be a valid coordinate format (latitude,longitude).');
            return;
        }
        $latitude = trim($coordinatesParts[0]);
        $longitude = trim($coordinatesParts[1]);
        if (!is_numeric($latitude) || !is_numeric($longitude)) {
            $fail('The :attribute must be a valid coordinate format (latitude,longitude).');
            return;
        }
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            $fail('latitude must be between -90 and 90 degrees, and longitude must be between -180 and 180 degrees.');
            return;
        }


    }
}
