@extends('layouts.app')

@section('title', 'Gestión de Pacientes - SSP25')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-users-medical"></i> Gestión de Pacientes
                </h1>
                <div>
                    <a href="{{ route('patients.export') }}" class="btn btn-success">
                        <i class="fas fa-file-excel"></i> Exportar Excel
                    </a>
                    <a href="{{ route('patients.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nuevo Paciente
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Pacientes
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $patients->total() }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Consultas Hoy
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">0</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Con Discapacidad
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $patients->where('disability', 'SI')->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-wheelchair fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Anexados
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                {{ $patients->where('is_annexed', true)->count() }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-home fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-filter"></i> Filtros de Búsqueda
            </h6>
        </div>
        <div class="card-body">
            <form id="searchForm">
                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="search" class="form-label">Búsqueda General</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   placeholder="Nombre, cédula, historia..." value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="gender" class="form-label">Sexo</label>
                            <select class="form-select" id="gender" name="gender">
                                <option value="">Todos</option>
                                <option value="M" {{ request('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                                <option value="F" {{ request('gender') == 'F' ? 'selected' : '' }}>Femenino</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label for="classification" class="form-label">Clasificación</label>
                            <select class="form-select" id="classification" name="classification">
                                <option value="">Todas</option>
                                <option value="AGUDO" {{ request('classification') == 'AGUDO' ? 'selected' : '' }}>Agudo</option>
                                <option value="CRONICO" {{ request('classification') == 'CRONICO' ? 'selected' : '' }}>Crónico</option>
                                <option value="GESTANTE" {{ request('classification') == 'GESTANTE' ? 'selected' : '' }}>Gestante</option>
                                <option value="NIÑO" {{ request('classification') == 'NIÑO' ? 'selected' : '' }}>Niño</option>
                                <option value="ADULTO" {{ request('classification') == 'ADULTO' ? 'selected' : '' }}>Adulto</option>
                                <option value="ADULTO_MAYOR" {{ request('classification') == 'ADULTO_MAYOR' ? 'selected' : '' }}>Adulto Mayor</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="community_id" class="form-label">Comunidad</label>
                            <select class="form-select" id="community_id" name="community_id">
                                <option value="">Todas las comunidades</option>
                                @foreach($communities as $community)
                                    <option value="{{ $community->id }}" {{ request('community_id') == $community->id ? 'selected' : '' }}>
                                        {{ $community->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="mb-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i> Buscar
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="is_annexed" name="is_annexed" value="1" 
                                   {{ request('is_annexed') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_annexed">
                                Solo pacientes anexados
                            </label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="checkbox" id="with_disability" name="with_disability" value="1"
                                   {{ request('with_disability') ? 'checked' : '' }}>
                            <label class="form-check-label" for="with_disability">
                                Solo con discapacidad
                            </label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Patients Table Card -->
    <div class="card shadow">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 font-weight-bold text-primary">
                <i class="fas fa-table"></i> Lista de Pacientes
                <span class="badge bg-primary ms-2">{{ $patients->total() }}</span>
            </h6>
            <div class="btn-group">
                <a href="{{ route('locations.dashboard') }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-map-marker-alt"></i> Ver Ubicaciones
                </a>
                <a href="{{ route('pregnancies.index') }}" class="btn btn-sm btn-outline-success">
                    <i class="fas fa-baby"></i> Gestantes
                </a>
                <a href="{{ route('child-health.index') }}" class="btn btn-sm btn-outline-info">
                    <i class="fas fa-child"></i> Niño Sano
                </a>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="patientsTableContainer">
                @include('patients.partials.table')
            </div>
        </div>
    </div>
</div>

<!-- View Patient Modal -->
<div class="modal fade" id="viewPatientModal" tabindex="-1" aria-labelledby="viewPatientModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="viewPatientModalLabel">
                    <i class="fas fa-user"></i> Información del Paciente
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="patientDetails">
                <!-- Los detalles del paciente se cargarán aquí -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times"></i> Cerrar
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.03);
    }
    .badge-sm {
        font-size: 0.7em;
    }
    .bg-pink {
        background-color: #e83e8c !important;
    }
    .bg-purple {
        background-color: #6f42c1 !important;
    }
    .bg-teal {
        background-color: #20c997 !important;
    }
</style>
@endpush

@push('scripts')
<script>
$(document).ready(function() {
    // Búsqueda y filtros
    $('#searchForm').submit(function(e) {
        e.preventDefault();
        loadPatients();
    });

    // Cargar pacientes
    function loadPatients() {
        const formData = $('#searchForm').serialize();
        const url = '{{ route("patients.index") }}?' + formData;
        
        $.ajax({
            url: url,
            type: 'GET',
            success: function(response) {
                $('#patientsTableContainer').html(response);
            },
            error: function(xhr) {
                console.error('Error loading patients:', xhr);
                showAlert('error', 'Error al cargar los pacientes');
            }
        });
    }

    // Ver paciente
    $(document).on('click', '.view-patient', function() {
        const patientId = $(this).data('id');
        
        $.ajax({
            url: `/patients/${patientId}`,
            method: 'GET',
            success: function(response) {
                $('#patientDetails').html(response);
                $('#viewPatientModal').modal('show');
            },
            error: function(xhr) {
                showAlert('error', 'Error al cargar los detalles del paciente');
            }
        });
    });

    // Eliminar paciente
    $(document).on('click', '.delete-patient', function(e) {
        e.preventDefault();
        const patientId = $(this).data('id');
        const patientName = $(this).data('name');
        
        if (confirm(`¿Está seguro de que desea eliminar al paciente ${patientName}?`)) {
            $.ajax({
                url: `/patients/${patientId}`,
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    if (response.success) {
                        showAlert('success', response.message);
                        loadPatients();
                    }
                },
                error: function(xhr) {
                    const message = xhr.responseJSON?.message || 'Error al eliminar el paciente';
                    showAlert('error', message);
                }
            });
        }
    });

    // Funciones auxiliares
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

    // Cargar pacientes al cambiar filtros
    $('#gender, #classification, #community_id, #is_annexed, #with_disability').change(function() {
        loadPatients();
    });

    // Búsqueda en tiempo real con debounce
    let searchTimeout;
    $('#search').on('input', function() {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            loadPatients();
        }, 500);
    });

    // Inicializar tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();
});
</script>
@endpush