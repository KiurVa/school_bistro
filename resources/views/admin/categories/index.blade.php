@extends('layouts.app')

@section('title', 'Kategooriate haldus')

@section('content')
<div class="container mt-4">

    <h2>Kategooriate haldus</h2>

    <a href="{{ route('categories.create') }}" class="btn btn-primary my-3">
        + Lisa kategooria
    </a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($categories->isEmpty())
        <p>Veel pole ühtegi kategooriat.</p>
    @else
        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Id</th>
                    <th>Nimi</th>
                    <th>Menüü tüüp</th>
                    <th>Nähtav</th>
                    <th>Järjekord</th>
                    <th>Tegevused</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categories as $category)
                    <tr>
                        {{-- Id --}}
                        <td>{{ $category->id }}</td>

                        {{-- Kategooria nimi --}}
                        <td>{{ $category->name }}</td>

                        {{-- Menüü tüüp (suhe MenuType mudeliga) --}}
                        <td>{{ $category->menuType->display_name ?? '-' }}</td>

                        {{-- Nähtav --}}
                        <td>
                            @if($category->is_visible)
                                <span class="badge bg-success">Jah</span>
                            @else
                                <span class="badge bg-secondary">Ei</span>
                            @endif
                        </td>
                        {{-- Järjekord --}}
                        <td>
                            <div class="d-flex align-items-center">
                                <span class="me-2">{{ $category->order_index }}</span>

                                <div class="btn-group btn-group-sm" role="group">
                                    {{-- ↑ ÜLES --}}
                                    <form action="{{ route('categories.move_up', $category) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-outline-secondary"
                                            {{ $loop->first ? 'disabled' : '' }}>
                                            ↑
                                        </button>
                                    </form>

                                    {{-- ↓ ALLA --}}
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


                        {{-- Tegevused: Muuda + Kustuta --}}
                        <td class="text-end">

                            {{-- Muuda nupp --}}
                            <a href="{{ route('categories.edit', $category) }}"
                               class="btn btn-sm btn-warning">
                                Muuda
                            </a>

                            {{-- Kustuta nupp --}}
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
@endsection
