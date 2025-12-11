<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\MenuType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $menus = Menu::with('type')
                     ->orderBy('date', 'desc')
                     ->paginate(20); // paginatsioon

        return view('admin.menus.index', compact('menus'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $menuTypes = MenuType::all();
        return view('admin.menus.create', compact('menuTypes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'menu_type_id' => 'required|exists:menu_types,id',
            'date'         => 'required|date|unique:menus,date,NULL,id,menu_type_id,' . $request->menu_type_id,
            'header_line1' => 'nullable|string|max:255',
            'header_line2' => 'nullable|string|max:255',
            'header_line3' => 'nullable|string|max:255',
            'background_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
            'is_visible' => 'boolean',
        ]);

        $data = $request->only([
            'menu_type_id', 'date', 'header_line1', 'header_line2', 'header_line3'
        ]);

        // Nähtavus
        $data['is_visible'] = $request->boolean('is_visible');

        // Taustapilt
        if ($request->hasFile('background_image')) {
            $file = $request->file('background_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/images', $filename);
            $data['background_image'] = $filename;
        }

        Menu::create($data);

        return redirect()->route('menus.index')->with('success', 'Menüü lisatud!');
    }
    

    /**
     * Display the specified resource.
     */
    public function show(Menu $menu)
    {
        return view('admin.menus.show', compact('menu'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Menu $menu)
    {
        $menuTypes = MenuType::all();
        return view('admin.menus.edit', compact('menu', 'menuTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Menu $menu)
    {
        $request->validate([
            'menu_type_id' => 'required|exists:menu_types,id',
            'date' => 'required|date',
            'header_line1' => 'nullable|string|max:255',
            'header_line2' => 'nullable|string|max:255',
            'header_line3' => 'nullable|string|max:255',
            'background_image' => 'nullable|image|max:2048',
            'is_visible' => 'boolean',
        ]);

        $data = $request->only([
            'menu_type_id', 'date', 'header_line1', 'header_line2', 'header_line3'
        ]);

        // Nähtavus
        $data['is_visible'] = $request->boolean('is_visible');

        // Taustapilt
        if ($request->hasFile('background_image')) {
            // Eemalda vana pilt, kui olemas
            if ($menu->background_image && Storage::exists('public/images/'.$menu->background_image)) {
                Storage::delete('public/images/'.$menu->background_image);
            }

            $file = $request->file('background_image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/images', $filename);
            $data['background_image'] = $filename;
        }

        $menu->update($data);

        return redirect()->route('menus.index')->with('success', 'Menüü uuendatud!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Menu $menu)
    {
        $menu->delete(); // soft delete
        return redirect()->route('menus.index')->with('success', 'Menüü kustutatud!');
    }
}
