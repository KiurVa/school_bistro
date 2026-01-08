<div class="food-row">
    <div class="position-relative mb-2">
        <input
            type="text"
            name="items[{{ $category_id }}][{{ $index }}][name]"
            class="food-name-input"
            placeholder="Toidu nimi..."
            autocomplete="off"
        >
    </div>

    <div class="row">
        <div class="col-md-4">
            <label>Täishind</label>
            <input type="number" step="0.01"
                class="form-control"
                name="items[{{ $category_id }}][{{ $index }}][full_price]">
        </div>

        <div class="col-md-4">
            <label>Poolhind</label>
            <input type="number" step="0.01"
                class="form-control"
                name="items[{{ $category_id }}][{{ $index }}][half_price]">
        </div>

        <div class="col-md-4 d-flex align-items-center pt-3">
            <div class="form-check">
                <input type="checkbox"
                    name="items[{{ $category_id }}][{{ $index }}][is_available]"
                    class="form-check-input"
                    checked>
                <label class="form-check-label">Saadaval</label>
            </div>
        </div>
    </div>

    {{-- Allergeenid --}}
    <div class="mt-2">
        @foreach ($allergens as $al)
            <label class="me-2">
                <input type="checkbox"
                    name="items[{{ $category_id }}][{{ $index }}][allergens][]"
                    value="{{ $al->id }}">
                {{ $al->code }}
            </label>
        @endforeach
    </div>
</div>
