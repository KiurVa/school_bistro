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
            ->select(
                'menu_items.normalized_name',
                DB::raw('COUNT(*) as total'),
                DB::raw('MAX(menu_items.name) as display_name')
            )
            ->groupBy('menu_items.normalized_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // -----------------------------------
        // Toidu esinemise ajalugu
        // -----------------------------------

        $foodHistory = collect();

        if ($search !== '') {
            $search = trim(urldecode($request->get('search', '')));
            $normalizedSearch = strtolower(str_replace([' ', '-'], '', $search));

            $foodHistory = MenuItem::query()
                ->with(['menu.type', 'category', 'allergens'])
                ->join('menus', 'menu_items.menu_id', '=', 'menus.id')
                ->where('menu_items.normalized_name', 'like', '%' . $normalizedSearch . '%')
                ->orderByDesc('menus.date')
                ->select('menu_items.*')
                ->paginate(20)
                ->appends(['search' => $search]);
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
            ->distinct('normalized_name')
            ->count('normalized_name');

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
