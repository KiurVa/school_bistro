@extends('layouts.app')

@section('title', 'Toidu lisamine')

@section('content')

    <div class="container mt-4">

        <h1 class="mb-3">Toidu lisamine</h1>

        <form id="createForm" action="{{ route('items.store', $menu) }}" method="POST">
            @csrf

            {{-- CATEGORY --}}
            <div class="mb-3">
                <label class="form-label">Kategooria</label>
                <select name="category_id" class="form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- NAME --}}
            <div class="mb-3" style="position: relative;">
                <label class="form-label">Toidu nimi</label>
                <input type="text" id="nameInput" name="name" class="form-control" autocomplete="off" value="{{ old('name') }}" required>
            </div>

            {{-- PRICES --}}
            <div class="row">
                <div class="col-md-2 mb-3">
                    <label class="form-label">Täishind (€)</label>
                    <input type="text" inputmode="decimal" step="0.01" name="full_price" class="form-control"
                        value="{{ old('full_price') }}">
                </div>

                <div class="col-md-2 mb-3">
                    <label class="form-label">Poolhind (€)</label>
                    <input type="text" inputmode="decimal" step="0.01" name="half_price" class="form-control"
                        value="{{ old('half_price') }}">
                </div>
            </div>

            {{-- IS AVAILABLE --}}
            <div class="form-check mb-3">
                <input type="hidden" name="is_available" value="0">
                <input type="checkbox" name="is_available" class="form-check-input" value="1"
                    {{ old('is_available', 1) ? 'checked' : '' }}>
                <label class="form-check-label">Saadaval</label>
            </div>

            {{-- ORDER INDEX --}}
            <div class="mb-3">
                <label class="form-label">Kuva järjekord</label>
                <input type="number" name="order_index" class="form-control" value="{{ old('order_index', 0) }}">
            </div>

            {{-- ALLERGENS --}}
            <div class="mb-3">
                <label class="form-label">Allergeenid</label>

                <div class="d-flex flex-wrap gap-2">
                    @foreach ($allergens as $allergen)
                        <div class="form-check">
                            <input type="checkbox" name="allergens[]" value="{{ $allergen->id }}" class="form-check-input"
                                {{ in_array($allergen->id, old('allergens', [])) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                {{ $allergen->code }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

        </form>

        {{-- BUTTONS --}}
        <div class="d-flex gap-2 mt-3">
            <button type="submit" form="createForm" class="btn btn-primary">
                Salvesta
            </button>

            <a href="{{ route('menus.show', $menu) }}" class="btn btn-secondary">
                Tagasi
            </a>
        </div>

    </div>


<style>
    .suggestions-box { position: absolute; background: white; border: 1px solid #ccc; z-index: 9999; width: 100%; }
    .suggestion-item { padding: 6px 8px; cursor: pointer; }
    .suggestion-item:hover { background: #f0f0f0; }
</style>

<script>
    function setupAutocomplete(inputEl) {
        let box = document.createElement("div");
        box.className = "suggestions-box";
        inputEl.parentNode.appendChild(box);

        inputEl.addEventListener("keyup", function () {
            let term = this.value.trim();
            if (term.length < 3) { box.innerHTML = ""; return; }

            fetch(`/menu-item-search?term=` + encodeURIComponent(term))
                .then(res => res.json())
                .then(data => {
                    box.innerHTML = "";
                    const cap = v => v ? v.charAt(0).toUpperCase() + v.slice(1) : v;
                    data.forEach(item => {
                        let div = document.createElement("div");
                        div.className = "suggestion-item";
                        div.textContent = cap(item.name);
                        div.onclick = () => { inputEl.value = cap(item.name); box.innerHTML = ""; };
                        box.appendChild(div);
                    });
                });
        });

        inputEl.addEventListener("blur", () => setTimeout(() => box.innerHTML = "", 100));
    }

    setupAutocomplete(document.getElementById('nameInput'));
</script>

@endsection
