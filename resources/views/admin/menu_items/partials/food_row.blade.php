@php
    $item = $item ?? null;
    $oldAllergens = old("items.$category_id.$index.allergens");
    $selectedAllergens = is_array($oldAllergens) ? $oldAllergens : ($item ? $item->allergens->pluck('id')->all() : []);
@endphp

<div class="food-row">
    @if ($item)
        <input type="hidden" name="items[{{ $category_id }}][{{ $index }}][id]" value="{{ $item->id }}">
    @endif
    <div class="position-relative mb-1">
        <input type="text" name="items[{{ $category_id }}][{{ $index }}][name]" class="food-name-input"
            placeholder="Toidu nimi..." autocomplete="off"
            value="{{ old("items.$category_id.$index.name", $item->name ?? '') }}">
    </div>

    <div class="row">
        <div class="col-md-2">
            <label>Täishind</label>
            <input type="number" step="0.1" class="form-control"
                name="items[{{ $category_id }}][{{ $index }}][full_price]"
                value="{{ old("items.$category_id.$index.full_price", $item->full_price ?? '') }}">
        </div>

        <div class="col-md-2">
            <label>Poolhind</label>
            <input type="number" step="0.1" class="form-control"
                name="items[{{ $category_id }}][{{ $index }}][half_price]"
                value="{{ old("items.$category_id.$index.half_price", $item->half_price ?? '') }}">
        </div>

        <div class="col-md-4 d-flex align-items-center pt-3 gap-3">
            <div class="form-check">
                <input type="checkbox" name="items[{{ $category_id }}][{{ $index }}][is_available]"
                    class="form-check-input"
                    {{ old("items.$category_id.$index.is_available", $item?->is_available ?? true) ? 'checked' : '' }}>
                <label class="form-check-label">Saadaval</label>
            </div>

            @if ($item)
                <div class="form-check">
                    <input type="checkbox" name="items[{{ $category_id }}][{{ $index }}][delete]"
                        class="form-check-input" value="1">
                    <label class="form-check-label">Kustuta</label>
                </div>
            @endif
        </div>
    </div>

    {{-- Allergeenid --}}
    <div class="mt-2">
        @foreach ($allergens as $al)
            <label class="me-2">
                <input type="checkbox" name="items[{{ $category_id }}][{{ $index }}][allergens][]"
                    value="{{ $al->id }}" {{ in_array($al->id, $selectedAllergens, false) ? 'checked' : '' }}>
                {{ $al->code }}
            </label>
        @endforeach
    </div>
</div>
