<?php

namespace Database\Seeders;

use App\Models\LogementEncadrement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LogementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $logementFilePath = storage_path('app/data/logement-encadrement-des-loyers.csv');
        $logementFile = file_get_contents($logementFilePath);
        //get array of lines from the file
        $rawLines = explode("\n", $logementFile);
        //get header from first line $rawLines[0]
        $headerArray = explode(";", trim($rawLines[0]));
        // create associative array  with header as keys and values as values
        for ($i = 1;$i < count($rawLines);$i++) {
            $line = trim($rawLines[$i]);
            if (empty($line)) {
                continue;
            }
            $values = explode(";", $line);

            // check line has the same length fields as the header
            if (count($values) !== count($headerArray)) {
                continue;
            }

            $row = array_combine($headerArray, $values);
            // Logement instance creation for each row
            LogementEncadrement::create([
                'geographic_sector'               => $row['Secteurs géographiques'] ?? null,
                'street_number'                   => $row['Numéro du quartier'] ?? null,
                'street_name'                     => $row['Nom du quartier'] ?? null,
                'room_number'                     => $row['Nombre de pièces principales'] ?? null,
                'construction_period'             => $row['Epoque de construction'] ?? null,
                'furnished_type'                  => $row['Type de location'] === 'meublé' ? 'furnished' : 'unfurnished',
                'reference'                       => $row['Loyers de référence'] ?? null,
                'major_reference'                 => $row['Loyers de référence majorés'] ?? null,
                'minor_reference'                 => $row['Loyers de référence minorés'] ?? null,
                'year'                            => $row['Année'] ?? null,
                'city'                            => $row['Ville'] ?? null,
                'INSEE_code'                      => $row['Numéro INSEE du quartier'] ?? null,
                'geographic_shape'                => $row['geo_shape'] ?? null,
                'geographic_point_2d'             => $row['geo_point_2d'] ?? null,
            ]);

        }
    }
}
