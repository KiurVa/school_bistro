@extends('layouts.app')

@section('content')
    <div class="container mt-4">

        <h1 class="mb-4">Muuda allergeeni</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('allergens.update', $allergen) }}" method="POST" class="card p-4">
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

            <div class="d-flex justify-content-between">
                <a href="{{ route('allergens.index') }}" class="btn btn-secondary">
                    Tagasi
                </a>

                <button type="submit" class="btn btn-primary">
                    Salvesta muudatused
                </button>
            </div>

        </form>
    </div>
@endsection
