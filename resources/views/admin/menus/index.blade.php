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
                    <th class="text-end">Tegevused</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($menus as $menu)
                    <tr>
                        <td>{{ $menu->date->format('d.m.Y') }}</td>
                        <td>{{ $menu->type->display_name ?? '-' }}</td>
                        <td>{{ $menu->created_at->format('d.m.Y H:i') }}</td>

                        <td class="text-end">
                            <a href="{{ route('menus.show', $menu) }}" class="btn btn-sm btn-info">Vaata</a>
                            <a href="{{ route('menus.edit', $menu) }}" class="btn btn-sm btn-warning">Muuda</a>

                            <form action="{{ route('menus.destroy', $menu) }}" method="POST"
                                style="display:inline-block" onsubmit="return confirm('Kustutada?')">
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
