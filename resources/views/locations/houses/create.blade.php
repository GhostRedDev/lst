@extends('layouts.app')

@section('title', 'Nueva Casa - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle"></i> Nueva Casa
                    </h1>
                    <p class="text-muted mb-0">
                        Calle: {{ $street->name }} - Comunidad: {{ $street->community->name }}
                    </p>
                </div>
                <a href="{{ route('streets.show', $street) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">Información de la Casa</h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('houses.store', $street) }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="house_number" class="form-label">Número de Casa *</label>
                            <input type="text" class="form-control @error('house_number') is-invalid @enderror" 
                                   id="house_number" name="house_number" value="{{ old('house_number') }}" 
                                   placeholder="Ej: 1, 2A, 15-B, #25" required>
                            @error('house_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Puede usar números, letras o combinaciones. Ej: 1, 2A, 15-B, Casa 3
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Información adicional sobre la casa">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Información:</strong> Esta casa será creada en la calle 
                            <strong>{{ $street->name }}</strong> de la comunidad 
                            <strong>{{ $street->community->name }}</strong>.
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('streets.show', $street) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Casa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection