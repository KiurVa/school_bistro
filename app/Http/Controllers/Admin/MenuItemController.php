<?php

namespace App\Http\Controllers\Admin;

use App\Models\Allergen;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class MenuItemController extends Controller
{
    /**
     * Kontrollib, kas menüü on eilse või vanema kuupäevaga.
     * Sellisel juhul ei tohi selle menüü toite enam muuta.
     */
    private function ensureMenuIsEditable(Menu $menu)
    {
        if ($menu->date->lt(now()->startOfDay())) {
            return redirect()
                ->route('menus.index')
                ->with('error', 'Eilse ja vanema kuupäevaga menüü toite ei saa muuta.');
        }

        return null;
    }

    /*
     * Näita toidu lisamise vormi
     */
    public function create(Menu $menu)
    {
        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $categories = Category::where('menu_type_id', $menu->menu_type_id)
            ->orderBy('order_index')
            ->get();

        $allergens = Allergen::orderBy('order_index')->get();

        return view('admin.menu_items.create', compact('menu', 'categories', 'allergens'));
    }

    /*
     * Salvesta uus toidukirje
     */
    public function store(Request $request, Menu $menu)
    {
        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'full_price' => 'nullable|numeric|max:999999.99',
            'half_price' => 'nullable|numeric|max:999999.99',
            'is_available' => 'boolean',
            'order_index' => 'nullable|integer',
            'allergens' => 'array',
            'allergens.*' => 'exists:allergens,id',
        ], [
            'full_price.max' => 'Täishind ei tohi olla suurem kui 999999.99.',
            'half_price.max' => 'Poolhind ei tohi olla suurem kui 999999.99.',
        ]);

        $item = $menu->items()->create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'full_price' => $request->full_price,
            'half_price' => $request->half_price,
            'is_available' => $request->boolean('is_available'),
            'order_index' => $request->order_index ?? 0,
        ]);

        $item->allergens()->sync($request->allergens ?? []);

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toit lisatud!');
    }

    /*
     * Toidu muutmine
     */
    public function edit(Menu $menu, MenuItem $item)
    {
        abort_unless($item->menu_id === $menu->id, 404);

        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $categories = Category::where('menu_type_id', $menu->menu_type_id)
            ->orderBy('order_index')
            ->get();

        $allergens = Allergen::orderBy('order_index')->get();

        return view('admin.menu_items.edit', [
            'menu' => $menu,
            'item' => $item,
            'categories' => $categories,
            'allergens' => $allergens,
        ]);
    }

    /*
     * Salvesta muudatused
     */
    public function update(Request $request, Menu $menu, MenuItem $item)
    {
        abort_unless($item->menu_id === $menu->id, 404);

        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'full_price' => 'nullable|numeric|max:999999.99',
            'half_price' => 'nullable|numeric|max:999999.99',
            'is_available' => 'boolean',
            'order_index' => 'nullable|integer',
            'allergens' => 'array',
        ], [
            'full_price.max' => 'Täishind ei tohi olla suurem kui 999999.99.',
            'half_price.max' => 'Poolhind ei tohi olla suurem kui 999999.99.',
        ]);

        $item->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'full_price' => $request->full_price,
            'half_price' => $request->half_price,
            'is_available' => $request->boolean('is_available'),
            'order_index' => $request->order_index ?? 0,
        ]);

        $item->allergens()->sync($request->allergens ?? []);

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toit uuendatud!');
    }

    /*
     * Kustuta toidukirje
     */
    public function destroy(Menu $menu, MenuItem $item)
    {
        abort_unless($item->menu_id === $menu->id, 404);

        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $item->allergens()->detach();
        $item->delete();

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toit edukalt kustutatud');
    }

    public function search(Request $request)
    {
        $term = $request->term;

        if (strlen($term) < 3) {
            return response()->json([]);
        }

        $items = MenuItem::where('name', 'LIKE', '%' . $term . '%')
            ->orderBy('name')
            ->limit(10)
            ->distinct()
            ->get(['name']);

        return response()->json($items);
    }

    public function bulkCreate(Menu $menu)
    {
        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $categories = Category::where('menu_type_id', $menu->menu_type_id)
            ->orderBy('order_index')
            ->get();

        $itemsByCategory = $menu->items()
            ->with('allergens')
            ->orderBy('order_index')
            ->get()
            ->groupBy('category_id');

        $allergens = Allergen::orderBy('order_index')->get();

        return view('admin.menu_items.bulk_create', [
            'menu' => $menu,
            'categories' => $categories,
            'itemsByCategory' => $itemsByCategory,
            'allergens' => $allergens,
        ]);
    }

    public function bulkSave(Request $request, Menu $menu)
    {
        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        if (!$request->has('items')) {
            return back()->with('error', 'Toite ei leitud.');
        }

        $validator = Validator::make($request->all(), [
            'items' => 'array',
            'items.*.*.id' => 'nullable|integer',
            'items.*.*.name' => 'nullable|string|max:255',
            'items.*.*.full_price' => 'nullable|numeric|max:999999.99',
            'items.*.*.half_price' => 'nullable|numeric|max:999999.99',
        ], [
            'items.*.*.full_price.max' => 'Täishind ei tohi olla suurem kui 999999.99.',
            'items.*.*.half_price.max' => 'Poolhind ei tohi olla suurem kui 999999.99.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        DB::transaction(function () use ($request, $menu) {
            foreach ($request->items as $categoryId => $rows) {
                foreach ($rows as $row) {
                    $itemId = $row['id'] ?? null;
                    $name = trim($row['name'] ?? '');

                    /*
                     * DELETE
                     */
                    if (!empty($row['delete']) && $itemId) {
                        MenuItem::where('menu_id', $menu->id)
                            ->find($itemId)
                            ?->delete();

                        continue;
                    }

                    /*
                     * Kui nimi tühi → ignoreeri rida
                     */
                    if ($name === '') {
                        continue;
                    }

                    $item = null;

                    /*
                     * UPDATE olemasolev kirje
                     */
                    if ($itemId) {
                        $item = MenuItem::where('menu_id', $menu->id)
                            ->find($itemId);

                        if ($item) {
                            $item->update([
                                'category_id' => $categoryId,
                                'name' => $name,
                                'full_price' => $row['full_price'] ?? null,
                                'half_price' => $row['half_price'] ?? null,
                                'is_available' => isset($row['is_available']),
                            ]);
                        }
                    }

                    /*
                     * CREATE uus kirje
                     */
                    if (!$item) {
                        $item = $menu->items()->create([
                            'category_id' => $categoryId,
                            'name' => $name,
                            'full_price' => $row['full_price'] ?? null,
                            'half_price' => $row['half_price'] ?? null,
                            'is_available' => isset($row['is_available']),
                            'order_index' => 0,
                        ]);
                    }

                    /*
                     * ALLERGENID
                     */
                    $item->allergens()->sync($row['allergens'] ?? []);
                }
            }
        });

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toidud salvestati edukalt!');
    }

    public function setAvailable(Menu $menu, MenuItem $item)
    {
        abort_unless($item->menu_id === $menu->id, 404);

        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $item->update(['is_available' => true]);

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toit on nüüd saadaval.');
    }

    public function unsetAvailable(Menu $menu, MenuItem $item)
    {
        abort_unless($item->menu_id === $menu->id, 404);

        if ($response = $this->ensureMenuIsEditable($menu)) {
            return $response;
        }

        $item->update(['is_available' => false]);

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toit märgiti mitte saadavaks.');
    }
}