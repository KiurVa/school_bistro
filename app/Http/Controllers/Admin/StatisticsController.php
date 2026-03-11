<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use App\Models\MenuType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        // Otsing toidu ajaloo jaoks
        $search = trim($request->get('search', ''));

        // TOP 10 filtrid
        $topCategory = $request->get('top_category');
        $topMenuType = $request->get('top_menu_type');

        // Filtrite valikud vaatesse
        $topCategories = Category::query()
            ->orderBy('name')
            ->get();

        $topMenuTypes = MenuType::query()
            ->orderBy('display_name')
            ->get();

        // -----------------------------------
        // TOP 10 populaarseimad toidud
        // -----------------------------------

        $popularFoodsQuery = MenuItem::query()
            ->join('menus', 'menu_items.menu_id', '=', 'menus.id');

        // Filtreerimine kategooria järgi
        if (!empty($topCategory)) {
            $popularFoodsQuery->where('menu_items.category_id', $topCategory);
        }

        // Filtreerimine menüütüübi järgi
        if (!empty($topMenuType)) {
            $popularFoodsQuery->where('menus.menu_type_id', $topMenuType);
        }

        $popularFoods = $popularFoodsQuery
            ->selectRaw("
                LOWER(REPLACE(REPLACE(menu_items.name, ' ', ''), '-', '')) as normalized_name,
                COUNT(*) as total
            ")
            ->groupBy('normalized_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // Leiame iga grupi kõige sagedamini esinenud nimekuju
        $popularFoods->transform(function ($food) use ($topCategory, $topMenuType) {
            $variantQuery = MenuItem::query()
                ->join('menus', 'menu_items.menu_id', '=', 'menus.id')
                ->select('menu_items.name', DB::raw('COUNT(*) as c'))
                ->whereRaw(
                    "LOWER(REPLACE(REPLACE(menu_items.name, ' ', ''), '-', '')) = ?",
                    [$food->normalized_name]
                );

            if (!empty($topCategory)) {
                $variantQuery->where('menu_items.category_id', $topCategory);
            }

            if (!empty($topMenuType)) {
                $variantQuery->where('menus.menu_type_id', $topMenuType);
            }

            $variant = $variantQuery
                ->groupBy('menu_items.name')
                ->orderByDesc('c')
                ->first();

            $food->display_name = ucfirst(mb_strtolower(trim($variant->name)));

            return $food;
        });

        // -----------------------------------
        // Toidu esinemise ajalugu
        // -----------------------------------

        $foodHistory = collect();

        if ($search !== '') {
            $normalizedSearch = mb_strtolower(str_replace([' ', '-'], '', $search));

            $foodHistory = MenuItem::query()
                ->with(['menu.type', 'category', 'allergens'])
                ->whereRaw(
                    "LOWER(REPLACE(REPLACE(name, ' ', ''), '-', '')) LIKE ?",
                    ['%' . $normalizedSearch . '%']
                )
                ->whereHas('menu')
                ->get()
                ->sortByDesc(fn ($item) => optional($item->menu)->date)
                ->values();
        }

        // -----------------------------------
        // Menüütüüpide kasutusstatistika
        // -----------------------------------

        $menuTypeStats = MenuType::query()
            ->select(
                'menu_types.id',
                'menu_types.display_name',
                DB::raw('COUNT(menus.id) as usage_count')
            )
            ->leftJoin('menus', 'menu_types.id', '=', 'menus.menu_type_id')
            ->groupBy('menu_types.id', 'menu_types.display_name')
            ->orderByDesc('usage_count')
            ->get();

        // -----------------------------------
        // Päise kaartide andmed
        // -----------------------------------

        $latestMenu = Menu::query()
            ->orderByDesc('date')
            ->first();

        $uniqueFoodsCount = MenuItem::query()
            ->selectRaw("LOWER(REPLACE(REPLACE(name, ' ', ''), '-', '')) as normalized_name")
            ->distinct()
            ->count();

        $summary = [
            // Menüüsid kokku
            'menu_count' => Menu::count(),

            // Viimase menüü kuupäev
            'latest_menu_date' => optional($latestMenu?->date)->format('d.m.Y'),

            // Erinevate toitude arv
            'unique_foods_count' => $uniqueFoodsCount,

            // Kõige populaarsem toit (vastavalt TOP tabeli loogikale)
            'most_popular_food' => $popularFoods->first()->display_name ?? '-',

            // Menüütüüpide arv
            'menu_type_count' => $topMenuTypes->count(),

            // Kõige sagedamini kasutatud menüütüüp
            'most_used_menu_type' => $menuTypeStats->first()->display_name ?? '-',
        ];

        return view('admin.statistics.index', compact(
            'popularFoods',
            'foodHistory',
            'menuTypeStats',
            'search',
            'topCategories',
            'topCategory',
            'topMenuTypes',
            'topMenuType',
            'summary'
        ));
    }
}