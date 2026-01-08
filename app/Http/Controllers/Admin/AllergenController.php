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
        $allergen->items()->detach();

        $orderIndex = $allergen->order_index;
        $allergen->delete();

        Allergen::where('order_index', '>', $orderIndex)
            ->decrement('order_index');

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
    /**
     * Näita allergeeni muutmise vormi.
     */
    public function edit(Allergen $allergen)
    {
        return view('admin.allergens.edit', compact('allergen'));
    }

    /**
     * Uuenda allergeeni andmeid.
     */
    public function update(Request $request, Allergen $allergen)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:allergens,name,' . $allergen->id,
            'code'        => 'nullable|string|max:10',
            'order_index' => 'required|integer|min:1',
        ]);

        // Kui järjekorda muudeti, peame korrigeerima teisi
        if ($validated['order_index'] != $allergen->order_index) {

            if ($validated['order_index'] > $allergen->order_index) {
                // liikumas alla → vähenda vahepealsete order_index väärtusi
                Allergen::whereBetween('order_index', [
                    $allergen->order_index + 1,
                    $validated['order_index']
                ])->decrement('order_index');
            } else {
                // liikumas üles → suurenda vahepealsete order_index väärtusi
                Allergen::whereBetween('order_index', [
                    $validated['order_index'],
                    $allergen->order_index - 1
                ])->increment('order_index');
            }
        }

        $allergen->update($validated);

        return redirect()
            ->route('allergens.index')
            ->with('success', 'Allergeen uuendatud.');
    }
}
