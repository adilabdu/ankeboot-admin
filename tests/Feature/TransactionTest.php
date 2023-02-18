<?php

use App\Models\Book;
use App\Models\Store;
use App\Models\StoreBook;
use App\Models\Transaction;
use Database\Seeders\StoreSeeder;

test('GET request returns 200', function () {
    $response = $this->getWithHeaders('/api/transactions');

    $response->assertStatus(200);
});

test('POST purchase request returns 201', function () {
    $book = Book::factory()->create();
    $this->seed(StoreSeeder::class);

    $primaryStore = Store::primary() ?? Store::factory()->primary()->create();
    $secondStore = Store::all()[rand(1, Store::count() - 1)];

    $firstStoreAmount = [
        'store_id' => $primaryStore->id,
        'amount' => rand(20, 250),
    ];
    $secondStoreAmount = [
        'store_id' => $secondStore->id,
        'amount' => rand(150, 500),
    ];

    $response = $this->postWithHeaders('/api/transactions', [
        'invoice' => 'ank_trans_' . rand(1, 1000),
        'book_id' => $book->id,
        'transaction_on' => fake()->dateTimeBetween('-6 months', 'now')->format('d-m-Y'),
        'type' => 'purchase',
        'quantity' => [
            $firstStoreAmount,
            $secondStoreAmount
        ],
    ]);

    $response->assertJson([
        'data' => [
            'quantity' => ($firstStoreAmount['amount'] + $secondStoreAmount['amount'])
        ]
    ]);
    $this->assertDatabaseHas('store_books', [
        'store_id' => $primaryStore->id,
        'book_id' => $book->id,
        'balance' => $secondStore->id === $primaryStore->id ?
            ($firstStoreAmount['amount'] + $secondStoreAmount['amount']) :
            $firstStoreAmount['amount']
    ]);
    $this->assertDatabaseHas('store_books', [
        'store_id' => $secondStore->id,
        'book_id' => $book->id,
        'balance' => $secondStore->id === $primaryStore->id ?
            ($firstStoreAmount['amount'] + $secondStoreAmount['amount']) :
            $secondStoreAmount['amount']
    ]);
    $this->assertDatabaseHas('store_transactions', [
        'store_id' => $primaryStore->id,
        'quantity' => $firstStoreAmount['amount'],
        'transaction_id' => $response['data']['id']
    ]);
    $this->assertDatabaseHas('store_transactions', [
        'store_id' => $secondStore->id,
        'quantity' => $secondStoreAmount['amount'],
        'transaction_id' => $response['data']['id']
    ]);
    $response->assertCreated();
});

test('POST purchase request updates book balance', function () {
    $book = Transaction::latest()->first()->book;

    $store_balance = StoreBook::where([
        'book_id' => $book->id
    ])->sum('balance');

    $this->assertEquals((int) $book->balance, (int) $store_balance);
});

test('POST sale request returns 201', function () {
    $storeBook = StoreBook::where('store_id', Store::primary()->id)
                ->where('balance', '>' , 0)
                ->get()->random();

    $initialBalance = $storeBook->balance;
    $saleQuantity = rand(1, $initialBalance);

    $response = $this->postWithHeaders('/api/transactions', [
        'invoice' => 'ank_trans_' . fake()->unique()->numberBetween(1, 1000),
        'book_id' => $storeBook->book_id,
        'transaction_on' => fake()->dateTimeBetween('-2 months')->format('d-m-Y'),
        'type' => 'sale',
        'quantity' => $saleQuantity
    ]);

    $this->assertDatabaseHas('store_books', [
        'store_id' => $storeBook->store_id,
        'book_id' => $storeBook->book_id,
        'balance' => ($initialBalance - $saleQuantity)
    ]);
    $this->assertDatabaseHas('store_transactions', [
        'store_id' => $storeBook->store_id,
        'quantity' => $saleQuantity,
        'transaction_id' => $response['data']['id']
    ]);
    $response->assertCreated();
});

test('POST sale request with insufficient balance returns 422', function () {
    $storeBook = StoreBook::where('store_id', Store::primary()->id)
        ->get()->random();

    $response = $this->postWithHeaders('/api/transactions', [
        'invoice' => 'ank_trans_' . fake()->unique()->numberBetween(1, 1000),
        'book_id' => $storeBook->book_id,
        'transaction_on' => fake()->dateTimeBetween('-2 months')->format('d-m-Y'),
        'type' => 'sale',
        'quantity' => $storeBook->balance + rand(1, 100)
    ]);

    $response->assertStatus(422);
});

test('POST sale request updates book balance', function () {
    $book = Transaction::latest()->first()->book;

    $store_balance = StoreBook::where([
        'book_id' => $book->id
    ])->sum('balance');

    $this->assertEquals((int) $book->balance, (int) $store_balance);
});

test('GET request returns 200 for single transaction', function () {
    $response = $this->getWithHeaders('/api/transactions?id=1');

    $response->assertStatus(200);
});
