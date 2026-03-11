<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->get('search', ''));

        // TOP 10 populaarseimad toidud
        $popularFoods = MenuItem::query()
            ->selectRaw("
                LOWER(REPLACE(REPLACE(name, ' ', ''), '-', '')) as normalized_name,
                COUNT(*) as total
            ")
            ->groupBy('normalized_name')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        $popularFoods->transform(function ($food) {

            $variant = MenuItem::query()
                ->select('name', DB::raw('COUNT(*) as c'))
                ->whereRaw(
                    "LOWER(REPLACE(REPLACE(name, ' ', ''), '-', '')) = ?",
                    [$food->normalized_name]
                )
                ->groupBy('name')
                ->orderByDesc('c')
                ->first();

            $food->display_name = ucfirst(mb_strtolower(trim($variant->name)));

            return $food;
        });

        // Toidu ajalugu
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
        

        // Menüütüüpide statistika
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

        return view('admin.statistics.index', compact(
            'popularFoods',
            'foodHistory',
            'menuTypeStats',
            'search'
        ));
    }
}