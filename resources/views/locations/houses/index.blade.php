@extends('layouts.app')

@section('title', 'Casas - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0">
                    <i class="fas fa-home"></i> Gestión de Casas
                </h1>
                <div class="btn-group">
                    <a href="{{ route('locations.houses.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Casa
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
                    <form action="{{ route('locations.houses.index') }}" method="GET">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="community_id" class="form-label">Comunidad</label>
                                    <select class="form-control" id="community_id" name="community_id">
                                        <option value="">Todas las comunidades</option>
                                        @foreach($communities as $community)
                                            <option value="{{ $community->id }}" 
                                                {{ request('community_id') == $community->id ? 'selected' : '' }}>
                                                {{ $community->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="street_id" class="form-label">Calle</label>
                                    <select class="form-control" id="street_id" name="street_id">
                                        <option value="">Todas las calles</option>
                                        @foreach($streets as $street)
                                            <option value="{{ $street->id }}" 
                                                {{ request('street_id') == $street->id ? 'selected' : '' }}>
                                                {{ $street->name }} - {{ $street->community->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
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
                    <h3 class="mb-0">{{ $houses->count() }}</h3>
                    <p class="mb-0">Total Casas</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $houses->sum('patients_count') }}</h3>
                    <p class="mb-0">Total Pacientes</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $streets->count() }}</h3>
                    <p class="mb-0">Calles</p>
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

    <!-- Lista de Casas -->
    <div class="row">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-header-sprl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-list"></i> Lista de Casas
                        <span class="badge bg-primary ms-2">{{ $houses->count() }}</span>
                    </h5>
                </div>
                <div class="card-body-sprl">
                    @if($houses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>Número</th>
                                        <th>Calle</th>
                                        <th>Comunidad</th>
                                        <th>Centro de Salud</th>
                                        <th>Municipio</th>
                                        <th>Estado</th>
                                        <th>Pacientes</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($houses as $house)
                                    <tr>
                                        <td>
                                            <strong>{{ $house->house_number }}</strong>
                                            @if($house->description)
                                                <br><small class="text-muted">{{ Str::limit($house->description, 30) }}</small>
                                            @endif
                                        </td>
                                        <td>{{ $house->street->name }}</td>
                                        <td>{{ $house->street->community->name }}</td>
                                        <td>{{ $house->street->community->healthCenter->name ?? 'N/A' }}</td>
                                        <td>{{ $house->street->community->healthCenter->municipality->name ?? 'N/A' }}</td>
                                        <td>{{ $house->street->community->healthCenter->municipality->state->name ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info">{{ $house->patients_count }}</span>
                                        </td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="{{ route('locations.houses.show', $house) }}" 
                                                   class="btn btn-sm btn-outline-primary" 
                                                   title="Ver detalles">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <a href="{{ route('locations.houses.edit', $house) }}" 
                                                   class="btn btn-sm btn-outline-warning" 
                                                   title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="{{ route('locations.houses.destroy', $house) }}" 
                                                      method="POST" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" 
                                                            class="btn btn-sm btn-outline-danger" 
                                                            title="Eliminar"
                                                            onclick="return confirm('¿Está seguro de eliminar esta casa?')">
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
                            <i class="fas fa-home fa-3x text-muted mb-3"></i>
                            <h4 class="text-muted">No hay casas registradas</h4>
                            <p class="text-muted">Comienza agregando la primera casa al sistema.</p>
                            <a href="{{ route('locations.houses.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Crear Primera Casa
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection