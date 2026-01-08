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

        $menuTypes = MenuType::all()
            ->sortBy(function ($type) {
                $order = ['louna', 'hommik', 'uritus', 'laager'];
                $position = array_search($type->name, $order, true);
                return $position === false ? 999 : $position;
            })
            ->values();

        return view('admin.categories.index', compact('categories', 'menuTypes'));
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
            'order_index'  => 'nullable|integer|min:1',
        ]);

        $orderIndex = (int) $request->input('order_index');

        if ($orderIndex > 0) {
            Category::where('menu_type_id', $request->menu_type_id)
                ->where('order_index', '>=', $orderIndex)
                ->increment('order_index');
        } else {
            $maxOrder = Category::where('menu_type_id', $request->menu_type_id)
                ->max('order_index') ?? 0;
            $orderIndex = $maxOrder + 1;
        }

        Category::create([
            'menu_type_id' => $request->menu_type_id,
            'name'         => $request->name,
            'order_index'  => $orderIndex,
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
            'order_index'  => 'required|integer|min:1',
        ]);

        $oldMenuTypeId = $category->menu_type_id;
        $oldOrderIndex = $category->order_index;
        $newMenuTypeId = (int) $request->menu_type_id;
        $newOrderIndex = (int) $request->order_index;

        if ($newMenuTypeId === $oldMenuTypeId) {
            if ($newOrderIndex !== $oldOrderIndex) {
                if ($newOrderIndex > $oldOrderIndex) {
                    Category::where('menu_type_id', $oldMenuTypeId)
                        ->whereBetween('order_index', [$oldOrderIndex + 1, $newOrderIndex])
                        ->decrement('order_index');
                } else {
                    Category::where('menu_type_id', $oldMenuTypeId)
                        ->whereBetween('order_index', [$newOrderIndex, $oldOrderIndex - 1])
                        ->increment('order_index');
                }
            }
        } else {
            Category::where('menu_type_id', $oldMenuTypeId)
                ->where('order_index', '>', $oldOrderIndex)
                ->decrement('order_index');

            Category::where('menu_type_id', $newMenuTypeId)
                ->where('order_index', '>=', $newOrderIndex)
                ->increment('order_index');
        }

        $category->update([
            'name'         => $request->name,
            'menu_type_id' => $request->menu_type_id,
            'order_index'  => $newOrderIndex,
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
