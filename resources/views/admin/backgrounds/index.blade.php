@extends('layouts.app')

@section('content')
<div class="container mt-4">
    <h1 class="mb-3">Taustapiltide haldus</h1>

    {{-- Uue pildi üleslaadimine --}}
    <div class="card mb-4">
        <div class="card-header">
            Lisa uus taustapilt
        </div>
        <div class="card-body">
            <form action="{{ route('backgrounds.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
                @csrf

                <div class="col-md-6">
                    <label class="form-label">Pilt (JPG/PNG)</label>
                    <input type="file" name="image" class="form-control" accept="image/*" required>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <div class="form-check mt-4">
                        <input type="checkbox" name="is_active" id="is_active" value="1" class="form-check-input">
                        <label for="is_active" class="form-check-label">Tee kohe aktiivseks</label>
                    </div>
                </div>

                <div class="col-md-3 d-flex align-items-center">
                    <button type="submit" class="btn btn-primary mt-4 w-100">
                        Lae üles
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Olemasolevad taustapildid --}}
    <div class="card">
        <div class="card-header">
            Olemasolevad taustapildid
        </div>
        <div class="card-body p-0">
            @if($backgrounds->isEmpty())
                <p class="p-3 mb-0">Ühtegi taustapilti pole veel lisatud.</p>
            @else
                <table class="table mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Eelvaade</th>
                            <th>Staatus</th>
                            <th>Lisatud</th>
                            <th class="text-end">Tegevused</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backgrounds as $background)
                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td style="width: 200px;">
                                    <img src="{{ asset('storage/' . $background->file_path) }}"
                                         alt="Taustapilt"
                                         class="img-fluid rounded"
                                         style="max-height: 120px;">
                                </td>

                                {{-- STAATUS: klikitav badge, mis lülitab aktiivsust --}}
                                <td>
                                    <form action="{{ route('backgrounds.activate', $background) }}"
                                          method="POST"
                                          class="d-inline">
                                        @csrf
                                        <button type="submit"
                                                class="badge border-0 {{ $background->is_active ? 'bg-success' : 'bg-secondary' }}"
                                                style="cursor: pointer; font-size: 0.9rem;">
                                            {{ $background->is_active ? 'Aktiivne' : 'Mitteaktiivne' }}
                                        </button>
                                    </form>
                                </td>

                                <td>
                                    {{ $background->created_at?->format('d.m.Y H:i') }}
                                </td>

                                <td class="text-end">
                                    {{-- Kustutamine --}}
                                    <form action="{{ route('backgrounds.destroy', $background) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Kas oled kindel, et soovid selle pildi kustutada?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">
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
