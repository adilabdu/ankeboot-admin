<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

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
            'code' => 'ank'.fake()->unique()->randomNumber(4),
            'type' => fake()->randomElement(['cash', 'consignment']),
            'title' => fake()->sentence(4),
            'category' => fake()->word,
            'balance' => 0,
            'supplier_id' => Supplier::factory()->create()->id,
        ];
    }

    /**
     * Indicate that the books were registered last year.
     *
     * @return self
     */
    public function lastYear(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'created_at' => fake()->dateTimeBetween('-1 year', '-6 months'),
            ];
        });
    }

    /**
     * Indicate that the book has no supplier.
     *
     */
    public function noSupplier(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'supplier_id' => null,
            ];
        });
    }
}
