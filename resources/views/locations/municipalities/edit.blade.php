@extends('layouts.app')

@section('title', 'Editar Municipio - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit"></i> Editar Municipio
                </h1>
                <a href="{{ route('locations.municipalities.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">Editar Información del Municipio</h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('locations.municipalities.update', $municipality) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="state_id" class="form-label">Estado *</label>
                            <select class="form-control @error('state_id') is-invalid @enderror" 
                                    id="state_id" name="state_id" required>
                                <option value="">Seleccione un estado</option>
                                @foreach($states as $state)
                                    <option value="{{ $state->id }}" 
                                        {{ old('state_id', $municipality->state_id) == $state->id ? 'selected' : '' }}>
                                        {{ $state->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('state_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nombre del Municipio *</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $municipality->name) }}" 
                                   placeholder="Ej: Chacao, Maracaibo" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="code" class="form-label">Código *</label>
                            <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                   id="code" name="code" value="{{ old('code', $municipality->code) }}" 
                                   placeholder="Ej: CHA, MAR" required maxlength="10">
                            @error('code')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Código único para identificar el municipio</div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('locations.municipalities.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Municipio
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection