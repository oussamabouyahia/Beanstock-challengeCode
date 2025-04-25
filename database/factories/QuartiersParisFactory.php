<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\QuartiersParis>
 */
class QuartiersParisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
             'N_SQ_QU' => $this->faker->unique()->randomNumber(4),
        'street_number' => (string) $this->faker->numberBetween(1, 200),
        'C_QUINSEE' => (string) $this->faker->unique()->numberBetween(7510101, 7512099),
        'L_QU' => 'Quartier ' . $this->faker->numberBetween(1, 20),
        'C_AR' => (string) $this->faker->numberBetween(1, 20),
        'N_SQ_AR' => $this->faker->unique()->randomNumber(4),
        'perimetre' => $this->faker->randomFloat(2, 2000, 6000),
        'surface' => $this->faker->randomFloat(2, 100000, 1000000),
        'geometry_X_Y' => '48.8566,2.3522',
        'zip_code' => $this->faker->numberBetween(75000, 75100),
        ];
    }
}
