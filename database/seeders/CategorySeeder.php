<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Lõunamenüü kategooriad (menu_type_id = 1)
        $lounaCategories = [
            ['name' => 'Koolilõuna', 'order' => 1],
            ['name' => 'Supid', 'order' => 2],
            ['name' => 'Praed', 'order' => 3],
            ['name' => 'Lisandid', 'order' => 4],
            ['name' => 'Kastmed', 'order' => 5],
            ['name' => 'Salatid', 'order' => 6],
            ['name' => 'Magustoidud', 'order' => 7],
        ];

        foreach ($lounaCategories as $cat) {
            DB::table('categories')->insert([
                'menu_type_id' => 1,
                'name' => $cat['name'],
                'order_index' => $cat['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Hommikusöök kategooriad (menu_type_id = 2)
        DB::table('categories')->insert([
            'menu_type_id' => 2,
            'name' => 'Hommikusöögid',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Laagrimenüü kategooriad (menu_type_id = 3)
        $laagerCategories = [
            ['name' => 'Hommikusöök', 'order' => 1],
            ['name' => 'Lõunasöök', 'order' => 2],
            ['name' => 'Õhtusöök', 'order' => 3],
        ];

        foreach ($laagerCategories as $cat) {
            DB::table('categories')->insert([
                'menu_type_id' => 3,
                'name' => $cat['name'],
                'order_index' => $cat['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Üritusemenüü kategooriad (menu_type_id = 4)
        DB::table('categories')->insert([
            'menu_type_id' => 4,
            'name' => 'Ürituse road',
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
