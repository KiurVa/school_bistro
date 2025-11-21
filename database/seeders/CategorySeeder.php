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
            ['name' => 'Koolilõuna', 'order' => 10],
            ['name' => 'Supid', 'order' => 20],
            ['name' => 'Praed', 'order' => 30],
            ['name' => 'Lisandid', 'order' => 40],
            ['name' => 'Kastmed', 'order' => 50],
            ['name' => 'Salatid', 'order' => 60],
            ['name' => 'Magustoidud', 'order' => 70],
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
            ['name' => 'Hommikusöök', 'order' => 10],
            ['name' => 'Lõunasöök', 'order' => 20],
            ['name' => 'Õhtusöök', 'order' => 30],
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
