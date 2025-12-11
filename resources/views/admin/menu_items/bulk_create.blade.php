@extends('layouts.app')

@section('title', 'Toidu muutmine')

@section('content')

<style>
    .food-row { border: 1px solid #ddd; padding: 12px; border-radius: 6px; margin-bottom: 10px; }
    .food-name-input { width: 100%; font-size: 1.1rem; padding: 8px; }
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

    <form action="{{ route('menus.items.bulkSave', $menu) }}" method="POST">
        @csrf

        @foreach ($categories as $category)
            <h3 class="mt-4">{{ $category->name }}</h3>

            <div id="category-{{ $category->id }}">
                
                {{-- 5 tühja rida automaatselt --}}
                @for ($i = 0; $i < 5; $i++)
                    @include('admin.menu_items.partials.food_row', [
                        'category_id' => $category->id,
                        'index' => $i,
                        'allergens' => $allergens
                    ])
                @endfor

            </div>

            {{-- Lisa rida nupp --}}
            <button type="button" 
                    class="btn btn-sm btn-secondary mt-2"
                    onclick="addRow({{ $category->id }})">
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
    fetch(`/admin/food-row-template?category_id=` + categoryId + `&index=` + rowCounter)
        .then(res => res.text())
        .then(html => {
            document.querySelector('#category-' + categoryId).insertAdjacentHTML('beforeend', html);
        });
    rowCounter++;
}


function setupAutocomplete(inputEl) {
    let suggestionsBox = document.createElement("div");
    suggestionsBox.className = "suggestions-box";
    inputEl.parentNode.appendChild(suggestionsBox);

    inputEl.addEventListener("keyup", function () {
        let term = this.value;

        if (term.length < 3) {
            suggestionsBox.innerHTML = "";
            return;
        }

        fetch("/admin/item-search?term=" + term)
            .then(res => res.json())
            .then(data => {
                suggestionsBox.innerHTML = "";
                data.forEach(item => {
                    let div = document.createElement("div");
                    div.className = "suggestion-item";
                    div.textContent = item.name;
                    div.onclick = () => {
                        inputEl.value = item.name;
                        suggestionsBox.innerHTML = "";
                    };
                    suggestionsBox.appendChild(div);
                });
            });
    });
}

document.querySelectorAll(".food-name-input").forEach(setupAutocomplete);
</script>

@endsection