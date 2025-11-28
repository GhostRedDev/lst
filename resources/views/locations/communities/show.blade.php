@extends('layouts.app')

@section('title', $community->name . ' - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-building"></i> {{ $community->name }}
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-map"></i> 
                        {{ $community->sector ? 'Sector ' . $community->sector : 'Sector no especificado' }}
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('streets.create', $community) }}" class="btn btn-primary">
                        <i class="fas fa-road"></i> Nueva Calle
                    </a>
                    <a href="{{ route('communities.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $community->streets->count() }}</h3>
                    <p class="mb-0">Calles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $community->houses->count() }}</h3>
                    <p class="mb-0">Casas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $community->patients_count }}</h3>
                    <p class="mb-0">Pacientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $community->description ? '✓' : '✗' }}</h3>
                    <p class="mb-0">Descripción</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Calles de la Comunidad -->
    <div class="row">
        @forelse($community->streets as $street)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card card-app h-100">
                <div class="card-header-sprl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-road"></i> {{ $street->name }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('streets.show', $street) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="{{ route('houses.bulk-create', $street) }}" class="btn btn-sm btn-success">
                            <i class="fas fa-layer-group"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body-sprl">
                    <!-- Estadísticas de la calle -->
                    <div class="row text-center mb-3">
                        <div class="col-6">
                            <small class="text-muted">Casas</small>
                            <h6 class="mb-0">{{ $street->houses->count() }}</h6>
                        </div>
                        <div class="col-6">
                            <small class="text-muted">Pacientes</small>
                            <h6 class="mb-0">{{ $street->patients->count() }}</h6>
                        </div>
                    </div>

                    <!-- Casas recientes -->
                    @if($street->houses->count() > 0)
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Últimas casas:</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($street->houses->take(8) as $house)
                            <span class="badge bg-light text-dark border">
                                {{ $house->house_number }}
                                @if($house->patients_count > 0)
                                <span class="badge bg-success ms-1">{{ $house->patients_count }}</span>
                                @endif
                            </span>
                            @endforeach
                            @if($street->houses->count() > 8)
                            <span class="badge bg-secondary">+{{ $street->houses->count() - 8 }}</span>
                            @endif
                        </div>
                    </div>
                    @else
                    <div class="text-center py-3">
                        <i class="fas fa-home fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No hay casas registradas</p>
                    </div>
                    @endif

                    <!-- Acciones -->
                    <div class="d-grid gap-2">
                        <a href="{{ route('houses.create', $street) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-plus"></i> Agregar Casa
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="card card-app">
                <div class="card-body text-center py-5">
                    <i class="fas fa-road fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay calles registradas</h4>
                    <p class="text-muted">Comienza agregando la primera calle a esta comunidad.</p>
                    <a href="{{ route('streets.create', $community) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primera Calle
                    </a>
                </div>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Información de la Comunidad -->
    @if($community->description)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información de la Comunidad
                    </h5>
                </div>
                <div class="card-body-sprl">
                    <p class="mb-0">{{ $community->description }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection