@extends('layouts.app')

@section('title', 'Editar Casa - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-edit"></i> Editar Casa
                </h1>
                <a href="{{ route('locations.houses.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">Información de la Casa</h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('locations.houses.update', $house) }}" method="POST">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label for="street_id" class="form-label">Calle *</label>
                            <select class="form-control @error('street_id') is-invalid @enderror" 
                                    id="street_id" name="street_id" required>
                                <option value="">Seleccione una calle</option>
                                @foreach($streets as $street)
                                    <option value="{{ $street->id }}" 
                                        {{ old('street_id', $house->street_id) == $street->id ? 'selected' : '' }}>
                                        {{ $street->name }} - {{ $street->community->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('street_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="house_number" class="form-label">Número de Casa *</label>
                            <input type="text" class="form-control @error('house_number') is-invalid @enderror" 
                                   id="house_number" name="house_number" value="{{ old('house_number', $house->house_number) }}" 
                                   placeholder="Ej: 123, A-1, B-2" required>
                            @error('house_number')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Descripción</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" name="description" rows="3" 
                                      placeholder="Información adicional sobre la casa">{{ old('description', $house->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="{{ route('locations.houses.index') }}" class="btn btn-secondary me-md-2">
                                <i class="fas fa-times"></i> Cancelar
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Actualizar Casa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection