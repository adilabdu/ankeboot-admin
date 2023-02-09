<?php

namespace Database\Seeders;

use Exception;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        try {
            DB::table('users')->insertOrIgnore([
                [
                    'name' => 'Test User',
                    'email' => 'testuser@test.com',
                    'username' => 'testuser',
                    'phone_number' => '0912345678',
                    'password' => Hash::make('helloworld'),
                    'telegram_chat_id' => '5783481231',
                ],
                [
                    'name' => 'Adil Abdu Bushra',
                    'email' => 'adil@ankeboot.com',
                    'username' => 'adil',
                    'phone_number' => '0912272145',
                    'password' => Hash::make('helloworld'),
                    'telegram_chat_id' => '340546535',
                ],
            ]);
        } catch (Exception $e) {
            dd('Error: '.$e->getMessage());
        }
    }
}
