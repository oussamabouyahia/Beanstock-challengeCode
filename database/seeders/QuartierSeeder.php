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
    private function convertQuinseeToInsee(string $quinsee)
    {
        $prefix = substr($quinsee, 0, 5);
        //two main digits goes from 01 to 20
        $twoMainDigit = (int) substr($quinsee, 3, 2);
        // could be 01,02,03,04
        $lastTwoDigits = substr($quinsee, -2);
        // echo $twoMainDigit;
        if ($twoMainDigit === 1) {
            return $quinsee;
        }
        switch ($lastTwoDigits) {
            case '01':
                $convertLastDigits = 4 * $twoMainDigit - 3;
                break;
            case '02':
                $convertLastDigits = 4 * $twoMainDigit - 2;
                break;
            case '03':
                $convertLastDigits = 4 * $twoMainDigit - 1;
                break;
            case '04':
                $convertLastDigits = 4 * $twoMainDigit;
                break;

                break;
            default:
                $convertLastDigits = $lastTwoDigits;
        }
        // this is only for the case of one digit 1-9 , I add 0 to match Insee Column in Logements table
        if ($convertLastDigits < 10) {
            $convertLastDigits = '0' . $convertLastDigits;
        }

        return $prefix . $convertLastDigits;



    }

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
                //the solution here is to re-seed the Quartiers table with matched Insee code (in Logements table)
                'C_QUINSEE' =>  $this->convertQuinseeToInsee($row['C_QUINSEE'])  ?? null,
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
