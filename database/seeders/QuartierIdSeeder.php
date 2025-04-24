<?php

namespace Database\Seeders;

use App\Models\LogementEncadrement;
use App\Models\QuartiersParis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuartierIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    //this function is helper function to convert Insee_code to C_QUINSEE
    private function getCorrespondantC_Quinsee($quinseeString, $insee)
    {
        $prefix = substr($quinseeString, 0, 5);
        $oneToTwenty = (int) substr($quinseeString, 3, 5);
        $inseeTwoLastDigits = substr($insee, -2);


    }
    public function run(): void
    {
        // get the list of C_QUINSEE keys and ids as values
        $map = QuartiersParis::pluck('id', 'C_QUINSEE')->all();


        LogementEncadrement::chunkById(100, function ($batch) use ($map) {
            foreach ($batch as $logement) {

                $insee = $logement->INSEE_code;
                if (isset($map[$insee])) {

                    $logement->quartier_id = $map[$insee];
                    $logement->save();
                }
            }
        });
    }
}
