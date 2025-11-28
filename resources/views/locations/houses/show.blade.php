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
                        <i class="fas fa-road"></i> {{ $house->street->name }} - 
                        <i class="fas fa-building"></i> {{ $house->street->community->name }}
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('patients.create') }}?house_id={{ $house->id }}" class="btn btn-primary">
                        <i class="fas fa-user-plus"></i> Agregar Paciente
                    </a>
                    <a href="{{ route('streets.show', $house->street) }}" class="btn btn-secondary">
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
                    <h3 class="mb-0">{{ $house->patients->count() }}</h3>
                    <p class="mb-0">Pacientes Registrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $house->patients->where('gender', 'M')->count() }}</h3>
                    <p class="mb-0">Hombres</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $house->patients->where('gender', 'F')->count() }}</h3>
                    <p class="mb-0">Mujeres</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-warning text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $house->patients->avg('age') ? round($house->patients->avg('age')) : 0 }}</h3>
                    <p class="mb-0">Edad Promedio</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Casa -->
    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información de la Casa
                    </h5>
                </div>
                <div class="card-body-sprl">
                    <table class="table table-sm">
                        <tr>
                            <th width="40%">Número de Casa:</th>
                            <td><strong>{{ $house->house_number }}</strong></td>
                        </tr>
                        <tr>
                            <th>Calle:</th>
                            <td>{{ $house->street->name }}</td>
                        </tr>
                        <tr>
                            <th>Comunidad:</th>
                            <td>{{ $house->street->community->name }}</td>
                        </tr>
                        <tr>
                            <th>Sector:</th>
                            <td>{{ $house->street->community->sector ?? 'No especificado' }}</td>
                        </tr>
                        <tr>
                            <th>Dirección Completa:</th>
                            <td>{{ $house->full_address }}</td>
                        </tr>
                        @if($house->description)
                        <tr>
                            <th>Descripción:</th>
                            <td>{{ $house->description }}</td>
                        </tr>
                        @endif
                    </table>
                </div>
            </div>
        </div>

        <div class="col-md-6">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie"></i> Estadísticas de Salud
                    </h5>
                </div>
                <div class="card-body-sprl">
                    @php
                        $riskFactors = $house->patients->groupBy('risk_factor')->map->count();
                        $disabilities = $house->patients->groupBy('disability')->map->count();
                    @endphp
                    
                    <div class="mb-3">
                        <strong>Factores de Riesgo:</strong>
                        @if($riskFactors->count() > 0)
                            @foreach($riskFactors as $risk => $count)
                            <span class="badge bg-warning ms-1">{{ $risk }}: {{ $count }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No hay datos</span>
                        @endif
                    </div>
                    
                    <div class="mb-3">
                        <strong>Discapacidades:</strong>
                        @if($disabilities->count() > 0)
                            @foreach($disabilities as $disability => $count)
                            <span class="badge bg-info ms-1">{{ $disability }}: {{ $count }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">No hay datos</span>
                        @endif
                    </div>
                    
                    <div class="mb-0">
                        <strong>Última Consulta:</strong>
                        @if($house->patients->count() > 0)
                            {{ $house->patients->max('consultation_date')->format('d/m/Y') }}
                        @else
                            <span class="text-muted">No hay consultas</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Lista de Pacientes -->
    <div class="row">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-header-sprl d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-users"></i> Pacientes en esta Casa
                        <span class="badge bg-primary ms-2">{{ $house->patients->count() }}</span>
                    </h5>
                    <a href="{{ route('patients.create') }}?house_id={{ $house->id }}" class="btn btn-sm btn-primary">
                        <i class="fas fa-user-plus"></i> Nuevo Paciente
                    </a>
                </div>
                <div class="card-body-sprl">
                    @if($house->patients->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>N° Historia</th>
                                    <th>Nombre Completo</th>
                                    <th>Cédula</th>
                                    <th>Edad</th>
                                    <th>Sexo</th>
                                    <th>Factor Riesgo</th>
                                    <th>Teléfono</th>
                                    <th>Última Consulta</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($house->patients as $patient)
                                <tr>
                                    <td>
                                        <strong>{{ $patient->medical_history_number }}</strong>
                                    </td>
                                    <td>
                                        {{ $patient->first_name }} {{ $patient->last_name }}
                                    </td>
                                    <td>{{ $patient->id_number }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $patient->age }} años</span>
                                    </td>
                                    <td>
                                        @if($patient->gender == 'M')
                                            <span class="badge bg-primary">Masculino</span>
                                        @else
                                            <span class="badge bg-pink">Femenino</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-warning">{{ $patient->risk_factor }}</span>
                                    </td>
                                    <td>
                                        @if($patient->phone)
                                            <i class="fas fa-phone text-muted me-1"></i>
                                            {{ $patient->phone }}
                                        @else
                                            <span class="text-muted">N/A</span>
                                        @endif
                                    </td>
                                    <td>
                                        {{ $patient->consultation_date->format('d/m/Y') }}
                                    </td>
                                    <td>
                                        <div class="btn-group btn-group-sm">
                                            <a href="{{ route('patients.show', $patient) }}" 
                                               class="btn btn-outline-primary" title="Ver">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('patients.edit', $patient) }}" 
                                               class="btn btn-outline-warning" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-users-slash fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay pacientes registrados</h4>
                        <p class="text-muted">Esta casa no tiene pacientes asociados.</p>
                        <a href="{{ route('patients.create') }}?house_id={{ $house->id }}" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Registrar Primer Paciente
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.bg-pink {
    background-color: #e83e8c !important;
}
</style>
@endsection