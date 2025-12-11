@extends('layouts.app')

@section('title', 'Muuda menüüd')

@section('content')
    <div class="container mt-4">

        <h1>Muuda menüüd</h1>

        <form method="POST" action="{{ route('menus.update', $menu) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label class="form-label">Kuupäev</label>
                <input type="date" name="date" class="form-control" value="{{ old('date', $menu->date->format('Y-m-d')) }}">
            </div>

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

            <button class="btn btn-success">Salvesta</button>
            <a href="{{ route('menus.index') }}" class="btn btn-secondary">Katkesta</a>
        </form>

    </div>
@endsection
