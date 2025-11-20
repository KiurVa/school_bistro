<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('menu_types')->insert([
            [
                'name' => 'louna',
                'display_name' => 'Lõunamenüü',
                'show_prices' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'hommik',
                'display_name' => 'Hommikusöök',
                'show_prices' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'laager',
                'display_name' => 'Laagrimenüü',
                'show_prices' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'uritus',
                'display_name' => 'Üritusemenüü',
                'show_prices' => false,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
