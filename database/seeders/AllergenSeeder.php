<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AllergenSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $allergens = [
            ['name' => 'Gluteen', 'code' => 'G', 'order_index' => 1],
            ['name' => 'Vegan', 'code' => 'V', 'order_index' => 2],
            ['name' => 'Vürtsikas', 'code' => 'Vürts', 'order_index' => 3],
        ];

        foreach ($allergens as $allergen) {
            DB::table('allergens')->insert([
                'name' => $allergen['name'],
                'code' => $allergen['code'],
                'order_index' => $allergen['order_index'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
