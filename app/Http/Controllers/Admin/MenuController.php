<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Menu;
use App\Models\MenuType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    /**
     * Kuva menüüde nimekiri (admin vaade).
     */
    public function index()
    {
        $menus = Menu::with('type')
            ->orderBy('created_at', 'desc')
            ->paginate(20); // lehekülgede kaupa

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Kuva vorm uue menüü loomiseks.
     */
    public function create()
    {
        $menuTypes = MenuType::all();

        return view('admin.menus.create', compact('menuTypes'));
    }

    /**
     * Salvesta uus menüü andmebaasi.
     *
     * Kui uus menüü märgitakse nähtavaks, siis tehakse kõik teised menüüd mitte nähtavaks.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_type_id' => 'required|exists:menu_types,id',
            'date' => [
                'required',
                'date',
                Rule::unique('menus')->where(
                    fn($query) =>
                    $query->where('menu_type_id', $request->menu_type_id)
                ),
            ],
            'header_line1' => 'nullable|string|max:255',
            'header_line2' => 'nullable|string|max:255',
            'header_line3' => 'nullable|string|max:255',
            'is_visible'   => 'boolean',
        ], [
            'date.unique' => 'Selle menüü tüübiga menüü on tänaseks juba olemas.',
        ], [
            'date' => 'kuupäev',
        ]);

        $data = $request->only([
            'menu_type_id',
            'date',
            'header_line1',
            'header_line2',
            'header_line3',
        ]);

        $isVisible = $request->boolean('is_visible');
        $data['is_visible'] = $isVisible;

        if ($isVisible) {
            Menu::where('is_visible', true)->update(['is_visible' => false]);
        }

        Menu::create($data);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menüü lisatud!');
    }

    /**
     * Kuva konkreetse menüü detailid.
     */
    public function show(Menu $menu)
    {
        $categories = Category::where('menu_type_id', $menu->menu_type_id)
            ->where('is_visible', true)
            ->orderBy('order_index')
            ->with(['items' => function ($query) use ($menu) {
                $query->where('menu_id', $menu->id)
                    ->orderBy('order_index')
                    ->with('allergens');
            }])
            ->get();

        return view('admin.menus.show', compact('menu', 'categories'));
    }

    /**
     * Kuva menüü muutmise vorm.
     *
     * Muutmine on lubatud ainult tänase ja tuleviku kuupäevaga menüüdel.
     */
    public function edit(Menu $menu)
    {
        if ($menu->date->lt(now()->startOfDay())) {
            return redirect()
                ->route('menus.index')
                ->with('error', 'Eilse ja vanema kuupäevaga menüüd ei saa muuta.');
        }

        $menuTypes = MenuType::all();

        return view('admin.menus.edit', compact('menu', 'menuTypes'));
    }

    /**
     * Uuenda olemasoleva menüü andmeid.
     *
     * Muutmine on lubatud ainult tänase ja tuleviku kuupäevaga menüüdel.
     * Kui menüü märgitakse nähtavaks, lülitatakse kõik teised menüüd välja.
     */
    public function update(Request $request, Menu $menu)
    {
        if ($menu->date->lt(now()->startOfDay())) {
            return redirect()
                ->route('menus.index')
                ->with('error', 'Eilse ja vanema kuupäevaga menüüd ei saa muuta.');
        }

        $request->validate([
            'menu_type_id' => 'required|exists:menu_types,id',
            'header_line1' => 'nullable|string|max:255',
            'header_line2' => 'nullable|string|max:255',
            'header_line3' => 'nullable|string|max:255',
            'is_visible'   => 'boolean',
        ]);

        $data = $request->only([
            'menu_type_id',
            'header_line1',
            'header_line2',
            'header_line3',
        ]);

        $isVisible = $request->boolean('is_visible');
        $data['is_visible'] = $isVisible;

        if ($isVisible) {
            Menu::where('is_visible', true)
                ->where('id', '!=', $menu->id)
                ->update(['is_visible' => false]);
        }

        // kontroll enne update, et kaks sama menüütüüpi pole
        $exists = Menu::where('menu_type_id', $request->menu_type_id)
            ->whereDate('date', $menu->date)
            ->where('id', '!=', $menu->id)
            ->exists();

        if ($exists) {
            return back()
                ->withErrors([
                    'menu_type_id' => 'Selle menüü tüübiga menüü on tänaseks juba olemas.'
                ])
                ->withInput();
        }

        $menu->update($data);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menüü uuendatud!');
    }

    /**
     * Kustuta menüü.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menüü kustutatud!');
    }

    /**
     * Määra konkreetne menüü aktiivseks.
     *
     * Aktiivsust saab muuta ainult tänase kuupäevaga menüül.
     */
    public function setVisible(Menu $menu)
    {
        if (!$menu->date->isToday()) {
            return redirect()
                ->route('menus.index')
                ->with('error', 'Aktiivsust saab muuta ainult tänase kuupäevaga menüül.');
        }

        Menu::where('is_visible', true)->update(['is_visible' => false]);

        $menu->update(['is_visible' => true]);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Aktiivne menüü uuendatud.');
    }

    /**
     * Muuda menüü mitteaktiivseks.
     *
     * Aktiivsust saab muuta ainult tänase kuupäevaga menüül.
     */
    public function unsetVisible(Menu $menu)
    {
        if (!$menu->date->isToday()) {
            return redirect()
                ->route('menus.index')
                ->with('error', 'Aktiivsust saab muuta ainult tänase kuupäevaga menüül.');
        }

        $menu->update(['is_visible' => false]);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menüü on nüüd mitteaktiivne.');
    }

    /**
     * Menüü kopeerimine
     */
    public function duplicate(Menu $menu)
    {
        $today = now()->toDateString();

        $exists = Menu::where('menu_type_id', $menu->menu_type_id)
            ->whereDate('date', $today)
            ->exists();

        if ($exists) {
            return redirect()->route('menus.index')
                ->with('error', 'Selle menüü tüübiga menüü on tänaseks juba olemas.');
        }

        DB::transaction(function () use ($menu) {

            $menu->load('items.allergens');

            $newMenu = $menu->replicate();
            $newMenu->date = now()->toDateString();
            $newMenu->save();

            foreach ($menu->items as $item) {

                $newItem = $item->replicate();
                $newItem->menu_id = $newMenu->id;
                $newItem->save();

                $newItem->allergens()->sync(
                    $item->allergens->pluck('id')
                );
            }
        });

        return redirect()->route('menus.index')
            ->with('success', 'Menüü kopeeritud');
    }
}
