<?php

namespace Database\Seeders;

use App\Models\Book;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        try {
            Book::factory()->count(20)->create();
        } catch (Exception $e) {
            dd('Error: ', $e->getMessage());
        }

    }
}
