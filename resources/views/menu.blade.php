@extends('layouts.app')

@section('title', 'Bistroo menüü')

@section('content')


<div class="container-fluid">

    {{-- FIXED KUUPÄEV --}}
    <div class="text-center">
        <h4>{{ now()->format('d.m.Y') }}</h4>
    </div>

    {{-- PÄIS (1-3 rida) --}}
    @if($menu && ($menu->header_line1 || $menu->header_line2 || $menu->header_line3))
    <div class="text-center mb-3">

        @if($menu->header_line2 && $menu->header_line3)
        <h3 class="text-success">{{ Str::upper($menu->header_line2) }}</h3>
        <h3 class="text-success">{{ Str::upper($menu->header_line3) }}</h3>
        @endif

        @if($menu->header_line1)
        <h3 class="text-info">{{ Str::upper($menu->header_line1) }}</h3>
        @endif
    </div>
    @endif

    {{-- KATEGOORIAD JA TOIDUD --}}
    @forelse($categories as $category)

    <table class="table table-sm w-100 mb-3">
        {{-- KATEGOORIA PÄIS --}}
        <tr class="d-flex">
            <td class="col-8 py-2 ps-2 fw-bold {{ Str::lower($category->name) === 'koolilõuna' ? 'text-danger' : '' }}">
                {{ Str::upper($category->name) }}
            </td>
            <td class="col-4"></td>
        </tr>

        {{-- TOIDUD --}}
        @foreach($category->menuItems as $item)
        <tr class="d-flex  ">

            <td class="col-8 py-2 ps-2 {{ $item->is_available ? '' : 'blur-item' }}">
                {{ Str::upper($item->name) }}

                @if($item->gluten_free)
                <span class="badge bg-secondary">G</span>
                @endif
                @if($item->lactose_free)
                <span class="badge bg-secondary">L</span>
                @endif
            </td>

            <td class="col-4 py-2 text-end pe-2">
                @if($item->included_in_main)
                Prae hinna sees
                @elseif($item->half_price)
                {{ $item->full_price }} / {{ $item->half_price }}
                @else
                {{ $item->full_price }}
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

</div>
@endsection