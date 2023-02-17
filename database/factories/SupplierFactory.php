<?php

namespace Database\Factories;

use App\Models\Address;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Supplier>
 */
class SupplierFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tin_number' => fake()->unique()->randomNumber(9),
            'name' => fake()->company,
            'address_id' => Address::factory()->create(),
            'email' => fake()->companyEmail,
            'phone' => fake()->phoneNumber,
        ];
    }
}
