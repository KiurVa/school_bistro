<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\BackgroundImage;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show(Request $request)
{

    // Täna menüü
    $menu = Menu::where('date', now()->toDateString())
                ->where('is_visible', true)
                ->first();

    // Kui tänase menüü kirjet pole, siis $menu jääb nulliks
    // Blade oskab ise kuvada "Menüüd pole veel sisestatud"
    $categories = collect(); // tühi kogumik

    if ($menu) {
        // Kui menüü leiti → laadime kategooriad + toidud
        $categories = Category::where('menu_type_id', $menu->menu_type_id)
                              ->where('is_visible', true)
                              ->orderBy('order_index')
                              ->with(['items' => function($query) use ($menu) {
                                  $query->where('menu_id', $menu->id)
                                        ->orderBy('order_index')
                                        ->with('allergens');
                              }])
                              ->get();
    }

    $background = BackgroundImage::where('is_active', true)
        ->orderByDesc('created_at')
        ->first();

    return view('menu', compact('menu', 'categories', 'background'));
}
}
