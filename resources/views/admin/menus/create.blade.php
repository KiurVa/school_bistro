@extends('layouts.app')

@section('title', 'Loo menüü')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    <div class="container">
        <h1>Lisa uus menüü</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Vorm menüü loomiseks -->
        <form action="{{ route('menus.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Menüü tüüp -->
            <div class="mb-3">
                <label for="menu_type_id" class="form-label">Menüü tüüp</label>
                <select name="menu_type_id" id="menu_type_id" class="form-select">
                    @foreach ($menuTypes as $type)
                        <option value="{{ $type->id }}"
                            {{ old('menu_type_id') == $type->id ? 'selected' : ($type->name == 'Lõunasöök' ? 'selected' : '') }}>
                            {{ $type->display_name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Kuupäev -->
            <div class="mb-3">
                <label for="date" class="form-label">Kuupäev</label>
                <input type="date" name="date" id="date" class="form-control"
                    value="{{ old('date', date('Y-m-d')) }}">
            </div>

            <!-- Päiseridad -->
            <div class="mb-3">
                <label for="header_line1" class="form-label">Päise rida 1</label>
                <input type="text" name="header_line1" id="header_line1" class="form-control"
                    value="{{ old('header_line1') }}">
            </div>

            <div class="mb-3">
                <label for="header_line2" class="form-label">Päise rida 2</label>
                <input type="text" name="header_line2" id="header_line2" class="form-control"
                    value="{{ old('header_line2') }}">
            </div>

            <div class="mb-3">
                <label for="header_line3" class="form-label">Päise rida 3</label>
                <input type="text" name="header_line3" id="header_line3" class="form-control"
                    value="{{ old('header_line3') }}">
            </div>

            <!-- Taustapilt -->
            <div class="mb-3">
                <label for="background_image" class="form-label">Taustapilt</label>
                <input type="file" name="background_image" id="background_image" class="form-control"
                    accept=".jpg,.jpeg,.png,.gif,.webp">
            </div>

            <!-- Nähtavus -->
            <div class="mb-3 form-check">
                <input type="hidden" name="is_visible" value="0">

                <input type="checkbox" id="is_visible" name="is_visible" class="form-check-input" value="1"
                    {{ old('is_visible', false) ? 'checked' : '' }}>
                <label for="is_visible" class="form-check-label">Tee kohe nähtavaks</label>
            </div>

            <button class="btn btn-primary">Salvesta menüü</button>
        </form>
    </div>
@endsection
