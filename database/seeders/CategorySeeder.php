<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
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
            ['name' => 'Koolilõuna', 'color' => '#FF4242', 'order' => 1],
            ['name' => 'Supid', 'color' => null, 'order' => 2],
            ['name' => 'Praed', 'color' => null, 'order' => 3],
            ['name' => 'Lisandid', 'color' => null, 'order' => 4],
            ['name' => 'Kastmed', 'color' => null, 'order' => 5],
            ['name' => 'Salatid', 'color' => null, 'order' => 6],
            ['name' => 'Magustoidud', 'color' => null, 'order' => 7],
        ];

        foreach ($lounaCategories as $cat) {
            DB::table('categories')->insert([
                'menu_type_id' => 1,
                'name' => $cat['name'],
                'color' => $cat['color'],
                'order_index' => $cat['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Hommikusöök kategooriad (menu_type_id = 2)
        DB::table('categories')->insert([
            'menu_type_id' => 2,
            'name' => 'Hommikutoidud',
            'color' => null,
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
                'color' => null,
                'order_index' => $cat['order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Üritusemenüü kategooriad (menu_type_id = 4)
        DB::table('categories')->insert([
            'menu_type_id' => 4,
            'name' => 'Ürituse road',
            'color' => null,
            'order_index' => 1,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
