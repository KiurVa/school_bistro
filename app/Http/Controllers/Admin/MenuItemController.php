<?php

namespace App\Http\Controllers\Admin;

use App\Models\Allergen;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class MenuItemController extends Controller
{
    /*
     * Näita toidu lisamise vormi
     */
    public function create(Menu $menu)
    {
        $categories = Category::where('menu_type_id', $menu->menu_type_id)
            ->orderBy('order_index')
            ->get();
        $allergens  = Allergen::orderBy('order_index')->get();

        return view('admin.menu_items.create', compact('menu', 'categories', 'allergens'));
    }

    /*
     * Salvesta uus toidukirje
     */
    public function store(Request $request, Menu $menu)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'full_price'  => 'nullable|numeric|max:999999.99',
            'half_price'  => 'nullable|numeric|max:999999.99',
            'is_available' => 'boolean',
            'order_index' => 'nullable|integer',
            'allergens'   => 'array',
        ], [
            'full_price.max' => 'Täishind ei tohi olla suurem kui 999999.99.',
            'half_price.max' => 'Poolhind ei tohi olla suurem kui 999999.99.',
        ]);

        $item = $menu->items()->create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'full_price'  => $request->full_price,
            'half_price'  => $request->half_price,
            'is_available' => $request->is_available ?? true,
            'order_index' => $request->order_index ?? 0,
        ]);

        // Salvestame allergeenid pivot tabelisse
        if ($request->filled('allergens')) {
            $item->allergens()->sync($request->allergens);
        }

        return back()->with('success', 'Toit lisatud!');
    }

    /*
     * Toidu muutmine
     */
    public function edit(Menu $menu, MenuItem $item)
    {
        $categories = Category::where('menu_type_id', $menu->menu_type_id)
            ->orderBy('order_index')
            ->get();
        $allergens  = Allergen::orderBy('order_index')->get();

        return view('admin.menu_items.edit', compact('menu', 'item', 'categories', 'allergens'));
    }

    /*
     * Salvesta muudatused
     */
    public function update(Request $request, Menu $menu, MenuItem $item)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name'        => 'required|string|max:255',
            'full_price'  => 'nullable|numeric|max:999999.99',
            'half_price'  => 'nullable|numeric|max:999999.99',
            'is_available' => 'boolean',
            'order_index' => 'nullable|integer',
            'allergens'   => 'array',
        ], [
            'full_price.max' => 'Täishind ei tohi olla suurem kui 999999.99.',
            'half_price.max' => 'Poolhind ei tohi olla suurem kui 999999.99.',
        ]);

        $item->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'full_price'  => $request->full_price,
            'half_price'  => $request->half_price,
            'is_available' => $request->is_available ?? true,
            'order_index' => $request->order_index ?? 0,
        ]);

        $item->allergens()->sync($request->allergens ?? []);

        return back()->with('success', 'Toit uuendatud!');
    }

    /*
     * Kustuta toidukirje
     */
    public function destroy(Menu $menu, MenuItem $item)
    {
        $item->allergens()->detach();
        $item->delete();

        return back()->with('success', 'Toit kustutatud!');
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
        $categories = Category::where('menu_type_id', $menu->menu_type_id)
            ->orderBy('order_index')
            ->get();
        $itemsByCategory = $menu->items()
            ->with('allergens')
            ->orderBy('order_index')
            ->get()
            ->groupBy('category_id');
        $allergens = Allergen::orderBy('order_index')->get();
        return view('admin.menu_items.bulk_create', compact('menu', 'categories', 'allergens', 'itemsByCategory'));
    }

    public function bulkSave(Request $request, Menu $menu)
    {
        // Kontroll, et items[] üldse olemas on
        if (!$request->has('items')) {
            return back()->with('error', 'Toite ei leitud.');
        }

        $validator = Validator::make($request->all(), [
            'items' => 'array',
            'items.*.*.id' => 'nullable|integer',
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

        foreach ($request->items as $categoryId => $rows) {

            foreach ($rows as $row) {
                $name = $row['name'] ?? null;
                $itemId = $row['id'] ?? null;

                // Kui nimi tühi – jätame vahele
                if (empty($name)) {
                    continue;
                }

                $item = null;
                if ($itemId) {
                    $item = $menu->items()->where('id', $itemId)->first();
                    if ($item) {
                        $item->update([
                            'category_id' => $categoryId,
                            'name'        => $name,
                            'full_price'  => $row['full_price'] ?? null,
                            'half_price'  => $row['half_price'] ?? null,
                            'is_available' => isset($row['is_available']) ? 1 : 0,
                        ]);
                    }
                }

                if (!$item) {
                    // Loo toidukirje
                    $item = $menu->items()->create([
                        'category_id' => $categoryId,
                        'name'        => $name,
                        'full_price'  => $row['full_price'] ?? null,
                        'half_price'  => $row['half_price'] ?? null,
                        'is_available' => isset($row['is_available']) ? 1 : 0,
                        'order_index' => 0, // bulk lisamisel ei pane sortimist
                    ]);
                }

                // Kui allergeenid on lisatud – lisa pivotisse
                if (!empty($row['allergens'])) {
                    $item->allergens()->sync($row['allergens']);
                } else {
                    $item->allergens()->sync([]);
                }
            }
        }

        return redirect()
            ->route('menus.show', $menu)
            ->with('success', 'Kõik toidud on edukalt salvestatud!');
    }
}
