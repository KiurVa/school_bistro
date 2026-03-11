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

        {{-- Päise kokkuvõtte kaardid --}}
        <div class="row g-3 mb-4">
            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Menüüsid kokku</div>
                        <div class="fs-4 fw-bold">{{ $summary['menu_count'] }}</div>
                        <div class="small text-muted mt-2">
                            Viimane menüü:
                            <span class="fw-semibold text-dark">
                                {{ $summary['latest_menu_date'] ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Erinevaid toite</div>
                        <div class="fs-4 fw-bold">{{ $summary['unique_foods_count'] }}</div>
                        <div class="small text-muted mt-2">
                            Kõige populaarsem:
                            <span class="fw-semibold text-dark">
                                {{ $summary['most_popular_food'] ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 col-xl-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body py-3">
                        <div class="text-muted small mb-1">Menüütüüpe kokku</div>
                        <div class="fs-4 fw-bold">{{ $summary['menu_type_count'] }}</div>
                        <div class="small text-muted mt-2">
                            Kõige sagedasem:
                            <span class="fw-semibold text-dark">
                                {{ $summary['most_used_menu_type'] ?? '-' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Populaarseimad toidud --}}
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-header bg-white border-0 pt-4 pb-2">
                <h2 class="h5 mb-0">Populaarseimad toidud (TOP 10)</h2>
            </div>

            <div class="card-body">
                <form method="GET" action="{{ route('statistics.index') }}" class="row g-2 mb-3">
                    <div class="col-md-4">
                        <select name="top_category" class="form-select" onchange="this.form.submit()">
                            <option value="">Kõik kategooriad</option>
                            @foreach ($topCategories as $category)
                                <option value="{{ $category->id }}"
                                    {{ (string) $topCategory === (string) $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-4">
                        <select name="top_menu_type" class="form-select" onchange="this.form.submit()">
                            <option value="">Kõik menüütüübid</option>
                            @foreach ($topMenuTypes as $menuType)
                                <option value="{{ $menuType->id }}"
                                    {{ (string) $topMenuType === (string) $menuType->id ? 'selected' : '' }}>
                                    {{ $menuType->display_name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    @if ($search)
                        <input type="hidden" name="search" value="{{ $search }}">
                    @endif
                </form>

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
    </div>
@endsection