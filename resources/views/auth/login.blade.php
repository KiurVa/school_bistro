@extends('layouts.app')

@section('title', 'Logi sisse')

@section('hide_nav', true)




@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-4">

        <div class="card shadow-sm">
            <div class="card-body">

                <h3 class="text-center mb-4">Logi sisse</h3>

                {{-- Veateated --}}
                @if ($errors->any())
                    <div class="alert alert-danger">
                        Vale e-post või parool.
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    {{-- Email --}}
                    <div class="mb-3">
                        <label for="email" class="form-label">E-post</label>
                        <input id="email" type="email" 
                               class="form-control @error('email') is-invalid @enderror"
                               name="email" value="{{ old('email') }}" required autofocus>
                    </div>

                    {{-- Password --}}
                    <div class="mb-3">
                        <label for="password" class="form-label">Parool</label>
                        <input id="password" type="password" 
                               class="form-control @error('password') is-invalid @enderror"
                               name="password" required>
                    </div>

                    {{-- Remember Me --}}
                    <div class="mb-3 form-check">
                        <input type="checkbox" class="form-check-input" 
                               id="remember" name="remember">
                        <label class="form-check-label" for="remember">Jäta mind meelde</label>
                    </div>

                    {{-- Submit --}}
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            Logi sisse
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>
</div>
@endsection
