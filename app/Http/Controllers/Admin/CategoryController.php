<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuType;  
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('menuType')
            ->orderBy('menu_type_id')
            ->orderBy('order_index')
            ->get();

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Lisa kategooria vorm
     */
    public function create()
    {
        $menuTypes = MenuType::orderBy('display_name')->get();

        return view('admin.categories.create', compact('menuTypes'));
    }

    /**
     * Salvestamine
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'menu_type_id' => 'required|exists:menu_types,id',
        ]);

        $maxOrder = Category::max('order_index') ?? 0;

        Category::create([
            'menu_type_id' => $request->menu_type_id,
            'name'         => $request->name,
            'order_index'  => $maxOrder + 1,
            'is_visible'   => $request->has('is_visible'),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategooria lisatud.');
    }

    /**
     * Muutmise vorm
     */
    public function edit(Category $category)
    {
        $menuTypes = MenuType::orderBy('display_name')->get();

        return view('admin.categories.edit', compact('category', 'menuTypes'));
    }

    /**
     * Uuendamine
     */
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name'         => 'required|string|max:255',
            'menu_type_id' => 'required|exists:menu_types,id',
        ]);

        $category->update([
            'name'         => $request->name,
            'menu_type_id' => $request->menu_type_id,
            'is_visible'   => $request->has('is_visible'),
        ]);

        return redirect()->route('categories.index')
            ->with('success', 'Kategooria uuendatud.');
    }

    public function destroy(Category $category)
    {
        if ($category->items()->exists()) {
            return back()->with('error', 'Kategooriat ei saa kustutada, sest sellega on seotud toidud menüüdes.');
        }
        $category->delete();

        return redirect()->route('categories.index')
            ->with('success', 'Kategooria kustutatud.');
    }

    public function moveUp(Category $category)
    {
        $prev = Category::where('menu_type_id', $category->menu_type_id)
            ->where('order_index', '<', $category->order_index)
            ->orderBy('order_index', 'desc')
            ->first();

        if ($prev) {
            $currentOrder = $category->order_index;

            $category->update(['order_index' => $prev->order_index]);
            $prev->update(['order_index' => $currentOrder]);
        }

        return redirect()->route('categories.index');
    }

    public function moveDown(Category $category)
    {
        $next = Category::where('menu_type_id', $category->menu_type_id)
            ->where('order_index', '>', $category->order_index)
            ->orderBy('order_index', 'asc')
            ->first();

        if ($next) {
            $currentOrder = $category->order_index;

            $category->update(['order_index' => $next->order_index]);
            $next->update(['order_index' => $currentOrder]);
        }

        return redirect()->route('categories.index');
    }
}
