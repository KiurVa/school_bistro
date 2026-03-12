@extends('layouts.app')

@section('title', 'Muuda menüüd')

@section('content')
    <div class="container mt-4">

        @php
            // Mineviku menüü: eilne või vanem.
            // Sellisel juhul ei tohi menüüd ega selle toite enam muuta.
            $isPastMenu = $menu->date->lt(now()->startOfDay());
        @endphp

        {{-- Menüü pealkiri: menüütüüp + kuupäev --}}
        <h1>{{ $menu->type->display_name ?? '-' }}: {{ $menu->date->format('d.m.Y') }}</h1>

        {{-- PÄIS (1–3 rida) --}}
        @php
            // Võtame menüü päiseread ja eemaldame algusest/lõpust liigsed tühikud
            $h1 = trim($menu->header_line1 ?? '');
            $h2 = trim($menu->header_line2 ?? '');
            $h3 = trim($menu->header_line3 ?? '');

            // Loeme kokku, mitu päiserida on tegelikult täidetud
            $filled = collect([$h1, $h2, $h3])->filter(fn($v) => $v !== '');
            $count = $filled->count();

            // Vaikimisi on täidetud read rohelised
            $c1 = $h1 ? 'text-success' : null;
            $c2 = $h2 ? 'text-success' : null;
            $c3 = $h3 ? 'text-success' : null;

            // Kui täidetud on ainult esimene rida, siis tee see siniseks
            if ($count === 1 && $h1) {
                $c1 = 'text-info';
            }

            // Kui kõik 3 rida on täidetud, siis kolmas rida on sinine
            if ($count === 3) {
                $c3 = 'text-info';
            }
        @endphp

        {{-- Kuvame päise ainult siis, kui vähemalt üks rida on täidetud --}}
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
        @forelse ($categories as $category)
            <table class="table table-sm w-100 mb-3">

                {{-- Kategooria päis --}}
                <tr class="d-flex">
                    <td class="col-6 py-2 ps-2 fw-bold {{ Str::lower($category->name) === 'koolilõuna' ? 'text-danger' : '' }}">
                        {{ Str::upper($category->name) }}
                    </td>
                    <td class="col-3"></td>
                    <td class="col-2"></td>
                </tr>

                {{-- Selle kategooria toidud --}}
                @foreach ($category->items as $item)
                    <tr class="d-flex">

                        {{-- Toidu nimi + allergeenid --}}
                        <td class="col-8 py-2 ps-2 {{ $item->is_available ? '' : 'blur-item' }}">
                            @if ($isPastMenu)
                                {{-- Mineviku menüü puhul ei tee toidunime klikitavaks --}}
                                <span class="fw-bold">
                                    {{ Str::upper($item->name) }}
                                </span>
                            @else
                                {{-- Tänase ja tuleviku menüü puhul saab toitu muuta --}}
                                <a href="{{ route('items.edit', [$menu, $item]) }}" class="text-decoration-none fw-bold">
                                    {{ Str::upper($item->name) }}
                                </a>
                            @endif

                            {{-- Kuvame kõik seotud allergeenid badge'idena --}}
                            @foreach ($item->allergens as $allergen)
                                <span class="badge bg-secondary">{{ $allergen->code }}</span>
                            @endforeach
                        </td>

                        {{-- Hinnalahter --}}
                        <td class="col-2 py-2 text-end pe-2">
                            @if (is_null($item->full_price))
                                {{-- Kui täishind puudub täielikult, ära kuva midagi --}}
                            @elseif ((float) $item->full_price === 0.0)
                                {{-- Kui täishind on 0.00, siis kuva eritekst --}}
                                Prae hinna sees
                            @elseif (is_null($item->half_price))
                                {{-- Kui poolhinda pole, kuva ainult täishind --}}
                                {{ number_format($item->full_price, 2) }} €
                            @else
                                {{-- Kui mõlemad hinnad olemas, kuva täishind / poolhind --}}
                                {{ number_format($item->full_price, 2) }} €
                                /
                                {{ number_format($item->half_price, 2) }} €
                            @endif
                        </td>

                        {{-- Saadavuse nupp --}}
                        <td class="col-1 text-end pe-2">
                            @if ($isPastMenu)
                                {{-- Mineviku menüü puhul ei saa saadavust muuta --}}
                                @if ($item->is_available)
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        Saadaval
                                    </button>
                                @else
                                    <button class="btn btn-sm btn-secondary" disabled>
                                        Otsas
                                    </button>
                                @endif
                            @else
                                {{-- Tänase ja tuleviku menüü puhul saab saadavust muuta --}}
                                @if ($item->is_available)
                                    <form action="{{ route('items.unsetAvailable', [$menu, $item]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-success">
                                            Saadaval
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('items.setAvailable', [$menu, $item]) }}" method="POST" class="d-inline">
                                        @csrf
                                        <button class="btn btn-sm btn-outline-secondary">
                                            Otsas
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>

                    </tr>
                @endforeach
            </table>

        @empty
            {{-- Kui kategooriaid/toite pole, näita teadet --}}
            <div class="alert alert-info text-center fw-bold">
                Menüüd pole veel sisestatud.
            </div>
        @endforelse

        {{-- Alumised tegevusnupud --}}
        @if ($isPastMenu)
            {{-- Mineviku menüü puhul muutmine pole lubatud --}}
            <button class="btn btn-secondary" disabled>Lisa toit</button>
            <button class="btn btn-secondary" disabled>Lisa/Muuda kõiki toite</button>
            <button class="btn btn-secondary" disabled>Muuda</button>
        @else
            {{-- Tänase ja tuleviku menüü puhul on muutmine lubatud --}}
            <a href="{{ route('items.create', $menu) }}" class="btn btn-primary">Lisa toit</a>
            <a href="{{ route('menus.items.bulk', $menu) }}" class="btn btn-primary">Lisa/Muuda kõiki toite</a>
            <a href="{{ route('menus.edit', $menu) }}" class="btn btn-warning">Muuda</a>
        @endif

        {{-- Tagasi nupp on alati nähtav --}}
        <a href="{{ route('menus.index') }}" class="btn btn-secondary">Tagasi</a>

    </div>
@endsection