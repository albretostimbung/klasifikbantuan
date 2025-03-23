<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Citizen>
 */
class CitizenFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'age' => fake()->randomNumber(2),
            'income' => fake()->randomFloat(2, 0, 1000000000),
            'occupation' => fake()->jobTitle(),
            'number_of_dependent' => fake()->randomNumber(2),
            'residence_status' => fake()->randomElement(['Milik sendiri', 'Dinas', 'Lainnya']),
            'last_education' => fake()->randomElement(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2', 'S3']),
            'vehicle_ownership' => fake()->boolean(),
            'marital_status' => fake()->boolean(),
            'last_social_assistance' => fake()->boolean(),
        ];
    }
}
