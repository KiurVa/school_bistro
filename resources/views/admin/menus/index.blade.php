@extends('layouts.app')

@section('title', 'Menüüd')

@section('content')
    <div class="container mt-4">

        <h1 class="mb-3">Menüüd</h1>

        <a href="{{ route('menus.create') }}" class="btn btn-primary mb-3">
            + Lisa uus menüü
        </a>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Kuupäev</th>
                    <th>Tüüp</th>
                    <th>Loodud</th>
                    <th>Aktiivsus</th>
                    <th class="text-center">Tegevused</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $menu)
                    @php
                        $isTodayMenu = $menu->date->isToday();
                        $isPastMenu = $menu->date->lt(now()->startOfDay());
                    @endphp

                    <tr>
                        <td>{{ $menu->date->format('d.m.Y') }}</td>
                        <td>{{ $menu->type->display_name ?? '-' }}</td>
                        <td>{{ $menu->created_at->format('d.m.Y H:i') }}</td>

                        <td>
                            @if (!$isTodayMenu)
                                <button class="btn btn-sm btn-secondary" disabled>
                                    Mitteaktiivne
                                </button>
                            @elseif ($menu->is_visible)
                                <form action="{{ route('menus.unsetVisible', $menu) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-success">
                                        Aktiivne
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('menus.setVisible', $menu) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button class="btn btn-sm btn-outline-primary">
                                        Määra aktiivseks
                                    </button>
                                </form>
                            @endif
                        </td>

                        <td class="text-center">
                            @if ($isPastMenu)
                                <button class="btn btn-sm btn-secondary" disabled>
                                    Lisa toit
                                </button>

                                <button class="btn btn-sm btn-secondary" disabled>
                                    Lisa toidud
                                </button>

                                <button class="btn btn-sm btn-secondary" disabled>
                                    Muuda
                                </button>

                                <a href="{{ route('menus.show', $menu) }}" class="btn btn-sm btn-info">
                                    Vaata
                                </a>
                            @else
                                <a href="{{ route('items.create', $menu) }}" class="btn btn-sm btn-primary">
                                    Lisa toit
                                </a>

                                <a href="{{ route('menus.items.bulk', $menu) }}" class="btn btn-sm btn-primary">
                                    Lisa toidud
                                </a>

                                <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-warning">
                                    Muuda
                                </a>

                                <a href="{{ route('menus.show', $menu) }}" class="btn btn-sm btn-info">
                                    Vaata
                                </a>
                            @endif

                            <form action="{{ route('menus.destroy', $menu) }}" method="POST" style="display:inline-block"
                                onsubmit="return confirm('Kustutada?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Kustuta</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        {{ $menus->links() }}

    </div>
@endsection