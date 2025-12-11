<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Allergen;
use Illuminate\Http\Request;

class AllergenController extends Controller
{
    /**
     * Näita allergeenide nimekirja + lisamisvormi.
     */
    public function index()
    {
        $allergens = Allergen::orderBy('order_index')
            ->orderBy('name')
            ->get();

        return view('admin.allergens.index', compact('allergens'));
    }

    /**
     * Salvesta uus allergeen.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:allergens,name',
            'code'        => 'nullable|string|max:10',
            'order_index' => 'nullable|integer|min:1',
        ]);

        // Kui järjekorranumber on tühi -> pane lõppu
        if (empty($validated['order_index'])) {
            $maxOrder = Allergen::max('order_index') ?? 0;
            $validated['order_index'] = $maxOrder + 1;
        } else {
            // Kui kasutaja pani konkreetse koha,
            // nihutame kõik sellest suuremad allapoole, et kordusi ei tekiks
            Allergen::where('order_index', '>=', $validated['order_index'])
                ->increment('order_index');
        }

        Allergen::create($validated);

        return redirect()
            ->route('allergens.index')
            ->with('success', 'Allergeen lisatud.');
    }

    /**
     * Kustuta allergeen (koos seostega menüüdega).
     */
    public function destroy(Allergen $allergen)
    {
        $allergen->menuItems()->detach();
        $allergen->delete();

        return redirect()
            ->route('allergens.index')
            ->with('success', 'Allergeen kustutatud.');
    }

    /**
     * Liiguta allergeeni ühe võrra ülespoole.
     */
    public function moveUp(Allergen $allergen)
    {
        $prev = Allergen::where('order_index', '<', $allergen->order_index)
            ->orderBy('order_index', 'desc')
            ->first();

        if ($prev) {
            $currentOrder = $allergen->order_index;

            $allergen->update(['order_index' => $prev->order_index]);
            $prev->update(['order_index' => $currentOrder]);
        }

        return redirect()->route('allergens.index');
    }

    /**
     * Liiguta allergeeni ühe võrra allapoole.
     */
    public function moveDown(Allergen $allergen)
    {
        $next = Allergen::where('order_index', '>', $allergen->order_index)
            ->orderBy('order_index', 'asc')
            ->first();

        if ($next) {
            $currentOrder = $allergen->order_index;

            $allergen->update(['order_index' => $next->order_index]);
            $next->update(['order_index' => $currentOrder]);
        }

        return redirect()->route('allergens.index');
    }
}
