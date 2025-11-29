@extends('layouts.app')

@section('title', 'Casa ' . $house->house_number . ' - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-home"></i> Casa {{ $house->house_number }}
                    </h1>
                    <p class="text-muted mb-0">
                        {{ $house->street->name }} - {{ $house->street->community->name }} - 
                        {{ $house->street->community->healthCenter->municipality->name }}, 
                        {{ $house->street->community->healthCenter->municipality->state->name }}
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('locations.houses.edit', $house) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar
                    </a>
                    <a href="{{ route('locations.streets.show', $house->street) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Casa -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información de la Casa
                    </h5>
                </div>
                <div class="card-body-sprl">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Número de Casa:</strong> {{ $house->house_number }}</p>
                            <p><strong>Calle:</strong> {{ $house->street->name }}</p>
                            <p><strong>Comunidad:</strong> {{ $house->street->community->name }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Centro de Salud:</strong> {{ $house->street->community->healthCenter->name ?? 'N/A' }}</p>
                            <p><strong>Municipio:</strong> {{ $house->street->community->healthCenter->municipality->name ?? 'N/A' }}</p>
                            <p><strong>Estado:</strong> {{ $house->street->community->healthCenter->municipality->state->name ?? 'N/A' }}</p>
                        </div>
                    </div>
                    @if($house->description)
                    <div class="mt-3">
                        <strong>Descripción:</strong>
                        <p class="mb-0">{{ $house->description }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $house->patients_count }}</h3>
                    <p class="mb-0">Pacientes Registrados</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Pacientes -->
    @if($house->patients->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Pacientes en esta Casa
                        <span class="badge bg-primary ms-2">{{ $house->patients->count() }}</span>
                    </h5>
                </div>
                <div class="card-body-sprl">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Cédula</th>
                                    <th>Teléfono</th>
                                    <th>Fecha Nacimiento</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($house->patients as $patient)
                                <tr>
                                    <td>{{ $patient->first_name }} {{ $patient->last_name }}</td>
                                    <td>{{ $patient->ci }}</td>
                                    <td>{{ $patient->phone }}</td>
                                    <td>{{ $patient->birth_date->format('d/m/Y') }}</td>
                                    <td>
                                        <a href="{{ route('patients.show', $patient) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @else
    <div class="row">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-body text-center py-5">
                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                    <h4 class="text-muted">No hay pacientes registrados</h4>
                    <p class="text-muted">Esta casa no tiene pacientes asociados.</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection