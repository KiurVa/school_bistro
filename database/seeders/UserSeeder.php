<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => 'Parool1',
                'role' => 'admin',
                'is_admin' => '1'
            ]
        );

        // Tavakasutaja
        User::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'User',
                'password' => 'Parool1',
                'role' => 'user',
            ]
        );
    }
}
