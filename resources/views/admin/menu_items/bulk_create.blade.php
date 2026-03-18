@extends('layouts.app')

@section('title', 'Toidu muutmine')

@section('content')

<style>
    .food-row {
        border: 1px solid #ddd;
        padding: 12px;
        border-radius: 6px;
        margin-bottom: 10px;
    }

    .food-name-input {
        width: 100%;
        font-size: 1.1rem;
        padding: 8px;
    }

    .suggestions-box {
        position: absolute;
        background: white;
        border: 1px solid #ccc;
        z-index: 9999;
        width: 100%;
    }

    .suggestion-item {
        padding: 6px 8px;
        cursor: pointer;
    }

    .suggestion-item:hover {
        background: #f0f0f0;
    }
</style>

<div class="container">

    <div class="d-flex justify-content-between align-items-center mb-3">

        {{-- Pealkiri --}}
        <h2 class="mb-0">
            Lisa toidud menüüsse:
            <strong>{{ $menu->display_name }}</strong>
            ({{ $menu->date->format('d.m.Y') }})
        </h2>

        {{-- Nupud --}}
        <div class="d-flex gap-2">
            <button type="submit" form="bulkForm" class="btn btn-primary">
                Salvesta
            </button>

            <a href="{{ route('menus.show', $menu) }}" class="btn btn-secondary">
                Katkesta
            </a>
        </div>

    </div>

    <form id="bulkForm" action="{{ route('menus.items.bulkSave', $menu) }}" method="POST">
        @csrf

        @foreach ($categories as $category)
        <h5 class="mt-2">{{ $category->name }}</h5>

        <div id="category-{{ $category->id }}">

            @php
            $categoryItems = $itemsByCategory->get($category->id, collect())->values();
            $rowCount = Str::slug($category->name) === 'koolilouna' ? 1 : 3;
            $extraRows = max($rowCount - $categoryItems->count(), 0);
            @endphp

            {{-- Olemasolevad read --}}
            @foreach ($categoryItems as $itemIndex => $item)
            @include('admin.menu_items.partials.food_row', [
            'category_id' => $category->id,
            'index' => $itemIndex,
            'allergens' => $allergens,
            'item' => $item,
            ])
            @endforeach

            {{-- Tühjad read --}}
            @for ($i = 0; $i < $extraRows; $i++)
                @include('admin.menu_items.partials.food_row', [ 'category_id'=> $category->id,
                'index' => $categoryItems->count() + $i,
                'allergens' => $allergens,
                'item' => null,
                ])
                @endfor

        </div>

        {{-- Lisa rida nupp --}}
        <button type="button" class="btn btn-sm btn-secondary mt-2 mb-2" onclick="addRow({{ $category->id }})">
            + Lisa rida
        </button>
        @endforeach

        <div class="d-flex justify-content-end gap-2 mt-2 mb-2">
            <button class="btn btn-primary">Salvesta</button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Katkesta</a>
        </div>

    </form>

</div>

{{-- Peidetud template uute ridade kloonimiseks --}}
<template id="row-template">
<div class="food-row">
    <div class="position-relative mb-1">
        <input type="text" name="items[__CAT__][__IDX__][name]" class="food-name-input"
            placeholder="Toidu nimi..." autocomplete="off" value="">
    </div>
    <div class="row">
        <div class="col-md-2">
            <label>Täishind</label>
            <input type="text" inputmode="decimal" step="0.01" class="form-control"
                name="items[__CAT__][__IDX__][full_price]" value="">
        </div>
        <div class="col-md-2">
            <label>Poolhind</label>
            <input type="text" inputmode="decimal" step="0.01" class="form-control"
                name="items[__CAT__][__IDX__][half_price]" value="">
        </div>
        <div class="col-md-4 d-flex align-items-center pt-3 gap-3">
            <div class="form-check">
                <input type="checkbox" name="items[__CAT__][__IDX__][is_available]"
                    class="form-check-input" checked>
                <label class="form-check-label">Saadaval</label>
            </div>
        </div>
    </div>
    <div class="mt-2">
        @foreach ($allergens as $al)
            <label class="me-2">
                <input type="checkbox" name="items[__CAT__][__IDX__][allergens][]" value="{{ $al->id }}">
                {{ $al->code }}
            </label>
        @endforeach
    </div>
</div>
</template>

{{-- JS osa --}}
<script>
    let rowCounter = 1000; // et igal real oleks unikaalne nimi

    function addRow(categoryId) {
        const html = document.getElementById('row-template').innerHTML
            .replaceAll('__CAT__', categoryId)
            .replaceAll('__IDX__', rowCounter);

        const container = document.querySelector('#category-' + categoryId);
        container.insertAdjacentHTML('beforeend', html);

        const newInput = container.querySelector('.food-row:last-child .food-name-input');
        setupAutocomplete(newInput);

        rowCounter++;
    }


    function setupAutocomplete(inputEl) {

        let box = document.createElement("div");
        box.className = "suggestions-box";
        inputEl.parentNode.appendChild(box);

        inputEl.addEventListener("keyup", function() {
            let term = this.value.trim();

            if (term.length < 3) {
                box.innerHTML = "";
                return;
            }

            fetch(`/menu-item-search?term=` + encodeURIComponent(term))
                .then(res => res.json())
                .then(data => {
                    box.innerHTML = "";

                    if (!data.length) {
                        return;
                    }

                    const capitalizeFirst = (value) => {
                        if (!value) return value;
                        return value.charAt(0).toUpperCase() + value.slice(1);
                    };

                    data.forEach(item => {
                        let div = document.createElement("div");
                        div.className = "suggestion-item";
                        div.textContent = capitalizeFirst(item.name);

                        div.onclick = () => {
                            inputEl.value = capitalizeFirst(item.name);
                            box.innerHTML = "";
                        };

                        box.appendChild(div);
                    });
                });
        });

        inputEl.addEventListener("blur", function() {
            setTimeout(() => {
                box.innerHTML = "";
            }, 100);
        });
    }


    document.querySelectorAll(".food-name-input").forEach(setupAutocomplete);
    document.getElementById('bulkForm').addEventListener('submit', function(e) {
        // Eemalda eelnevad veaäärised
        document.querySelectorAll('.food-row.is-invalid-row').forEach(row => {
            row.classList.remove('is-invalid-row');
            row.querySelector('.name-error')?.remove();
        });

        let hasErrors = false;

        document.querySelectorAll('.food-row').forEach(row => {
            const idInput = row.querySelector('input[type="hidden"][name*="[id]"]');
            const nameInput = row.querySelector('.food-name-input');
            const deleteBox = row.querySelector('input[type="checkbox"][name*="[delete]"]');

            // Ainult olemasolevad kirjed (kus on id) ja delete pole märgitud
            if (idInput && nameInput && nameInput.value.trim() === '') {
                if (!deleteBox || !deleteBox.checked) {
                    hasErrors = true;
                    row.classList.add('is-invalid-row');

                    const msg = document.createElement('div');
                    msg.className = 'text-danger small mt-1 name-error';
                    msg.textContent = 'Nimi on kohustuslik. Kustutamiseks märgi "Kustuta".';
                    nameInput.insertAdjacentElement('afterend', msg);
                }
            }
        });

        if (hasErrors) {
            e.preventDefault();
            // Kerib esimese veani
            document.querySelector('.is-invalid-row')?.scrollIntoView({
                behavior: 'smooth',
                block: 'center'
            });
        }
    });
</script>

@endsection
