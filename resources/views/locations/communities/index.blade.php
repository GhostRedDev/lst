@extends('layouts.app')

@section('title', 'Gestión de Comunidades - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-map-marker-alt"></i> Gestión de Comunidades
                </h1>
                <a href="{{ route('locations.communities.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nueva Comunidad
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-body-sprl">
                    <form method="GET" action="{{ route('locations.communities.index') }}">
                        <div class="row">
                            <div class="col-md-4">
                                <label for="health_center_id" class="form-label">Filtrar por Centro de Salud</label>
                                <select class="form-control" id="health_center_id" name="health_center_id" onchange="this.form.submit()">
                                    <option value="">Todos los centros de salud</option>
                                    @foreach($health_centers as $health_center)
                                        <option value="{{ $health_center->id }}" 
                                            {{ request('health_center_id') == $health_center->id ? 'selected' : '' }}>
                                            {{ $health_center->name }}
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

    <div class="row">
        @foreach($communities as $community)
        <div class="col-md-4 mb-4">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-building"></i> {{ $community->name }}
                    </h5>
                </div>
                <div class="card-body-sprl">
                    <div class="mb-2">
                        <strong>Centro de Salud:</strong> 
                        {{ $community->healthCenter->name }}
                    </div>
                    <div class="mb-2">
                        <strong>Municipio:</strong> 
                        {{ $community->healthCenter->municipality->name }}
                    </div>
                    <div class="mb-3">
                        <strong>Estado:</strong> 
                        {{ $community->healthCenter->municipality->state->name }}
                    </div>
                    
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-primary mb-0">{{ $community->streets_count }}</h4>
                                <small class="text-muted">Calles</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="border rounded p-2">
                                <h4 class="text-success mb-0">{{ $community->patients_count }}</h4>
                                <small class="text-muted">Pacientes</small>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <a href="{{ route('locations.communities.show', $community) }}" class="btn btn-sm btn-outline-primary w-100">
                            <i class="fas fa-eye"></i> Ver Detalles
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    @if($communities->isEmpty())
    <div class="row">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-body text-center py-5">
                    <i class="fas fa-building fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay comunidades registradas</h4>
                    <p class="text-muted">Comienza agregando la primera comunidad.</p>
                    <a href="{{ route('locations.communities.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Comunidad
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection