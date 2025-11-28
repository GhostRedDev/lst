@extends('layouts.app')

@section('title', 'Gestión de Municipios - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-map"></i> Gestión de Municipios
                </h1>
                <a href="{{ route('locations.municipalities.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Municipio
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-body-sprl">
                    <form method="GET" action="{{ route('locations.municipalities.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="state_id" class="form-label">Filtrar por Estado</label>
                                <select class="form-control" id="state_id" name="state_id" onchange="this.form.submit()">
                                    <option value="">Todos los estados</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" 
                                            {{ request('state_id') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @if($municipalities->count() > 0)
    <div class="row">
        @foreach($municipalities as $municipality)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card card-app h-100">
                <div class="card-header-sprl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-city"></i> {{ $municipality->name }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('locations.municipalities.edit', $municipality) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('locations.municipalities.destroy', $municipality) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('¿Está seguro de eliminar este municipio?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body-sprl">
                    <div class="mb-3">
                        <strong>Estado:</strong> {{ $municipality->state->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Código:</strong> {{ $municipality->code }}
                    </div>
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="text-success mb-0">{{ $municipality->health_centers_count }}</h6>
                                <small class="text-muted">Centros</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="text-info mb-0">{{ $municipality->communities_count }}</h6>
                                <small class="text-muted">Comunidades</small>
                            </div>
                        </div>
                    </div>

                    @if($municipality->health_centers_count > 0)
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Centros de salud:</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($municipality->healthCenters->take(3) as $healthCenter)
                            <span class="badge bg-light text-dark border">
                                {{ $healthCenter->name }}
                            </span>
                            @endforeach
                            @if($municipality->health_centers_count > 3)
                            <span class="badge bg-secondary">+{{ $municipality->health_centers_count - 3 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('locations.health-centers.index', ['municipality_id' => $municipality->id]) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Ver Centros de Salud
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-body text-center py-5">
                    <i class="fas fa-map fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay municipios registrados</h4>
                    <p class="text-muted">Comienza agregando el primer municipio.</p>
                    <a href="{{ route('locations.municipalities.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Municipio
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection