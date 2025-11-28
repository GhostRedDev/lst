<div class="patient-details">
    <div class="row">
        <div class="col-md-6">
            <h5 class="border-bottom pb-2 mb-3">
                <i class="fas fa-user me-2"></i>Información Personal
            </h5>
            <table class="table table-sm">
                <tr>
                    <th width="40%">N° Historia:</th>
                    <td>{{ $patient->medical_history_number }}</td>
                </tr>
                <tr>
                    <th>Cédula:</th>
                    <td>{{ $patient->id_number }}</td>
                </tr>
                <tr>
                    <th>Apellidos:</th>
                    <td>{{ $patient->last_name }}</td>
                </tr>
                <tr>
                    <th>Nombres:</th>
                    <td>{{ $patient->first_name }}</td>
                </tr>
                <tr>
                    <th>Fecha Nacimiento:</th>
                    <td>{{ $patient->birth_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Edad:</th>
                    <td>{{ $patient->age }} años</td>
                </tr>
                <tr>
                    <th>Sexo:</th>
                    <td>{{ $patient->gender == 'M' ? 'Masculino' : 'Femenino' }}</td>
                </tr>
            </table>
        </div>
        
        <div class="col-md-6">
            <h5 class="border-bottom pb-2 mb-3">
                <i class="fas fa-home me-2"></i>Información de Contacto
            </h5>
            <table class="table table-sm">
                <tr>
                    <th width="40%">Dirección:</th>
                    <td>{{ $patient->address }}</td>
                </tr>
                <tr>
                    <th>Teléfono:</th>
                    <td>{{ $patient->phone ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Tipo Vivienda:</th>
                    <td>{{ $patient->housing_type }}</td>
                </tr>
                <tr>
                    <th>Grupo Dispensario:</th>
                    <td>{{ $patient->dispensary_group }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-md-6">
            <h5 class="border-bottom pb-2 mb-3">
                <i class="fas fa-stethoscope me-2"></i>Información Médica
            </h5>
            <table class="table table-sm">
                <tr>
                    <th width="40%">Factor de Riesgo:</th>
                    <td>
                        <span class="badge bg-warning">{{ $patient->risk_factor }}</span>
                    </td>
                </tr>
                <tr>
                    <th>Discapacidad:</th>
                    <td>{{ $patient->disability }}</td>
                </tr>
                <tr>
                    <th>Clasificación:</th>
                    <td>{{ $patient->classification ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <th>Diagnóstico:</th>
                    <td>{{ $patient->diagnosis }}</td>
                </tr>
            </table>
        </div>
        
        <div class="col-md-6">
            <h5 class="border-bottom pb-2 mb-3">
                <i class="fas fa-graduation-cap me-2"></i>Información Académica/Laboral
            </h5>
            <table class="table table-sm">
                <tr>
                    <th width="40%">Escolaridad:</th>
                    <td>{{ $patient->education_level }}</td>
                </tr>
                <tr>
                    <th>Ocupación:</th>
                    <td>{{ $patient->occupation }}</td>
                </tr>
                <tr>
                    <th>Profesión:</th>
                    <td>{{ $patient->profession ?? 'N/A' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <h5 class="border-bottom pb-2 mb-3">
                <i class="fas fa-calendar me-2"></i>Información de Consultas
            </h5>
            <table class="table table-sm">
                <tr>
                    <th width="20%">Fecha Consulta:</th>
                    <td>{{ $patient->consultation_date->format('d/m/Y') }}</td>
                </tr>
                <tr>
                    <th>Próxima Consulta:</th>
                    <td>
                        @if($patient->next_consultation)
                            {{ $patient->next_consultation->format('d/m/Y') }}
                        @else
                            <span class="text-muted">No programada</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Observaciones:</th>
                    <td>{{ $patient->observation ?? 'Ninguna' }}</td>
                </tr>
            </table>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="d-flex justify-content-end gap-2">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cerrar
                </button>
                <button type="button" class="btn btn-warning edit-patient" data-id="{{ $patient->id }}">
                    <i class="fas fa-edit me-2"></i>Editar
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    .patient-details th {
        font-weight: 600;
        color: #495057;
    }
    .patient-details td {
        color: #6c757d;
    }
    .table-sm th, .table-sm td {
        padding: 0.5rem;
    }
</style>