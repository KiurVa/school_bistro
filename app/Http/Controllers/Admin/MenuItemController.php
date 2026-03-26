<?php

namespace App\Http\Controllers\Admin;

use App\Models\Allergen;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\MenuController;
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

        $full = $request->full_price !== null
            ? str_replace(',', '.', $request->full_price)
            : null;

        $half = $request->half_price !== null
            ? str_replace(',', '.', $request->half_price)
            : null;

        $request->merge([
            'full_price' => $full === '' ? null : $full,
            'half_price' => $half === '' ? null : $half,
        ]);

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
        MenuController::clearCache();

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

        $full = $request->full_price !== null
            ? str_replace(',', '.', $request->full_price)
            : null;

        $half = $request->half_price !== null
            ? str_replace(',', '.', $request->half_price)
            : null;

        $request->merge([
            'full_price' => $full === '' ? null : $full,
            'half_price' => $half === '' ? null : $half,
        ]);

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

        $item->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'full_price' => $request->full_price,
            'half_price' => $request->half_price,
            'is_available' => $request->boolean('is_available'),
            'order_index' => $request->order_index ?? 0,
        ]);

        $item->allergens()->sync($request->allergens ?? []);
        MenuController::clearCache();

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

        $item->delete();
        MenuController::clearCache();

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toit edukalt kustutatud');
    }

    public function search(Request $request)
    {
        $validated = $request->validate([
            'term' => 'required|string|max:255',
            'category_id' => 'nullable|integer|exists:categories,id',
        ]);

        $term = $validated['term'];

        if (strlen($term) < 3) {
            return response()->json([]);
        }

        $query = MenuItem::where('name', 'LIKE', '%' . $term . '%');

        if (!empty($validated['category_id'])) {
            $query->where('category_id', $validated['category_id']);
        }

        $items = $query->orderBy('name')
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

        $items = $request->input('items', []);

        foreach ($items as $categoryId => $rows) {
            foreach ($rows as $index => $row) {
                foreach (['full_price', 'half_price'] as $field) {
                    if (isset($row[$field])) {
                        $value = str_replace(',', '.', $row[$field]);
                        $items[$categoryId][$index][$field] = $value === '' ? null : $value;
                    }
                }
            }
        }

        $request->merge(['items' => $items]);

        $validator = Validator::make($request->all(), [
            'items' => 'array',
            'items.*' => 'array',
            'items.*.*.id' => 'nullable|integer',
            'items.*.*.name' => 'nullable|string|max:255',
            'items.*.*.full_price' => 'nullable|numeric|max:999999.99',
            'items.*.*.half_price' => 'nullable|numeric|max:999999.99',
            'items.*.*.allergens' => 'nullable|array',
            'items.*.*.allergens.*' => 'exists:allergens,id',
        ], [
            'items.*.*.full_price.max' => 'Täishind ei tohi olla suurem kui 999999.99.',
            'items.*.*.half_price.max' => 'Poolhind ei tohi olla suurem kui 999999.99.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput();
        }

        // Kontrollime, et kõik category_id-d kuuluvad selle menüü tüübile
        $validCategoryIds = Category::where('menu_type_id', $menu->menu_type_id)
            ->pluck('id')
            ->toArray();

        foreach (array_keys($request->input('items', [])) as $categoryId) {
            if (!in_array((int) $categoryId, $validCategoryIds)) {
                return back()->with('error', 'Vigane kategooria.')->withInput();
            }
        }

        $existingItems = MenuItem::where('menu_id', $menu->id)
            ->get()
            ->keyBy('id');

        DB::transaction(function () use ($request, $menu, $existingItems) {
            foreach ($request->input('items') as $categoryId => $rows) {
                foreach ($rows as $row) {
                    $itemId = $row['id'] ?? null;
                    $name = trim($row['name'] ?? '');

                    /*
                     * DELETE
                     */
                    if (!empty($row['delete']) && $itemId) {
                        if (isset($existingItems[$itemId])) {
                            $existingItems[$itemId]->delete();
                            unset($existingItems[$itemId]);
                        }
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
                        $item = $existingItems[$itemId] ?? null;

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
                        $existingItems[$item->id] = $item;
                    }

                    /*
                     * ALLERGENID
                     */
                    if (isset($row['allergens'])) {
                        $item->allergens()->sync($row['allergens']);
                    }
                }
            }
        });

        MenuController::clearCache();

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
        MenuController::clearCache();

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
        MenuController::clearCache();

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Toit märgiti mitte saadavaks.');
    }
}
