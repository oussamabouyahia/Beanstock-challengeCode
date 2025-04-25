<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogementEncadrement>
 */
class LogementEncadrementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'geographic_sector' => $this->faker->word(),
            'street_number' => $this->faker->buildingNumber(),
            'street_name' => $this->faker->streetName(),
            'room_number' => $this->faker->numberBetween(1, 5),
            'construction_period' => $this->faker->randomElement([
                'Avant 1946', '1946-1970', '1971-1990', 'Apres 1990'
            ]),
            'furnished_type' => $this->faker->randomElement(['furnished', 'unfurnished']),
            'reference' => $this->faker->randomFloat(2, 500, 1500),
            'major_reference' => $this->faker->randomFloat(2, 1000, 2000),
            'minor_reference' => $this->faker->randomFloat(2, 300, 999),
            'year' => $this->faker->year(),
            'city' => 'PARIS',
            'INSEE_code' => $this->faker->numerify('7510##'),
            'geographic_shape' => json_encode([
                'coordinates' => [
                    [[$this->faker->longitude(), $this->faker->latitude()]],
                ],
            ]),
            'geographic_point_2d' => $this->faker->latitude(48.80, 48.90) . ',' . $this->faker->longitude(2.30, 2.40),

        ];
    }
}
