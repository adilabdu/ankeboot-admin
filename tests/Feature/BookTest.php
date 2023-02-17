<?php

use App\Models\Book;
use App\Models\Supplier;

test('GET request returns 200', function () {
    $response = $this->getWithHeaders('/api/books');

    $response->assertStatus(200);
});

test('POST request returns 201', function () {

    $response = $this->postWithHeaders('/api/books', [
        'code' => 'ank' . fake()->unique()->randomNumber(4),
        'type' => fake()->randomElement(['consignment', 'cash']),
        'title' => fake()->sentence(4),
        'author' => fake()->name,
        'category' => fake()->word,
        'supplier_id' => Supplier::factory()->create()->id,
    ]);

    $response->assertStatus(201);
});

test('GET paginated request returns 200', function () {
    $response = $this->getWithHeaders(
        '/api/books/paginate?per_page=5'
    );

    $response->assertStatus(200);
});
