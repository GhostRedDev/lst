@extends('layouts.app')

@section('title', $street->name . ' - SPRL')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-road"></i> {{ $street->name }}
                    </h1>
                    <p class="text-muted mb-0">
                        <i class="fas fa-building"></i> 
                        Comunidad: {{ $street->community->name }}
                        @if($street->community->sector)
                         - Sector {{ $street->community->sector }}
                        @endif
                    </p>
                </div>
                <div class="btn-group">
                    <a href="{{ route('houses.create', $street) }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Casa
                    </a>
                    <a href="{{ route('houses.bulk-create', $street) }}" class="btn btn-success">
                        <i class="fas fa-layer-group"></i> Múltiples Casas
                    </a>
                    <a href="{{ route('communities.show', $street->community) }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Volver
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Estadísticas -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card bg-primary text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $street->houses->count() }}</h3>
                    <p class="mb-0">Total de Casas</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-success text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $street->patients->count() }}</h3>
                    <p class="mb-0">Pacientes Registrados</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card bg-info text-white">
                <div class="card-body text-center">
                    <h3 class="mb-0">{{ $street->houses->sum('total_residents') }}</h3>
                    <p class="mb-0">Total Residentes</p>
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
                        <i class="fas fa-home"></i> Casas en esta Calle
                    </h5>
                    <span class="badge bg-primary">{{ $street->houses->count() }} casas</span>
                </div>
                <div class="card-body-sprl">
                    @if($street->houses->count() > 0)
                    <div class="row">
                        @foreach($street->houses as $house)
                        <div class="col-md-3 mb-3">
                            <div class="card border-0 shadow-sm h-100">
                                <div class="card-body text-center">
                                    <div class="house-number display-6 text-primary mb-2">
                                        {{ $house->house_number }}
                                    </div>
                                    <div class="resident-count mb-3">
                                        @if($house->patients_count > 0)
                                        <span class="badge bg-success">
                                            <i class="fas fa-users"></i>
                                            {{ $house->patients_count }} residente{{ $house->patients_count > 1 ? 's' : '' }}
                                        </span>
                                        @else
                                        <span class="badge bg-secondary">
                                            <i class="fas fa-home"></i>
                                            Sin residentes
                                        </span>
                                        @endif
                                    </div>
                                    <div class="btn-group w-100">
                                        <a href="{{ route('houses.show', $house) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-outline-info view-residents" 
                                                data-house-id="{{ $house->id }}"
                                                data-house-number="{{ $house->house_number }}">
                                            <i class="fas fa-users"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-home fa-3x text-muted mb-3"></i>
                        <h4 class="text-muted">No hay casas registradas</h4>
                        <p class="text-muted">Comienza agregando la primera casa a esta calle.</p>
                        <div class="btn-group">
                            <a href="{{ route('houses.create', $street) }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Agregar Casa Individual
                            </a>
                            <a href="{{ route('houses.bulk-create', $street) }}" class="btn btn-success">
                                <i class="fas fa-layer-group"></i> Crear Múltiples Casas
                            </a>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Información de la Calle -->
    @if($street->description)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card card-app">
                <div class="card-header-sprl">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle"></i> Información de la Calle
                    </h5>
                </div>
                <div class="card-body-sprl">
                    <p class="mb-0">{{ $street->description }}</p>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Modal para ver residentes -->
<div class="modal fade" id="residentsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="residentsModalLabel">Residentes de la Casa <span id="modalHouseNumber"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="residentsList">
                <!-- Los residentes se cargarán aquí -->
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Ver residentes de una casa
    $('.view-residents').click(function() {
        const houseId = $(this).data('house-id');
        const houseNumber = $(this).data('house-number');
        
        $('#modalHouseNumber').text(houseNumber);
        $('#residentsList').html(`
            <div class="text-center py-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Cargando residentes...</p>
            </div>
        `);
        
        $.get(`/houses/${houseId}/residents`, function(data) {
            if (data.length > 0) {
                let html = '<div class="list-group">';
                data.forEach(patient => {
                    html += `
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">${patient.first_name} ${patient.last_name}</h6>
                                <small class="text-muted">Cédula: ${patient.id_number} | Edad: ${patient.age} años</small>
                            </div>
                            <span class="badge bg-primary">${patient.gender == 'M' ? 'Masculino' : 'Femenino'}</span>
                        </div>
                    </div>
                    `;
                });
                html += '</div>';
                $('#residentsList').html(html);
            } else {
                $('#residentsList').html(`
                    <div class="text-center py-4">
                        <i class="fas fa-users-slash fa-2x text-muted mb-3"></i>
                        <p class="text-muted">No hay residentes registrados en esta casa.</p>
                    </div>
                `);
            }
        });
        
        $('#residentsModal').modal('show');
    });
});
</script>
@endpush