@extends('layouts.app')

@section('title', 'Gestión de Centros de Salud - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-hospital"></i> Gestión de Centros de Salud
                </h1>
                <a href="{{ route('locations.health-centers.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Centro
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-body-sprl">
                    <form method="GET" action="{{ route('locations.health-centers.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="municipality_id" class="form-label">Filtrar por Municipio</label>
                                <select class="form-control" id="municipality_id" name="municipality_id" onchange="this.form.submit()">
                                    <option value="">Todos los municipios</option>
                                    @foreach($municipalities as $municipality)
                                        <option value="{{ $municipality->id }}" 
                                            {{ request('municipality_id') == $municipality->id ? 'selected' : '' }}>
                                            {{ $municipality->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="type" class="form-label">Filtrar por Tipo</label>
                                <select class="form-control" id="type" name="type" onchange="this.form.submit()">
                                    <option value="">Todos los tipos</option>
                                    @foreach($types as $type)
                                        <option value="{{ $type }}" 
                                            {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
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

    @if($health_centers->count() > 0)
    <div class="row">
        @foreach($health_centers as $health_center)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card card-app h-100">
                <div class="card-header-sprl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-hospital-alt"></i> {{ $health_center->name }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('locations.health-centers.show', $health_center) }}" class="btn btn-sm btn-outline-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('locations.health-centers.edit', $health_center) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('locations.health-centers.destroy', $health_center) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('¿Está seguro de eliminar este centro de salud?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body-sprl">
                    <div class="mb-2">
                        <strong>Tipo:</strong> 
                        <span class="badge bg-primary">{{ $health_center->type }}</span>
                    </div>
                    <div class="mb-2">
                        <strong>Municipio:</strong> {{ $health_center->municipality->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Estado:</strong> {{ $health_center->municipality->state->name }}
                    </div>
                    
                    <div class="mb-3">
                        <strong>Dirección:</strong>
                        <p class="text-muted small mb-2">{{ $health_center->address }}</p>
                    </div>

                    @if($health_center->phone)
                    <div class="mb-3">
                        <strong>Teléfono:</strong> {{ $health_center->phone }}
                    </div>
                    @endif
                    
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="text-success mb-0">{{ $health_center->communities_count }}</h6>
                                <small class="text-muted">Comunidades</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h6 class="text-info mb-0">{{ $health_center->patients_count }}</h6>
                                <small class="text-muted">Pacientes</small>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <a href="{{ route('locations.communities.index', ['health_center_id' => $health_center->id]) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Ver Comunidades
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
                    <i class="fas fa-hospital fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay centros de salud registrados</h4>
                    <p class="text-muted">Comienza agregando el primer centro de salud.</p>
                    <a href="{{ route('locations.health-centers.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Centro
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection