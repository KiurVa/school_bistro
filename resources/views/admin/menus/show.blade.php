@extends('layouts.app')

@section('title', 'Muuda menüüd')

@section('content')
    <div class="container mt-4">

    <h1> {{ $menu->type->display_name ?? '-' }}: {{ $menu->date->format('d.m.Y') }}</h1>

    <a href="{{ route('menus.edit', $menu) }}" class="btn btn-warning">Muuda</a>
    <a href="{{ route('menus.index') }}" class="btn btn-secondary">Tagasi</a>

</div>
@endsection