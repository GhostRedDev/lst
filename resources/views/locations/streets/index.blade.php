@extends('layouts.app')

@section('title', 'Calles - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-road"></i> Gestión de Calles
                </h1>
                <div class="btn-group">
                    <a href="{{ route('locations.streets.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Calle
                    </a>
                    <a href="{{ route('locations.dashboard') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Filtros -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-filter"></i> Filtros
                    </h5>
                </div>
                <div class="card-body-sprl">
                    <form action="{{ route('locations.streets.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="community_id" class="form-label">Filtrar por Comunidad</label>
                                    <select class="form-control" id="community_id" name="community_id">
                                        <option value="">Todas las comunidades</option>
                                        @foreach($communities as $community)
                                            <option value="{{ $community->id }}" 
                                                {{ request('community_id') == $community->id ? 'selected' : '' }}>
                                                {{ $community->name }} - {{ $community->healthCenter->name ?? 'Sin centro' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <div class="d-grid">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-search"></i> Filtrar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $streets->count() }}</h3>
                    <p class="mb-0">Total Calles</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $streets->sum('houses_count') }}</h3>
                    <p class="mb-0">Total Casas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $streets->sum('patients_count') }}</h3>
                    <p class="mb-0">Total Pacientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $communities->count() }}</h3>
                    <p class="mb-0">Comunidades</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Calles -->
    <div class="row">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-header-sprl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Calles
                        <span class="badge bg-primary ms-2">{{ $streets->count() }}</span>
                    </h5>
                </div>
                <div class="card-body-sprl">
                    @if($streets->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Nombre</th>
                                        <th>Comunidad</th>
                                        <th>Centro de Salud</th>
                                        <th>Municipio</th>
                                        <th>Estado</th>
                                        <th>Casas</th>
                                        <th>Pacientes</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($streets as $street)
                                    <tr>
                                        <td>
                                            <strong>{{ $street->name }}</strong>
                                            @if($street->description)
                                                <br><small class="text-muted">{{ Str::limit($street->description, 50) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $street->community->name }}</td>
                                        <td>{{ $street->community->healthCenter->name ?? 'N/A' }}</td>
                                        <td>{{ $street->community->healthCenter->municipality->name ?? 'N/A' }}</td>
                                        <td>{{ $street->community->healthCenter->municipality->state->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-success">{{ $street->houses_count }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $street->patients_count }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('locations.streets.show', $street) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('locations.streets.edit', $street) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <a href="{{ route('locations.houses.create.with-street', $street) }}" 
                                                   class="btn btn-sm btn-outline-success" 
                                                   title="Agregar Casa">
                                                    <i class="fas fa-plus"></i>
                                                </a>
                                                <form action="{{ route('locations.streets.destroy', $street) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Eliminar"
                                                            onclick="return confirm('¿Está seguro de eliminar esta calle?')">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <i class="fas fa-road fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay calles registradas</h4>
                            <p class="text-muted">Comienza agregando la primera calle al sistema.</p>
                            <a href="{{ route('locations.streets.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Calle
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection