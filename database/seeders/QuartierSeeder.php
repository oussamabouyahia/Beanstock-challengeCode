<?php

namespace Database\Seeders;

use App\Models\QuartiersParis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuartierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $quartierFile = file_get_contents(storage_path('app/data/quartier_paris.csv'));
        //get array of lines from the file
        $rawLines = explode("\n", $quartierFile);
        //titles of columns array
        $headerArray = explode(";", trim($rawLines[0]));
        for ($i = 1;$i < count($rawLines);$i++) {
            $line = trim($rawLines[$i]);
            if (empty($line)) {
                continue;
            }
            $values = explode(";", $line);
            // check line has the same number of fields as the header
            if (count($values) !== count($headerArray)) {
                continue;
            }
            $row = array_combine($headerArray, $values);
            QuartiersParis::create([
                'N_SQ_QU' => $row['N_SQ_QU'] ?? null,
                'street_number' => $row['Numéro du quartier / C_QU'] ?? null,
                'C_QUINSEE' => $row['C_QUINSEE'] ?? null,
                'L_QU' => $row['L_QU'] ?? null,
                'C_AR' => $row['C_AR'] ?? null,
                'N_SQ_AR' => $row['N_SQ_AR'] ?? null,
                'perimetre' => $row['PERIMETRE'] ?? null,
                'surface' => $row['SURFACE'] ?? null,
                'geometry_X_Y' => $row['Geometry X Y'] ?? null,
                'zip_code' => $row['ZIP CODE'] ?? null,
            ]);
        }

    }
}
