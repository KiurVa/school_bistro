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

        {{-- Pealkiri --}}
        <h2>
            Lisa toidud menüüsse:
            <strong>{{ $menu->display_name }}</strong>
            ({{ $menu->date->format('d.m.Y') }})
        </h2>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('menus.items.bulkSave', $menu) }}" method="POST">
            @csrf

            @foreach ($categories as $category)
                <h3 class="mt-4">{{ $category->name }}</h3>

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
                        @include('admin.menu_items.partials.food_row', [
                            'category_id' => $category->id,
                            'index' => $categoryItems->count() + $i,
                            'allergens' => $allergens,
                            'item' => null,
                        ])
                    @endfor

                </div>

                {{-- Lisa rida nupp --}}
                <button type="button" class="btn btn-sm btn-secondary mt-2" onclick="addRow({{ $category->id }})">
                    + Lisa rida
                </button>
            @endforeach

            <button class="btn btn-primary mt-4">SALVESTA KÕIK</button>

        </form>

    </div>

    {{-- JS osa --}}
    <script>
        let rowCounter = 1000; // et igal real oleks unikaalne nimi

        function addRow(categoryId) {
            fetch(`/menus/{{ $menu->id }}/items/row-template?category_id=` + categoryId + `&index=` + rowCounter)
                .then(res => res.text())
                .then(html => {
                    document.querySelector('#category-' + categoryId).insertAdjacentHTML('beforeend', html);
                });

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
    </script>

@endsection
