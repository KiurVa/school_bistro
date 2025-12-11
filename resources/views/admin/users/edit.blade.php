@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-4">Muuda kasutajat</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="card p-4">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label class="form-label">Nimi</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $user->name) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">E-post</label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $user->email) }}">
        </div>

        <div class="mb-3">
            <label class="form-label">Uus parool (valikuline)</label>
            <input type="password" name="password" class="form-control">
        </div>

        <div class="mb-3">
            <label class="form-label">Uus parool (kordus)</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>

        <div class="mb-3">
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin"
                       value="1" {{ old('is_admin', $user->is_admin) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_admin">
                    Admin
                </label>
            </div>

            <div class="form-check form-check-inline ms-3">
                <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                       value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_active">
                    Aktiivne
                </label>
            </div>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">
                Tagasi
            </a>

            <button type="submit" class="btn btn-primary">
                Salvesta muudatused
            </button>
        </div>
    </form>
</div>
@endsection
