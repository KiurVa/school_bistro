@extends('layouts.app')

@section('title', 'Toidu muutmine')

@section('content')

    <div class="container">

        <h3>Muuda toitu: <strong>{{ $item->name }}</strong></h3>

        <form action="{{ route('items.update', [$menu, $item]) }}" method="POST">
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
                <input type="checkbox" name="is_available" class="form-check-input"
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

            <button type="submit" class="btn btn-primary">Uuenda</button>

        </form>

        <form action="{{ route('items.destroy', [$menu, $item]) }}" method="POST" class="mt-3"
            onsubmit="return confirm('Kustuta toit?')">
            @csrf @method('DELETE')
            <button class="btn btn-danger">Kustuta</button>
        </form>

    </div>

@endsection
