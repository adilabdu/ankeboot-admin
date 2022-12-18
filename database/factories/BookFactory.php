<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'code' => 'ank' . fake()->unique()->randomNumber(4),
            'type' => fake()->randomElement(['cash', 'consignment']),
            'title' => fake()->sentence(4),
            'category' => fake()->word,
            'balance' => 0,
        ];
    }
}
