@extends('layouts.app')

@section('title', 'Kategooriate haldus')

@section('content')
<div class="container mt-4">

    <h1 class="mb-4">Kategooriate haldus</h1>

    {{-- Teated --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Lisamisvorm --}}
    <div class="card mb-4">
        <div class="card-header">
            Lisa uus kategooria
        </div>
        <div class="card-body">
            <form action="{{ route('categories.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <label for="name" class="form-label">Nimi</label>
                    <input type="text" name="name" id="name" class="form-control"
                           value="{{ old('name') }}">
                </div>

                <div class="col-md-4">
                    <label for="menu_type_id" class="form-label">Menüü tüüp</label>
                    <select name="menu_type_id" id="menu_type_id" class="form-select">
                        @foreach($menuTypes as $menuType)
                            <option value="{{ $menuType->id }}"
                                {{ old('menu_type_id')
                                    ? (old('menu_type_id') == $menuType->id ? 'selected' : '')
                                    : ($menuType->name === 'louna' ? 'selected' : '') }}>
                                {{ $menuType->display_name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="order_index" class="form-label">Järjekord</label>
                    <input type="number" name="order_index" id="order_index" class="form-control"
                           value="{{ old('order_index') }}">
                </div>

                <div class="col-md-2 d-flex align-items-end">
                    <div class="form-check">
                        <input type="checkbox" name="is_visible" id="is_visible" value="1" class="form-check-input"
                               {{ old('is_visible', true) ? 'checked' : '' }}>
                        <label for="is_visible" class="form-check-label">Nähtav</label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        Lisa kategooria
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Nimekirjad menüü tüübi kaupa --}}
    @forelse($menuTypes as $menuType)
        @php
            $typeCategories = $categories->where('menu_type_id', $menuType->id)
                ->sortBy('order_index')
                ->values();
        @endphp

        <div class="card mb-4">
            <div class="card-header">
                {{ $menuType->display_name }}
            </div>
            <div class="card-body p-0">
                @if($typeCategories->isEmpty())
                    <p class="p-3 mb-0">Selles menüü tüübis pole veel kategooriaid.</p>
                @else
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nimi</th>
                                <th>Nähtav</th>
                                <th>Järjekord</th>
                                <th class="text-end">Tegevused</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($typeCategories as $category)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $category->name }}</td>

                                    <td>
                                        @if($category->is_visible)
                                            <span class="badge bg-success">Jah</span>
                                        @else
                                            <span class="badge bg-secondary">Ei</span>
                                        @endif
                                    </td>

                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $category->order_index }}</span>

                                            <div class="btn-group btn-group-sm" role="group">
                                            <form action="{{ route('categories.move_up', $category) }}" method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary"
                                                        {{ $loop->first ? 'disabled' : '' }}>
                                                    ↑
                                                </button>
                                            </form>

                                            <form action="{{ route('categories.move_down', $category) }}" method="POST"
                                                  class="d-inline">
                                                @csrf
                                                <button type="submit" class="btn btn-outline-secondary"
                                                        {{ $loop->last ? 'disabled' : '' }}>
                                                    ↓
                                                </button>
                                            </form>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="text-end">
                                        <a href="{{ route('categories.edit', $category) }}"
                                           class="btn btn-sm btn-warning me-2">
                                            Muuda
                                        </a>

                                        <form action="{{ route('categories.destroy', $category) }}"
                                              method="POST"
                                              class="d-inline"
                                              onsubmit="return confirm('Kustutada kategooria?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-danger">
                                                Kustuta
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    @empty
        <div class="card">
            <div class="card-body">
                Menüü tüüpe pole lisatud.
            </div>
        </div>
    @endforelse
</div>
@endsection
