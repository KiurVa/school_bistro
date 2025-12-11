<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function show(Request $request)
{
    // Admin valib menüütüübi (nt radio nupp)
    $menuTypeId = $request->input('menu_type_id', 1); // Default: lõuna

    // Täna menüü
    $menu = Menu::where('menu_type_id', $menuTypeId)
                ->where('date', now()->toDateString())
                ->where('is_visible', true)
                ->first();

    // Kui tänase menüü kirjet pole, siis $menu jääb nulliks
    // Blade oskab ise kuvada "Menüüd pole veel sisestatud"
    $categories = collect(); // tühi kogumik

    if ($menu) {
        // Kui menüü leiti → laadime kategooriad + toidud
        $categories = Category::where('menu_type_id', $menuTypeId)
                              ->where('is_visible', true)
                              ->orderBy('order_index')
                              ->with(['menuItems' => function($query) use ($menu) {
                                  $query->where('menu_id', $menu->id)
                                        ->orderBy('order_index')
                                        ->with('allergens');
                              }])
                              ->get();
    }

    return view('menu', compact('menu', 'categories', 'menuTypeId'));
}
}
