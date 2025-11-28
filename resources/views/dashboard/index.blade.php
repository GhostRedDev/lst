@extends('layouts.app')

@section('title', 'Dashboard - SSP25 Sistema de Salud')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
     
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-tachometer-alt"></i> Dashboard SSP25
                </h1>
                <div class="btn-group">
                    <button class="btn btn-outline-primary" onclick="refreshDashboard()">
                        <i class="fas fa-sync-alt"></i> Actualizar
                    </button>
                    <span class="btn btn-outline-secondary">
                        <i class="fas fa-calendar"></i> {{ now()->format('d/m/Y') }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    @if(isset($error))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Error:</strong> {{ $error }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Estadísticas Principales -->
    <div class="row mb-4">
        <!-- Total Pacientes -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total de Pacientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="totalPatients">
                                {{ number_format($stats['total_patients']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Consultas Hoy -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Consultas Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="todayConsultations">
                                {{ number_format($stats['today_consultations']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Próximas Consultas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Próximas Consultas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="pendingConsultations">
                                {{ number_format($stats['pending_consultations']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gestantes Activas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Gestantes Activas
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="activePregnancies">
                                {{ number_format($stats['active_pregnancies']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-baby fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda Fila de Estadísticas -->
    <div class="row mb-4">
        <!-- Controles Niño Sano -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Controles Niño Sano
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="childControls">
                                {{ number_format($stats['child_controls']) }}
                            </div>
                            <div class="text-xs text-muted">(Último mes)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-child fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Visitas Domiciliarias -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Visitas Domiciliarias
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="homeVisits">
                                {{ number_format($stats['home_visits']) }}
                            </div>
                            <div class="text-xs text-muted">(Última semana)</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pacientes con Discapacidad -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Con Discapacidad
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="patientsWithDisability">
                                {{ number_format($stats['patients_with_disability']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wheelchair fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pacientes Alto Riesgo -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Alto Riesgo
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800" id="highRiskPatients">
                                {{ number_format($stats['high_risk_patients']) }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Primera Fila: Gráficos Principales -->
    <div class="row mb-4">
        <!-- Distribución por Género -->
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-venus-mars"></i> Distribución por Género
                    </h6>
                </div>
                <div class="card-body">
                    @if($demographicStats['gender_distribution']->count() > 0)
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="genderChart" width="400" height="200"></canvas>
                    </div>
                    <div class="mt-4 text-center small">
                        @foreach($demographicStats['gender_distribution'] as $gender => $count)
                        <span class="mr-2">
                            <i class="fas fa-circle" style="color: {{ $gender == 'M' ? '#4e73df' : '#e74a3b' }}"></i>
                            {{ $gender == 'M' ? 'Masculino' : 'Femenino' }} ({{ $count }})
                        </span>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-venus-mars fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de género</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Distribución por Grupos de Edad -->
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-friends"></i> Distribución por Edad
                    </h6>
                </div>
                <div class="card-body">
                    @if(count($demographicStats['age_groups']) > 0)
                    <div class="chart-bar pt-4 pb-2">
                        <canvas id="ageGroupsChart" width="400" height="200"></canvas>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-user-friends fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de grupos de edad</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Distribución por Clasificación -->
        <div class="col-xl-4 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-tags"></i> Clasificación de Pacientes
                    </h6>
                </div>
                <div class="card-body">
                    @if($demographicStats['classifications']->count() > 0)
                    <div class="chart-pie pt-4 pb-2">
                        <canvas id="classificationChart" width="400" height="200"></canvas>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-tags fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de clasificación</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Segunda Fila: Más Gráficos -->
    <div class="row mb-4">
        <!-- Factores de Riesgo -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-heartbeat"></i> Factores de Riesgo
                    </h6>
                </div>
                <div class="card-body">
                    @if($demographicStats['risk_factors']->count() > 0)
                    <div class="chart-bar pt-4 pb-2">
                        <canvas id="riskFactorsChart" width="400" height="200"></canvas>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-heartbeat fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de factores de riesgo</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Nivel de Escolaridad -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-graduation-cap"></i> Nivel de Escolaridad
                    </h6>
                </div>
                <div class="card-body">
                    @if($demographicStats['education_levels']->count() > 0)
                    <div class="chart-bar pt-4 pb-2">
                        <canvas id="educationChart" width="400" height="200"></canvas>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-graduation-cap fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No hay datos de escolaridad</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Tercera Fila: Ubicaciones y Actividad -->
    <div class="row">
        <!-- Top Comunidades -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-map-marker-alt"></i> Top Comunidades
                    </h6>
                </div>
                <div class="card-body">
                    @if($locationStats['top_communities']->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Comunidad</th>
                                    <th>Centro de Salud</th>
                                    <th>Pacientes</th>
                                    <th>Porcentaje</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($locationStats['top_communities'] as $index => $community)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>
                                        <i class="fas fa-building text-primary me-2"></i>
                                        {{ $community['name'] }}
                                    </td>
                                    <td>{{ $community['health_center'] }}</td>
                                    <td>
                                        <span class="badge bg-primary">{{ $community['patients_count'] }}</span>
                                    </td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: {{ $stats['total_patients'] > 0 ? ($community['patients_count'] / $stats['total_patients']) * 100 : 0 }}%"
                                                 aria-valuenow="{{ $stats['total_patients'] > 0 ? ($community['patients_count'] / $stats['total_patients']) * 100 : 0 }}" 
                                                 aria-valuemin="0" aria-valuemax="100">
                                                {{ $stats['total_patients'] > 0 ? number_format(($community['patients_count'] / $stats['total_patients']) * 100, 1) : 0 }}%
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="fas fa-building fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No hay comunidades con pacientes registrados</p>
                        <a href="{{ route('locations.communities.index') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Gestionar Comunidades
                        </a>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Actividad Reciente -->
        <div class="col-xl-6 col-lg-6">
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history"></i> Actividad Reciente
                    </h6>
                </div>
                <div class="card-body">
                    <div class="nav nav-tabs" id="activityTabs" role="tablist">
                        <button class="nav-link active" id="patients-tab" data-bs-toggle="tab" data-bs-target="#patients" type="button" role="tab">
                            <i class="fas fa-users"></i> Pacientes
                        </button>
                        <button class="nav-link" id="pregnancies-tab" data-bs-toggle="tab" data-bs-target="#pregnancies" type="button" role="tab">
                            <i class="fas fa-baby"></i> Gestantes
                        </button>
                        <button class="nav-link" id="children-tab" data-bs-toggle="tab" data-bs-target="#children" type="button" role="tab">
                            <i class="fas fa-child"></i> Niños
                        </button>
                    </div>
                    
                    <div class="tab-content mt-3" id="activityTabsContent">
                        <!-- Pacientes Recientes -->
                        <div class="tab-pane fade show active" id="patients" role="tabpanel">
                            @if($recentActivity['recent_patients']->count() > 0)
                            <div class="list-group">
                                @foreach($recentActivity['recent_patients'] as $patient)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="fas fa-user me-2"></i>
                                            {{ $patient->last_names }} {{ $patient->names }}
                                        </h6>
                                        <small class="text-muted">{{ $patient->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            <i class="fas fa-id-card me-1"></i>{{ $patient->id_card }} | 
                                            <i class="fas fa-map-marker-alt me-1"></i>
                                            @if($patient->house && $patient->house->street && $patient->house->street->community)
                                                {{ $patient->house->street->community->name }}
                                            @else
                                                Sin ubicación
                                            @endif
                                        </small>
                                    </p>
                                    <small>
                                        <span class="badge bg-{{ $patient->gender == 'M' ? 'primary' : 'danger' }}">
                                            {{ $patient->gender == 'M' ? 'M' : 'F' }}
                                        </span>
                                        <span class="badge bg-info">{{ $patient->age }} años</span>
                                        <span class="badge bg-warning">{{ $patient->classification }}</span>
                                    </small>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-users fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay pacientes recientes</p>
                            </div>
                            @endif
                        </div>

                        <!-- Gestantes Recientes -->
                        <div class="tab-pane fade" id="pregnancies" role="tabpanel">
                            @if($recentActivity['recent_pregnancies']->count() > 0)
                            <div class="list-group">
                                @foreach($recentActivity['recent_pregnancies'] as $pregnancy)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="fas fa-baby me-2"></i>
                                            {{ $pregnancy->patient->last_names }} {{ $pregnancy->patient->names }}
                                        </h6>
                                        <small class="text-muted">{{ $pregnancy->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            Semana {{ $pregnancy->current_week }} | 
                                            FPP: {{ $pregnancy->estimated_delivery->format('d/m/Y') }}
                                        </small>
                                    </p>
                                    <small>
                                        <span class="badge bg-success">{{ $pregnancy->prenatal_controls }} controles</span>
                                        @if($pregnancy->risk_factors)
                                        <span class="badge bg-warning">Con riesgos</span>
                                        @endif
                                    </small>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-baby fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay gestantes activas recientes</p>
                            </div>
                            @endif
                        </div>

                        <!-- Controles Niño Sano Recientes -->
                        <div class="tab-pane fade" id="children" role="tabpanel">
                            @if($recentActivity['recent_child_controls']->count() > 0)
                            <div class="list-group">
                                @foreach($recentActivity['recent_child_controls'] as $control)
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <i class="fas fa-child me-2"></i>
                                            {{ $control->patient->last_names }} {{ $control->patient->names }}
                                        </h6>
                                        <small class="text-muted">{{ $control->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p class="mb-1">
                                        <small class="text-muted">
                                            Peso: {{ $control->weight }} kg | 
                                            Talla: {{ $control->height }} cm |
                                            IMC: {{ $control->bmi }}
                                        </small>
                                    </p>
                                    <small>
                                        <span class="badge bg-{{ $control->nutritional_status == 'OPTIMO' ? 'success' : 'warning' }}">
                                            {{ $control->nutritional_status }}
                                        </span>
                                        <span class="badge bg-info">Próximo: {{ $control->next_control_date->format('d/m') }}</span>
                                    </small>
                                </div>
                                @endforeach
                            </div>
                            @else
                            <div class="text-center py-4">
                                <i class="fas fa-child fa-2x text-muted mb-3"></i>
                                <p class="text-muted">No hay controles recientes de niño sano</p>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Resumen del Sistema -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle"></i> Resumen del Sistema
                    </h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-globe-americas fa-2x text-primary mb-2"></i>
                                <h5 class="mb-1">{{ $locationStats['states'] }}</h5>
                                <small class="text-muted">Estados</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-city fa-2x text-success mb-2"></i>
                                <h5 class="mb-1">{{ $locationStats['municipalities'] }}</h5>
                                <small class="text-muted">Municipios</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-hospital fa-2x text-info mb-2"></i>
                                <h5 class="mb-1">{{ $locationStats['health_centers'] }}</h5>
                                <small class="text-muted">Centros de Salud</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-building fa-2x text-warning mb-2"></i>
                                <h5 class="mb-1">{{ $locationStats['communities'] }}</h5>
                                <small class="text-muted">Comunidades</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-road fa-2x text-danger mb-2"></i>
                                <h5 class="mb-1">{{ $locationStats['streets'] }}</h5>
                                <small class="text-muted">Calles</small>
                            </div>
                        </div>
                        <div class="col-md-2 col-6 mb-3">
                            <div class="border rounded p-3">
                                <i class="fas fa-home fa-2x text-secondary mb-2"></i>
                                <h5 class="mb-1">{{ $locationStats['houses'] }}</h5>
                                <small class="text-muted">Casas</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .card {
        border: none;
        border-radius: 10px;
    }
    .card-header {
        background: white;
        border-bottom: 1px solid #e3e6f0;
    }
    .border-left-primary { border-left: 4px solid #4e73df !important; }
    .border-left-success { border-left: 4px solid #1cc88a !important; }
    .border-left-info { border-left: 4px solid #36b9cc !important; }
    .border-left-warning { border-left: 4px solid #f6c23e !important; }
    .border-left-danger { border-left: 4px solid #e74a3b !important; }
    .progress { height: 8px; }
    .nav-tabs .nav-link.active {
        background-color: #f8f9fc;
        border-color: #e3e6f0 #e3e6f0 #f8f9fc;
    }
</style>
@endpush

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Datos para los gráficos
const genderData = {
    labels: {!! json_encode($demographicStats['gender_distribution']->keys()->map(function($g) { 
        return $g == 'M' ? 'Masculino' : 'Femenino'; 
    })) !!},
    datasets: [{
        data: {!! json_encode($demographicStats['gender_distribution']->values()) !!},
        backgroundColor: ['#4e73df', '#e74a3b'],
        hoverBackgroundColor: ['#4e73df', '#e74a3b']
    }]
};

const ageGroupsData = {
    labels: {!! json_encode(array_keys($demographicStats['age_groups'])) !!},
    datasets: [{
        label: 'Pacientes',
        data: {!! json_encode(array_values($demographicStats['age_groups'])) !!},
        backgroundColor: '#1cc88a',
        borderColor: '#17a673',
        borderWidth: 1
    }]
};

const classificationData = {
    labels: {!! json_encode($demographicStats['classifications']->keys()) !!},
    datasets: [{
        data: {!! json_encode($demographicStats['classifications']->values()) !!},
        backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1'],
        hoverBackgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#6f42c1']
    }]
};

const riskFactorsData = {
    labels: {!! json_encode($demographicStats['risk_factors']->keys()) !!},
    datasets: [{
        label: 'Pacientes',
        data: {!! json_encode($demographicStats['risk_factors']->values()) !!},
        backgroundColor: '#e74a3b',
        borderColor: '#d52a1e',
        borderWidth: 1
    }]
};

const educationData = {
    labels: {!! json_encode($demographicStats['education_levels']->keys()) !!},
    datasets: [{
        label: 'Pacientes',
        data: {!! json_encode($demographicStats['education_levels']->values()) !!},
        backgroundColor: '#6f42c1',
        borderColor: '#5a32a3',
        borderWidth: 1
    }]
};

// Inicializar gráficos cuando el documento esté listo
document.addEventListener('DOMContentLoaded', function() {
    // Gráfico de género (Pie)
    @if($demographicStats['gender_distribution']->count() > 0)
    new Chart(document.getElementById('genderChart'), {
        type: 'pie',
        data: genderData,
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif

    // Gráfico de grupos de edad (Bar)
    @if(count($demographicStats['age_groups']) > 0)
    new Chart(document.getElementById('ageGroupsChart'), {
        type: 'bar',
        data: ageGroupsData,
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif

    // Gráfico de clasificación (Pie)
    @if($demographicStats['classifications']->count() > 0)
    new Chart(document.getElementById('classificationChart'), {
        type: 'pie',
        data: classificationData,
        options: {
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif

    // Gráfico de factores de riesgo (Bar)
    @if($demographicStats['risk_factors']->count() > 0)
    new Chart(document.getElementById('riskFactorsChart'), {
        type: 'bar',
        data: riskFactorsData,
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif

    // Gráfico de escolaridad (Bar)
    @if($demographicStats['education_levels']->count() > 0)
    new Chart(document.getElementById('educationChart'), {
        type: 'bar',
        data: educationData,
        options: {
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
    @endif
});

// Función para actualizar el dashboard
function refreshDashboard() {
    const refreshBtn = event.target;
    const originalHtml = refreshBtn.innerHTML;
    
    refreshBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Actualizando...';
    refreshBtn.disabled = true;

    fetch('/dashboard/data')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Actualizar estadísticas principales
                document.getElementById('totalPatients').textContent = data.data.stats.total_patients.toLocaleString();
                document.getElementById('todayConsultations').textContent = data.data.stats.today_consultations.toLocaleString();
                document.getElementById('pendingConsultations').textContent = data.data.stats.pending_consultations.toLocaleString();
                document.getElementById('activePregnancies').textContent = data.data.stats.active_pregnancies.toLocaleString();
                document.getElementById('childControls').textContent = data.data.stats.child_controls.toLocaleString();
                document.getElementById('homeVisits').textContent = data.data.stats.home_visits.toLocaleString();
                document.getElementById('patientsWithDisability').textContent = data.data.stats.patients_with_disability.toLocaleString();
                document.getElementById('highRiskPatients').textContent = data.data.stats.high_risk_patients.toLocaleString();
                
                showAlert('success', 'Dashboard actualizado correctamente');
            } else {
                showAlert('error', data.message || 'Error al actualizar');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showAlert('error', 'Error al actualizar el dashboard');
        })
        .finally(() => {
            refreshBtn.innerHTML = originalHtml;
            refreshBtn.disabled = false;
        });
}

function showAlert(type, message) {
    const alertClass = type === 'success' ? 'alert-success' : 'alert-danger';
    const alertHtml = `
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            <i class="fas fa-${type === 'success' ? 'check' : 'exclamation-triangle'} me-2"></i>
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    $('.container-fluid').prepend(alertHtml);
    
    setTimeout(() => {
        $('.alert').alert('close');
    }, 5000);
}

// Actualizar automáticamente cada 5 minutos
setInterval(refreshDashboard, 300000);
</script>
@endpush