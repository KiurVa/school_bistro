<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MenuController extends Controller
{
    /**
     * Kuva menüüde nimekiri (admin vaade).
     */
    public function index()
    {
        $menus = Menu::with('type')
            ->orderBy('date', 'desc')
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
            'date'         => 'required|date|unique:menus,date,NULL,id,menu_type_id,' . $request->menu_type_id,
            'header_line1' => 'nullable|string|max:255',
            'header_line2' => 'nullable|string|max:255',
            'header_line3' => 'nullable|string|max:255',
            'is_visible'   => 'boolean',
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
        return view('admin.menus.show', compact('menu'));
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
            'date' => [
            'required',
            'date',
            Rule::unique('menus')
                ->where('menu_type_id', $request->menu_type_id)
                ->ignore($menu->id),
        ],
            'header_line1' => 'nullable|string|max:255',
            'header_line2' => 'nullable|string|max:255',
            'header_line3' => 'nullable|string|max:255',
            'is_visible'   => 'boolean',
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

        // Kui see menüü on märgitud nähtavaks, lülitame kõik teised menüüd välja
        if ($isVisible) {
            Menu::where('is_visible', true)
                ->where('id', '!=', $menu->id)
                ->update(['is_visible' => false]);
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

}
