<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $today = now()->toDateString();

        $menuType = DB::table('menu_types')
            ->where('name', 'louna')
            ->first();

        if (!$menuType) {
            $this->command->error('Menu type "louna" not found.');
            return;
        }

        // Kustuta sama päeva menüü
        DB::table('menus')
            ->where('menu_type_id', $menuType->id)
            ->whereDate('date', $today)
            ->delete();

        $menuId = DB::table('menus')->insertGetId([
            'menu_type_id' => $menuType->id,
            'date' => $today,
            'header_line1' => 'Tänane lõunamenüü',
            'header_line2' => 'Head isu!',
            'header_line3' => '',
            'is_visible' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $foods = [
            'Koolilõuna' => [
                'Maksakaste kartuliga',
                'Kanafilee riisiga',
                'Hakklihakaste makaronidega',
                'Ahjukana kartuliga',
                'Kalapulk kartulipüreega',
            ],

            'Supid' => [
                'Hakklihasupp',
                'Kana-klimbisupp',
                'Seljanka',
                'Köögiviljasupp',
                'Herne- ja suitsulihasupp',
            ],

            'Praed' => [
                'Seapraad ahjukartuliga',
                'Kanašnitsel riisiga',
                'Hakklihapallid kartulipüreega',
                'Sealiha koorekastmes',
                'Ahjulõhe köögiviljadega',
            ],

            'Lisandid' => [
                'Keedukartul',
                'Kartulipüree',
                'Riis',
                'Tatar',
                'Ahjukartul',
            ],

            'Kastmed' => [
                'Maksakaste',
                'Hakklihakaste',
                'Seenekaste',
                'Koorekaste',
                'Piprakaste',
            ],

            'Salatid' => [
                'Värske kapsasalat',
                'Porgandisalat',
                'Kurgi-hapukooresalat',
                'Peedisalat',
                'Kartulisalat',
            ],

            'Magustoidud' => [
                'Mannavaht',
                'Kohupiimakreem',
                'Šokolaadipuding',
                'Kissell vahukoorega',
                'Õunakook',
            ],
        ];

        foreach ($foods as $categoryName => $items) {

            $category = DB::table('categories')
                ->where('menu_type_id', $menuType->id)
                ->where('name', $categoryName)
                ->first();

            if (!$category) {
                $this->command->warn("Category not found: {$categoryName}");
                continue;
            }

            $order = 1;

            foreach ($items as $food) {

                DB::table('menu_items')->insert([
                    'menu_id' => $menuId,
                    'category_id' => $category->id,
                    'name' => $food,
                    'full_price' => $categoryName === 'Koolilõuna' ? 0 : rand(350, 650) / 100,
                    'half_price' => $categoryName === 'Koolilõuna' ? null : rand(150, 350) / 100,
                    'is_available' => true,
                    'order_index' => $order++,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }

        $this->command->info('Lõunamenüü loodud.');
    }
}