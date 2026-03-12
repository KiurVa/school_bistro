@extends('layouts.app')

@section('title', 'Toidu muutmine')

@section('content')

    <div class="container mt-4">

        <h1 class="mb-1">Muuda toitu</h1>
        <h5><strong>{{ $item->name }}</strong></h5>

        <form id="updateForm" action="{{ route('items.update', [$menu, $item]) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- CATEGORY --}}
            <div class="mb-3">
                <label class="form-label">Kategooria</label>
                <select name="category_id" class="form-select" required>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ $item->category_id == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- NAME --}}
            <div class="mb-3">
                <label class="form-label">Toidu nimi</label>
                <input type="text" name="name" class="form-control" value="{{ $item->name }}" required>
            </div>

            {{-- PRICES --}}
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Täishind (€)</label>
                    <input type="number" step="0.01" name="full_price" class="form-control"
                        value="{{ $item->full_price }}">
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Poolhind (€)</label>
                    <input type="number" step="0.01" name="half_price" class="form-control"
                        value="{{ $item->half_price }}">
                </div>
            </div>

            {{-- IS AVAILABLE --}}
            <div class="form-check mb-3">
                <input type="hidden" name="is_available" value="0">
                <input type="checkbox" name="is_available" class="form-check-input" value="1"
                    {{ $item->is_available ? 'checked' : '' }}>
                <label class="form-check-label">Saadaval</label>
            </div>

            {{-- ORDER INDEX --}}
            <div class="mb-3">
                <label class="form-label">Kuva järjekord</label>
                <input type="number" name="order_index" class="form-control" value="{{ $item->order_index }}">
            </div>

            {{-- ALLERGENS --}}
            <div class="mb-3">
                <label class="form-label">Allergeenid</label>

                <div class="d-flex flex-wrap gap-2">
                    @foreach ($allergens as $allergen)
                        <div class="form-check">
                            <input type="checkbox" name="allergens[]" value="{{ $allergen->id }}" class="form-check-input"
                                {{ $item->allergens->contains($allergen->id) ? 'checked' : '' }}>
                            <label class="form-check-label">
                                {{ $allergen->code }} – {{ $allergen->name }}
                            </label>
                        </div>
                    @endforeach
                </div>
            </div>

        </form>

        {{-- DELETE FORM --}}
        <form id="deleteForm" action="{{ route('items.destroy', [$menu, $item]) }}" method="POST"
            onsubmit="return confirm('Kustuta toit?')">
            @csrf
            @method('DELETE')
        </form>

        {{-- BUTTONS --}}
        <div class="d-flex gap-2 mt-3">
            <button type="submit" form="updateForm" class="btn btn-primary">
                Salvesta
            </button>

            <button type="submit" form="deleteForm" class="btn btn-danger">
                Kustuta
            </button>

            <a href="{{ route('menus.show', $menu) }}" class="btn btn-secondary">Tagasi</a>
        </div>

    </div>

@endsection
