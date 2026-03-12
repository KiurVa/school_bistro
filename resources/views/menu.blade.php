@extends('layouts.app')

@section('title', 'Bistroo menüü')

@section('hide_nav', true)

@push('styles')
    @if ($background)
        <style>
            body {
                background-image: url('{{ asset('storage/' . $background->file_path) }}');
                background-size: cover;
                background-position: center;
                background-repeat: no-repeat;
                background-attachment: fixed;
            }

            .menu-overlay {
                min-height: 100vh;
                background: rgba(255, 255, 255, 0.82);
                backdrop-filter: blur(2px);
            }
        </style>
    @endif
@endpush

@section('content')
    <div class="menu-overlay">
        <div class="container-fluid">

        {{-- KUUPÄEV --}}
        <div class="text-center">
            <h4>{{ now()->format('d.m.Y') }}</h4>
        </div>

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
            if ($count === 1) {
                if ($h1) {
                    $c1 = 'text-info';
                }
                if ($h2) {
                    $c2 = 'text-info';
                }
                if ($h3) {
                    $c3 = 'text-info';
                }
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

            @if ($category->items->isNotEmpty())
                <table class="table table-sm w-100 mb-3">
                    {{-- KATEGOORIA PÄIS --}}
                    <tr class="d-flex">
                        <td
                            class="col-8 py-2 ps-2 fw-bold {{ Str::lower($category->name) === 'koolilõuna' ? 'text-danger' : '' }}">
                            {{ Str::upper($category->name) }}
                        </td>
                        <td class="col-4"></td>
                    </tr>

                    {{-- TOIDUD --}}
                    @foreach ($category->items as $item)
                        <tr class="d-flex">

                            <td class="col-8 py-2 ps-2 {{ $item->is_available ? '' : 'blur-item' }}">
                                {{ Str::upper($item->name) }}

                                {{-- Kuvame kõik allergeenid --}}
                                @foreach ($item->allergens as $allergen)
                                    <span class="badge bg-secondary">{{ $allergen->code }}</span>
                                @endforeach
                            </td>

                            <td class="col-4 py-2 text-end pe-2 {{ $item->is_available ? '' : 'blur-item' }}">

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

                        </tr>
                    @endforeach
                </table>
            @endif

        @empty

            {{-- FALLBACK: MENÜÜD POLE --}}
            <div class="alert alert-info text-center fw-bold">
                Menüüd pole veel sisestatud.
            </div>

        @endforelse

        </div>
    </div>
@endsection