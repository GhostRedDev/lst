@extends('layouts.app')

@section('title', 'Nueva Comunidad - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-plus-circle"></i> Nueva Comunidad
                </h1>
                <a href="{{ route('locations.communities.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">Información de la Comunidad</h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('locations.communities.store') }}" method="POST">
                        @csrf
                        
                        <!-- ESTE CAMPO ES OBLIGATORIO -->
                        <div class="mb-3">
                            <label for="health_center_id" class="form-label">Centro de Salud *</label>
                            <select class="form-control @error('health_center_id') is-invalid @enderror" 
                                    id="health_center_id" name="health_center_id" required>
                                <option value="">Seleccione un centro de salud</option>
                                @foreach($health_centers as $health_center)
                                    <option value="{{ $health_center->id }}" 
                                        {{ old('health_center_id') == $health_center->id ? 'selected' : '' }}>
                                        {{ $health_center->name }} - {{ $health_center->municipality->name }}, {{ $health_center->municipality->state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('health_center_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Nombre de la Comunidad *</label>
                                    <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                           id="name" name="name" value="{{ old('name') }}" 
                                           placeholder="Ej: Benito Páez, Los Olivos" required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="sector" class="form-label">Sector</label>
                                    <input type="text" class="form-control @error('sector') is-invalid @enderror" 
                                           id="sector" name="sector" value="{{ old('sector') }}" 
                                           placeholder="Ej: Norte, Centro">
                                    @error('sector')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Información adicional sobre la comunidad">{{ old('description') }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('locations.communities.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Guardar Comunidad
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection