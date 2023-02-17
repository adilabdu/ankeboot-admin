<?php

namespace Database\Factories;

use App\Models\Address;
use App\Models\Store;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Store>
 */
class StoreFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->address,
            'primary' => false,
            'address_id' => Address::factory()->create()->id,
        ];
    }

    /**
     * Indicate that the store is primary.
     *
     * @return $this
     */
    public function primary(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'primary' => true,
            ];
        });
    }

    /**
     * Indicate that the store has no address
     *
     * @return $this
     */
    public function noAddress(): self
    {
        return $this->state(function (array $attributes) {
            return [
                'address_id' => null,
            ];
        });
    }
}
