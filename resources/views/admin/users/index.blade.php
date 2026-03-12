@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-3">Kasutajate haldus</h1>

    {{-- Uue kasutaja lisamine --}}
    <div class="card mb-4">
        <div class="card-header">
            Lisa uus kasutaja
        </div>
        <div class="card-body">
            <form action="{{ route('admin.users.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Nimi</label>
                    <input type="text" name="name" class="form-control"
                           value="{{ old('name') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">E-post</label>
                    <input type="email" name="email" class="form-control"
                           value="{{ old('email') }}">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Parool</label>
                    <input type="password" name="password" class="form-control">
                    <small class="text-muted">Min 6 märki</small>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Parool (kordus)</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div class="col-md-4 d-flex align-items-center">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="checkbox" name="is_admin" id="is_admin_new"
                               value="1" {{ old('is_admin') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_admin_new">
                            Admin
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active_new"
                               value="1" {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active_new">
                            Aktiivne
                        </label>
                    </div>
                </div>

                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        Lisa kasutaja
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Olemasolevad kasutajad --}}
    <div class="card">
        <div class="card-header">
            Olemasolevad kasutajad
        </div>
        <div class="card-body p-0">
            @if($users->isEmpty())
                <p class="p-3 mb-0">Kasutajaid pole.</p>
            @else
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nimi</th>
                            <th>E-post</th>
                            <th>Roll</th>
                            <th>Staatus</th>
                            <th class="text-end">Tegevused</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>
                                    @if($user->is_admin)
                                        <span class="badge bg-primary">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">Kasutaja</span>
                                    @endif
                                </td>
                                <td>
                                    @if($user->is_active)
                                        <span class="badge bg-success">Aktiivne</span>
                                    @else
                                        <span class="badge bg-danger">Mitteaktiivne</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.users.edit', $user) }}"
                                       class="btn btn-sm btn-warning me-2">
                                        Muuda
                                    </a>

                                    <form action="{{ route('admin.users.destroy', $user) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Kas oled kindel, et soovid selle kasutaja kustutada?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                                type="submit"
                                                {{ auth()->id() === $user->id ? 'disabled' : '' }}>
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
    </div>
</div>
@endsection
