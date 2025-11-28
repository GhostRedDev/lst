@extends('layouts.app')

@section('title', 'Gestión de Estados - SPRL')

@section('content')
<div class="container-fluid">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-globe-americas"></i> Gestión de Estados
                </h1>
                <a href="{{ route('locations.states.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Nuevo Estado
                </a>
            </div>
        </div>
    </div>

    @if($states->count() > 0)
    <div class="row">
        @foreach($states as $state)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card card-app h-100">
                <div class="card-header-sprl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-flag"></i> {{ $state->name }}
                    </h5>
                    <div class="btn-group">
                        <a href="{{ route('locations.states.edit', $state) }}" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-edit"></i>
                        </a>
                        <form action="{{ route('locations.states.destroy', $state) }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger" 
                                    onclick="return confirm('¿Está seguro de eliminar este estado?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </div>
                </div>
                <div class="card-body-sprl">
                    <div class="mb-3">
                        <strong>Código:</strong> {{ $state->code }}
                    </div>
                    
                    <div class="row text-center mb-3">
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="text-primary mb-0">{{ $state->municipalities_count }}</h6>
                                <small class="text-muted">Municipios</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="text-success mb-0">{{ $state->health_centers_count }}</h6>
                                <small class="text-muted">Centros</small>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="border rounded p-2">
                                <h6 class="text-info mb-0">{{ $state->communities_count }}</h6>
                                <small class="text-muted">Comunidades</small>
                            </div>
                        </div>
                    </div>

                    @if($state->municipalities_count > 0)
                    <div class="mb-3">
                        <small class="text-muted d-block mb-2">Municipios principales:</small>
                        <div class="d-flex flex-wrap gap-1">
                            @foreach($state->municipalities->take(4) as $municipality)
                            <span class="badge bg-light text-dark border">
                                {{ $municipality->name }}
                            </span>
                            @endforeach
                            @if($state->municipalities_count > 4)
                            <span class="badge bg-secondary">+{{ $state->municipalities_count - 4 }}</span>
                            @endif
                        </div>
                    </div>
                    @endif

                    <div class="d-grid gap-2">
                        <a href="{{ route('locations.municipalities.index', ['state_id' => $state->id]) }}" 
                           class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-eye"></i> Ver Municipios
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
                    <i class="fas fa-globe-americas fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay estados registrados</h4>
                    <p class="text-muted">Comienza agregando el primer estado.</p>
                    <a href="{{ route('locations.states.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Crear Primer Estado
                    </a>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection