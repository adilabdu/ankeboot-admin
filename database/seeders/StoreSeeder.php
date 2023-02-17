<?php

namespace Database\Seeders;

use App\Models\Store;
use Exception;
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
            Store::firstOrCreate([
                'name' => 'Shop',
                'primary' => true,
            ]);
            Store::firstOrCreate([
                'name' => 'Bole Store 1',
            ]);
            Store::firstOrCreate([
                'name' => 'Bole Store 2',
            ]);
            Store::firstOrCreate([
                'name' => 'Bole Store 3',
            ]);
            Store::firstOrCreate([
                'name' => 'Welete Warehouse',
            ]);
        } catch (Exception $e) {
            dd('Error: ', $e->getMessage());
        }
    }
}
