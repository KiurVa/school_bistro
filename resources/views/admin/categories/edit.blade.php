@extends('layouts.app')

@section('title', 'Muuda kategooriat')

@section('content')
<div class="container mt-4">

    <h2>Muuda kategooriat</h2>

    <form action="{{ route('categories.update', $category) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Nimi --}}
        <div class="mb-3">
            <label class="form-label">Nimi</label>
            <input type="text" name="name" class="form-control" 
                   value="{{ $category->name }}" required>
        </div>

        {{-- Menüü tüüp --}}
        <div class="mb-3">
            <label class="form-label">Menüü tüüp</label>
            <select name="menu_type_id" class="form-select" required>
                @foreach($menuTypes as $menuType)
                    <option value="{{ $menuType->id }}"
                        {{ $category->menu_type_id == $menuType->id ? 'selected' : '' }}>
                        {{ $menuType->display_name }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Järjekord --}}
        <div class="mb-3">
            <label class="form-label">Järjekord</label>
            <input type="number" name="order_index" class="form-control"
                   value="{{ old('order_index', $category->order_index) }}" required>
        </div>

        {{-- Nähtav --}}
        <div class="mb-3 form-check">
            <input class="form-check-input" type="checkbox" name="is_visible"
                   id="is_visible"
                   {{ $category->is_visible ? 'checked' : '' }}>
            <label class="form-check-label" for="is_visible">Nähtav</label>
        </div>

        <button class="btn btn-success">Uuenda</button>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary">Tagasi</a>
    </form>

</div>
@endsection
