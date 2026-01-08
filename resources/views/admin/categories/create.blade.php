@extends('layouts.app')

@section('title', 'Lisa kategooria')

@section('content')
<div class="container mt-4">

    <h2>Lisa kategooria</h2>

    <form action="{{ route('categories.store') }}" method="POST">
        @csrf

        {{-- Nimi --}}
        <div class="mb-3">
            <label class="form-label">Nimi</label>
            <input type="text" name="name" class="form-control" required>
        </div>

        {{-- Järjekord --}}
        <div class="mb-3">
            <label class="form-label">Järjekord</label>
            <input type="number" name="order_index" class="form-control"
                   value="{{ old('order_index') }}">
        </div>

        {{-- Menüü tüüp --}}
        <div class="mb-3">
            <label class="form-label">Menüü tüüp</label>
            <select name="menu_type_id" class="form-select" required>
                <option value="">— Vali menüü tüüp —</option>
                @foreach($menuTypes as $menuType)
                    <option value="{{ $menuType->id }}">
                        {{ $menuType->display_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Nähtav --}}
        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="is_visible" id="is_visible" checked>
            <label class="form-check-label" for="is_visible">Nähtav</label>
        </div>

        <button class="btn btn-success">Salvesta</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Tagasi</a>
    </form>

</div>
@endsection
