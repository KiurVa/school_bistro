<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\BackgroundImage;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    // Cache'i võtmed – kasutatakse ka admin-kontrollerites tühjendamiseks
    const CACHE_KEY      = 'menu.public';
    const MODIFIED_KEY   = 'menu.last_modified';
    const CACHE_SECONDS  = 30;

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
                ->with(['items' => function ($query) use ($menu) {
                    $query->where('menu_id', $menu->id)
                        ->orderBy('order_index')
                        ->with('allergens');
                }])
                ->get()
                ->filter(function ($category) {
                    return $category->items->isNotEmpty();
                })
                ->values();
        }

        $background = BackgroundImage::where('is_active', true)
            ->orderByDesc('created_at')
            ->first();

        return view('menu', compact('menu', 'categories', 'background'));
    }

    /**
     * JS kontrollib seda endpointi iga 30s tagant.
     * Tagastab viimase muutmise ajatempli.
     * location.reload() tehakse ainult siis kui timestamp on muutunud.
     */
    public function lastModified()
    {
        $timestamp = Cache::get(self::MODIFIED_KEY, 0);

        return response()->json(['timestamp' => $timestamp]);
    }

    /**
     * Tühjendab avaliku menüü cache'i ja uuendab last_modified timestampi.
     * Kutsutakse välja kõigist admin-kontroleritest, mis menüüd muudavad.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::put(self::MODIFIED_KEY, now()->timestamp, 86400);
    }
}
