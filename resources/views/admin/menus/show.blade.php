@extends('layouts.app')

@section('title', 'Muuda menüüd')

@section('content')
    <div class="container mt-4">

        <h1> {{ $menu->type->display_name ?? '-' }}: {{ $menu->date->format('d.m.Y') }}</h1>

        {{-- PÄIS (1-3 rida) --}}
        @php
            $h1 = trim($menu->header_line1 ?? '');
            $h2 = trim($menu->header_line2 ?? '');
            $h3 = trim($menu->header_line3 ?? '');

            $filled = collect([$h1, $h2, $h3])->filter(fn($v) => $v !== '');
            $count = $filled->count();

            // Vaikimisi: roheline
            $c1 = $h1 ? 'text-success' : null;
            $c2 = $h2 ? 'text-success' : null;
            $c3 = $h3 ? 'text-success' : null;

            // Erireeglid
            if ($count === 1 && $h1) {
                // ainult header 1 -> sinine
                $c1 = 'text-info';
            }

            if ($count === 3) {
                // kõik 3 -> kolmas rida sinine
                $c3 = 'text-info';
            }
        @endphp

        @if ($menu && $count > 0)
            <div class="text-center mb-3">
                @if ($h1)
                    <h3 class="{{ $c1 }}">{{ Str::upper($h1) }}</h3>
                @endif
                @if ($h2)
                    <h3 class="{{ $c2 }}">{{ Str::upper($h2) }}</h3>
                @endif
                @if ($h3)
                    <h3 class="{{ $c3 }}">{{ Str::upper($h3) }}</h3>
                @endif
            </div>
        @endif

        {{-- KATEGOORIAD JA TOIDUD --}}
        @forelse($categories as $category)

            <table class="table table-sm w-100 mb-3">
                {{-- KATEGOORIA PÄIS --}}
                <tr class="d-flex">
                    <td
                        class="col-6 py-2 ps-2 fw-bold {{ Str::lower($category->name) === 'koolilõuna' ? 'text-danger' : '' }}">
                        {{ Str::upper($category->name) }}
                    </td>
                    <td class="col-3"></td>
                    <td class="col-2"></td>
                </tr>

                {{-- TOIDUD --}}
                @foreach ($category->items as $item)
                    <tr class="d-flex  ">

                        <td class="col-8 py-2 ps-2 {{ $item->is_available ? '' : 'blur-item' }}">
                            <a href="{{ route('items.edit', [$menu, $item]) }}" class="text-decoration-none fw-bold">
                                {{ Str::upper($item->name) }}
                            </a>

                            {{-- Kuvame kõik allergeenid --}}
                            @foreach ($item->allergens as $allergen)
                                <span class="badge bg-secondary">{{ $allergen->code }}</span>
                            @endforeach
                        </td>

                        <td class="col-2 py-2 text-end pe-2">

                            {{-- Kui täishind puudub täielikult → ära kuva midagi --}}
                            @if (is_null($item->full_price))
                                {{-- tühi --}}

                                {{-- Kui täishind on TÄPSELT 0.00 → Prae hinna sees --}}
                            @elseif ((float) $item->full_price === 0.0)
                                Prae hinna sees

                                {{-- Kui poolhind puudub → kuva ainult täishind --}}
                            @elseif (is_null($item->half_price))
                                {{ number_format($item->full_price, 2) }} €

                                {{-- Kui mõlemad hinnad olemas → kuva full / half --}}
                            @else
                                {{ number_format($item->full_price, 2) }} € /
                                {{ number_format($item->half_price, 2) }} €
                            @endif

                        </td>
                        <td class="col-1 text-end pe-2">

                            @if ($item->is_available)
                                <form action="{{ route('items.unsetAvailable', [$menu, $item]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">
                                        Saadaval
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('items.setAvailable', [$menu, $item]) }}" method="POST"
                                    class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-secondary">
                                        Otsas
                                    </button>
                                </form>
                            @endif

                        </td>

                    </tr>
                @endforeach
            </table>

        @empty

            {{-- FALLBACK: MENÜÜD POLE --}}
            <div class="alert alert-info text-center fw-bold">
                Menüüd pole veel sisestatud.
            </div>

        @endforelse

        <a href="{{ route('items.create', $menu) }}" class="btn btn-primary">Lisa toit</a>
        <a href="{{ route('menus.items.bulk', $menu) }}" class="btn btn-primary">Lisa/Muuda kõiki toite</a>
        <a href="{{ route('menus.edit', $menu) }}" class="btn btn-warning">Muuda</a>
        <a href="{{ route('menus.index') }}" class="btn btn-secondary">Tagasi</a>

    </div>
@endsection
