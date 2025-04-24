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


    public function run(): void
    {
        // get the list of C_QUINSEE keys and ids as values
        $map = QuartiersParis::pluck('id', 'C_QUINSEE')->all();

        // I implement chunkById to avoid memory issues already logements table has more than 7000 houses
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
