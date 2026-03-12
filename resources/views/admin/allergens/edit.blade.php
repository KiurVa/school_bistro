@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <h1 class="mb-3">Muuda allergeeni</h1>

        <form action="{{ route('allergens.update', $allergen) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Nimi</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $allergen->name) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Kood / lühend</label>
                <input type="text" name="code" class="form-control" value="{{ old('code', $allergen->code) }}">
            </div>

            <div class="mb-3">
                <label class="form-label">Järjekord</label>
                <input type="number" name="order_index" class="form-control"
                    value="{{ old('order_index', $allergen->order_index) }}">
            </div>
            <button class="btn btn-primary">Salvesta</button>
            <a href="{{ route('allergens.index') }}" class="btn btn-secondary">Tagasi</a>
        </form>
    </div>
@endsection
