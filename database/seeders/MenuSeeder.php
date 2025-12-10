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

        // Otsi "G" (Gluteen) allergeeni ID üks kord
        $gluteenId = DB::table('allergens')
            ->where('code', 'G')
            ->value('id');

        $this->command->info("Gluteen ID: " . ($gluteenId ?? 'EI LEITUD'));

        foreach ($categories as $category) {

            if (Str::lower($category->name) === 'koolilõuna') {
                // Koolilõuna: 1 toit
                DB::table('menu_items')->insert([
                    'menu_id' => $menuId,
                    'category_id' => $category->id,
                    'name' => 'Praekapsas',
                    'full_price' => 0,
                    'is_available' => true,
                    'order_index' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            } else {
                // TOIT 1 - ILMA ALLERGEENITA
                $toit1Id = DB::table('menu_items')->insertGetId([
                    'menu_id' => $menuId,
                    'category_id' => $category->id,
                    'name' => $category->name . ' toit 1',
                    'full_price' => 3.50,
                    'half_price' => 2.00,
                    'is_available' => true,
                    'order_index' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // TOIT 2 - GLUTEENIGA
                $toit2Id = DB::table('menu_items')->insertGetId([
                    'menu_id' => $menuId,
                    'category_id' => $category->id,
                    'name' => $category->name . ' toit 2',
                    'full_price' => 4.00,
                    'half_price' => null,
                    'is_available' => true,
                    'order_index' => 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                // Lisa gluteen ainult toit 2 juurde
                if ($gluteenId) {
                    DB::table('allergen_menu_item')->insert([
                        'menu_item_id' => $toit2Id,
                        'allergen_id' => $gluteenId,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->command->info("Lisatud gluteen toidule: {$category->name} toit 2 (ID: {$toit2Id})");
                }
            }
        }
    }
}