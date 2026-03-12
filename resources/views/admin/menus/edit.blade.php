@extends('layouts.app')

@section('title', 'Muuda menüüd')

@section('content')
    <div class="container mt-4">

        <h1>Muuda menüüd</h1>
        <form method="POST" action="{{ route('menus.update', $menu) }}">
            @csrf
            @method('PUT')

            {{-- Kuupäev --}}
            <div class="mb-3">
                <label class="form-label">Kuupäev</label>
                <input type="date" name="date" class="form-control"
                    value="{{ old('date', $menu->date->format('Y-m-d')) }}" readonly>
            </div>

            {{-- Menüü tüüp --}}
            <div class="mb-3">
                <label class="form-label">Menüü tüüp</label>
                <select name="menu_type_id" class="form-control">
                    @foreach ($menuTypes as $type)
                        <option value="{{ $type->id }}" @selected($type->id == $menu->menu_type_id)>
                            {{ $type->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Päiserida 1 --}}
            <div class="mb-3">
                <label class="form-label">Päise rida 1</label>
                <input type="text" name="header_line1" class="form-control"
                    value="{{ old('header_line1', $menu->header_line1) }}">
            </div>

            {{-- Päiserida 2 --}}
            <div class="mb-3">
                <label class="form-label">Päise rida 2</label>
                <input type="text" name="header_line2" class="form-control"
                    value="{{ old('header_line2', $menu->header_line2) }}">
            </div>

            {{-- Päiserida 3 --}}
            <div class="mb-3">
                <label class="form-label">Päise rida 3</label>
                <input type="text" name="header_line3" class="form-control"
                    value="{{ old('header_line3', $menu->header_line3) }}">
            </div>

            <!-- Nähtavus -->
            <div class="mb-3 form-check">
                <input type="hidden" name="is_visible" value="0">

                <input type="checkbox" id="is_visible" name="is_visible" class="form-check-input" value="1"
                    {{ old('is_visible', $menu->is_visible) ? 'checked' : '' }}>
                <label for="is_visible" class="form-check-label">Tee kohe nähtavaks</label>
            </div>

            <button class="btn btn-primary">Salvesta</button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Katkesta</a>
        </form>

    </div>
@endsection
