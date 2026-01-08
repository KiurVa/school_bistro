@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Allergeenide haldus</h1>

        {{-- Teated --}}
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Vead --}}
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        {{-- Lisamisvorm --}}
        <div class="card mb-4">
            <div class="card-header">
                Lisa uus allergeen
            </div>
            <div class="card-body">
                <form action="{{ route('allergens.store') }}" method="POST" class="row g-3">
                    @csrf

                    <div class="col-md-4">
                        <label for="name" class="form-label">Nimi</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}"
                            placeholder="nt Gluteen, Laktoos, Pähklid">
                    </div>

                    <div class="col-md-3">
                        <label for="code" class="form-label">Kood / lühend</label>
                        <input type="text" name="code" id="code" class="form-control" value="{{ old('code') }}"
                            placeholder="nt G, L, P">
                    </div>

                    <div class="col-md-2">
                        <label for="order_index" class="form-label">Järjekord</label>
                        <input type="number" name="order_index" id="order_index" class="form-control"
                            value="{{ old('order_index') }}">
                    </div>

                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            Lisa allergeen
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Nimekiri --}}
        <div class="card">
            <div class="card-header">
                Olemasolevad allergeenid
            </div>
            <div class="card-body p-0">
                @if ($allergens->isEmpty())
                    <p class="p-3 mb-0">Ühtegi allergeeni pole veel lisatud.</p>
                @else
                    <table class="table mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nimi</th>
                                <th>Kood</th>
                                <th>Järjekord</th>
                                <th class="text-end">Tegevused</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($allergens as $allergen)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $allergen->name }}</td>
                                    <td>{{ $allergen->code }}</td>

                                    {{-- Järjekord + ↑ / ↓ nupud --}}
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">{{ $allergen->order_index }}</span>

                                            <div class="btn-group btn-group-sm" role="group">
                                                {{-- ↑ ÜLES --}}
                                                <form action="{{ route('allergens.move_up', $allergen) }}" method="POST"
                                                    class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="btn btn-outline-secondary"
                                                        {{ $loop->first ? 'disabled' : '' }}>
                                                        ↑
                                                    </button>
                                                </form>

                                                {{-- ↓ ALLA --}}
                                                <form action="{{ route('allergens.move_down', $allergen) }}" method="POST"
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
                                        {{-- MUUDA nupp --}}
                                        <a href="{{ route('allergens.edit', $allergen) }}"
                                            class="btn btn-sm btn-warning me-2">
                                            Muuda
                                        </a>

                                        {{-- Kustuta nupp --}}
                                        <form action="{{ route('allergens.destroy', $allergen) }}" method="POST"
                                            class="d-inline"
                                            onsubmit="return confirm('Kas oled kindel, et soovid selle allergeeni kustutada?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Kustuta</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
    </div>
@endsection
