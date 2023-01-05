<?php

namespace Database\Seeders;

use App\Models\Store;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StoreSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {

        try {
            Store::create([
                'name' => 'Shop',
                'primary' => true,
            ]);
            Store::create([
                'name' => 'Bole Store 1',
            ]);
            Store::create([
                'name' => 'Bole Store 2'
            ]);
            Store::create([
                'name' => 'Bole Store 3'
            ]);
            Store::create([
                'name' => 'Welete Warehouse'
            ]);
        } catch (Exception $e) {
            dd('Error: ', $e->getMessage());
        }

    }
}
