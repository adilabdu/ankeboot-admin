<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Address>
 */
class AddressFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'country' => fake()->randomElement([fake()->country, 'Ethiopia']),
            'city' => fake()->randomElement([fake()->city, 'Addis Ababa']),
            'sub_city' => fake()->words(2, true),
            'kebele' => fake()->numberBetween(1, 30),
            'house_number' => fake()->numberBetween(1, 100),
        ];
    }
}
