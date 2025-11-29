@extends('layouts.app')

@section('title', 'Nueva Calle - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-plus-circle"></i> Nueva Calle
                    </h1>
                    <p class="text-muted mb-0">
                        Comunidad: {{ $community->name }}
                    </p>
                </div>
                <!-- 🔥 CORRECCIÓN: Cambia communities.show por locations.communities.show -->
                <a href="{{ route('locations.communities.show', $community) }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">Información de la Calle</h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('locations.streets.store') }}" method="POST">
                        @csrf
                        
                        <!-- Campo oculto para la comunidad -->
                        <input type="hidden" name="community_id" value="{{ $community->id }}">

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre de la Calle *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" 
                                   placeholder="Ej: Calle Principal, Avenida Central" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Información adicional sobre la calle">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('locations.communities.show', $community) }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Calle
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection