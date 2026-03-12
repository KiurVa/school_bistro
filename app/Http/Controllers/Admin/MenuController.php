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
     * Märkus: kui uus menüü märgitakse nähtavaks (is_visible = true),
     * siis tehakse kõik teised menüüd mitte nähtavaks.
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

        // Võtame ainult lubatud väljad
        $data = $request->only([
            'menu_type_id',
            'date',
            'header_line1',
            'header_line2',
            'header_line3',
        ]);

        // Kas checkbox "tee nähtavaks" on märgitud?
        $isVisible = $request->boolean('is_visible');
        $data['is_visible'] = $isVisible;

        // Kui uus menüü tehakse nähtavaks, lülitame kõik teised menüüd nähtavuse maha
        if ($isVisible) {
            Menu::where('is_visible', true)->update(['is_visible' => false]);
        }

        Menu::create($data);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menüü lisatud!');
    }

    /**
     * Kuva konkreetse menüü detailid (vajadusel).
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
     */
    public function edit(Menu $menu)
    {
        $menuTypes = MenuType::all();

        return view('admin.menus.edit', compact('menu', 'menuTypes'));
    }

    /**
     * Uuenda olemasoleva menüü andmeid.
     *
     * Märkus: kui see menüü märgitakse nähtavaks (is_visible = true),
     * siis lülitame kõik teised menüüd nähtavuse maha.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_type_id' => 'required|exists:menu_types,id',
            'header_line1' => 'nullable|string|max:255',
            'header_line2' => 'nullable|string|max:255',
            'header_line3' => 'nullable|string|max:255',
            'is_visible'   => 'boolean',
        ]);

        // Võtame ainult lubatud väljad
        $data = $request->only([
            'menu_type_id',
            'header_line1',
            'header_line2',
            'header_line3',
        ]);

        // Kas checkbox "tee nähtavaks" on märgitud?
        $isVisible = $request->boolean('is_visible');
        $data['is_visible'] = $isVisible;

        // Kui see menüü on märgitud nähtavaks, lülitame kõik teised menüüd välja
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
     * Kustuta menüü (soft delete, kui mudelis on SoftDeletes).
     */
    public function destroy(Menu $menu)
    {
        $menu->delete();

        return redirect()
            ->route('menus.index')
            ->with('success', 'Menüü kustutatud!');
    }

    /**
     * Määra konkreetne menüü nähtavaks (aktiivseks) ühe nupuvajutusega.
     *
     * Kasutus: "Määra aktiivseks" nupp menüüde nimekirjas.
     * Loogika:
     *  - kõik menüüd seatakse is_visible = false
     *  - antud menüü seatakse is_visible = true
     *
     * Tulemuseks on, et korraga saab olla nähtav ainult üks menüü.
     */
    public function setVisible(Menu $menu)
    {
        // Lülita kõik menüüd nähtamatusse
        Menu::where('is_visible', true)->update(['is_visible' => false]);

        // Tee antud menüü nähtavaks
        $menu->update(['is_visible' => true]);

        return redirect()
            ->route('menus.index')
            ->with('success', 'Aktiivne menüü uuendatud.');
    }

    /**
     * Muuda antud menüü mitteaktiivseks (is_visible = false).
     *
     * Märkus: sel juhul võib jäädagi 0 aktiivset menüüd,
     * mis on täiesti lubatud.
     */
    public function unsetVisible(Menu $menu)
    {
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
