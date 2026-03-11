@extends('layouts.app')

@section('title', 'Statistika')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1">Statistika</h1>
                <p class="text-muted mb-0">Ülevaade toitudest ja menüütüüpidest</p>
            </div>
        </div>

        {{-- Populaarseimad toidud --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h2 class="h5 mb-0">Populaarseimad toidud (TOP 10)</h2>
            </div>

            <div class="card-body">
                @if ($popularFoods->isEmpty())
                    <div class="alert alert-light border mb-0">
                        Andmeid pole veel.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 70px;">#</th>
                                    <th>Toit</th>
                                    <th class="text-end" style="width: 180px;">Kordi menüüs</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($popularFoods as $index => $food)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('statistics.index', ['search' => $food->display_name]) }}"
                                               class="text-decoration-none">
                                                {{ $food->display_name }}
                                            </a>
                                        </td>
                                        <td class="text-end fw-semibold">{{ $food->total }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Toidu esinemise ajalugu --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h2 class="h5 mb-0">Toidu esinemise ajalugu</h2>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('statistics.index') }}" class="row g-2 mb-4" autocomplete="off">
                    <div class="col-md-9">
                        <input
                            type="text"
                            name="search"
                            class="form-control"
                            placeholder="Sisesta toidu nimi"
                            value="{{ $search }}"
                        >
                    </div>

                    <div class="col-md-3 d-grid">
                        <button type="submit" class="btn btn-dark">
                            Otsi
                        </button>
                    </div>
                </form>

                @if ($search === '')
                    <div class="alert alert-light border mb-0">
                        Sisesta toidu nimi, et näha ajalugu.
                    </div>
                @elseif ($foodHistory->isEmpty())
                    <div class="alert alert-warning mb-0">
                        Otsingule "{{ $search }}" vasteid ei leitud.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-striped table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kuupäev</th>
                                    <th>Toit</th>
                                    <th>Menüütüüp</th>
                                    <th>Kategooria</th>
                                    <th>Hind</th>
                                    <th>Allergeenid</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($foodHistory as $item)
                                    <tr
                                        @if ($item->menu)
                                            onclick="window.location='{{ route('menus.show', $item->menu) }}'"
                                            style="cursor: pointer;"
                                        @endif
                                    >
                                        <td>{{ optional($item->menu?->date)->format('d.m.Y') }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ $item->menu?->type?->display_name ?? '-' }}</td>
                                        <td>{{ $item->category?->name ?? '-' }}</td>
                                        <td>
                                            @if (is_null($item->full_price))
                                                -
                                            @elseif ((float) $item->full_price === 0.0)
                                                Prae hinna sees
                                            @elseif (is_null($item->half_price))
                                                {{ number_format($item->full_price, 2) }} €
                                            @else
                                                {{ number_format($item->full_price, 2) }} € /
                                                {{ number_format($item->half_price, 2) }} €
                                            @endif
                                        </td>
                                        <td>
                                            @forelse ($item->allergens as $allergen)
                                                <span class="badge text-bg-secondary">
                                                    {{ $allergen->code }}
                                                </span>
                                            @empty
                                                <span class="text-muted">-</span>
                                            @endforelse
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Menüütüüpide kasutus --}}
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h2 class="h5 mb-0">Menüütüüpide kasutusstatistika</h2>
            </div>

            <div class="card-body">
                @if ($menuTypeStats->isEmpty())
                    <div class="alert alert-light border mb-0">
                        Andmeid pole veel.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Menüütüüp</th>
                                    <th class="text-end" style="width: 180px;">Kasutuskordi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($menuTypeStats as $type)
                                    <tr>
                                        <td>{{ $type->display_name }}</td>
                                        <td class="text-end fw-semibold">{{ $type->usage_count }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection