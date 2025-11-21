<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $today = now()->toDateString();

        // Leia lõunamenüü tüüp
        $menuType = DB::table('menu_types')
            ->where('name', 'louna')
            ->first();

        if (!$menuType) {
            $this->command->error('Menu type "louna" not found. Run MenuTypeSeeder first.');
            return;
        }

        // Kustutame sama päeva vanad seeditud menüüd
        DB::table('menus')->where('date', $today)->delete();

        // Loo Lõunamenüü tänaseks päevaks
        $menuId = DB::table('menus')->insertGetId([
            'menu_type_id' => $menuType->id,
            'date' => $today,
            'header_line1' => 'Ilus päev maitsvaks lõunaks!',
            'header_line2' => 'SOOJA SÖÖGI AEG',
            'header_line3' => 'PARIM MENÜÜ PÄEVALE',
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Leia kõik lõunamenüü kategooriad
        $categories = DB::table('categories')
            ->where('menu_type_id', $menuType->id)
            ->orderBy('order_index')
            ->get();

        foreach ($categories as $category) {

            if (Str::lower($category->name) === 'koolilõuna') {
                // Koolilõuna: 1 toit
                DB::table('menu_items')->insert([
                    'menu_id' => $menuId,
                    'category_id' => $category->id,
                    'name' => 'Praekapsas',
                    'full_price' => 0,
                    'half_price' => 0,
                    'included_in_main' => false,
                    'gluten_free' => false,
                    'lactose_free' => false,
                    'is_available' => false,
                    'order_index' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // Kõik muud kategooriad: 2 toitu
                DB::table('menu_items')->insert([
                    [
                        'menu_id' => $menuId,
                        'category_id' => $category->id,
                        'name' => $category->name . ' toit 1',
                        'full_price' => 3.50,
                        'half_price' => 2.00,
                        'included_in_main' => false,
                        'gluten_free' => rand(0,1),
                        'lactose_free' => rand(0,1),
                        'is_available' => true,
                        'order_index' => 1,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                    [
                        'menu_id' => $menuId,
                        'category_id' => $category->id,
                        'name' => $category->name . ' toit 2',
                        'full_price' => 4.00,
                        'half_price' => null,
                        'included_in_main' => false,
                        'gluten_free' => rand(0,1),
                        'lactose_free' => rand(0,1),
                        'is_available' => true,
                        'order_index' => 2,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            }
        }
    
    }
}
