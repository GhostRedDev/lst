@extends('layouts.app')

@section('title', 'Nuevo Paciente - SSP25')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-gray-800">
                    <i class="fas fa-user-plus"></i> Registrar Nuevo Paciente
                </h1>
                <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
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

    <div class="row justify-content-center">
        <div class="col-12">
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-user-circle"></i> Información del Paciente
                    </h6>
                </div>
                <div class="card-body">
                    <form id="patientForm" method="POST" action="{{ route('patients.store') }}">
                        @csrf
                        
                        <!-- Información Básica -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-id-card"></i> Información Básica
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="history_number" class="form-label">N° Historia Clínica *</label>
                                    <input type="text" class="form-control @error('history_number') is-invalid @enderror" 
                                           id="history_number" name="history_number" 
                                           value="{{ old('history_number') }}" required>
                                    @error('history_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="code" class="form-label">Código</label>
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code') }}">
                                    @error('code')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="id_card" class="form-label">Cédula de Identidad *</label>
                                    <input type="text" class="form-control @error('id_card') is-invalid @enderror" 
                                           id="id_card" name="id_card" value="{{ old('id_card') }}" required>
                                    @error('id_card')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Nombre Completo -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="last_names" class="form-label">Apellidos *</label>
                                    <input type="text" class="form-control @error('last_names') is-invalid @enderror" 
                                           id="last_names" name="last_names" value="{{ old('last_names') }}" required>
                                    @error('last_names')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="names" class="form-label">Nombres *</label>
                                    <input type="text" class="form-control @error('names') is-invalid @enderror" 
                                           id="names" name="names" value="{{ old('names') }}" required>
                                    @error('names')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Sistema de Ubicaciones Mejorado -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt"></i> Ubicación Geográfica
                                </h5>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> Seleccione la ubicación jerárquica del paciente
                                </div>
                            </div>
                            
                            <!-- Estado -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="state_id" class="form-label">Estado *</label>
                                    <select class="form-select @error('state_id') is-invalid @enderror" 
                                            id="state_id" name="state_id" required>
                                        <option value="">Seleccionar Estado</option>
                                        @foreach($states as $state)
                                            <option value="{{ $state->id }}" 
                                                {{ old('state_id') == $state->id ? 'selected' : '' }}>
                                                {{ $state->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('state_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Municipio -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="municipality_id" class="form-label">Municipio *</label>
                                    <select class="form-select @error('municipality_id') is-invalid @enderror" 
                                            id="municipality_id" name="municipality_id" disabled required>
                                        <option value="">Primero seleccione un estado</option>
                                    </select>
                                    @error('municipality_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Centro de Salud -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="health_center_id" class="form-label">Centro de Salud *</label>
                                    <select class="form-select @error('health_center_id') is-invalid @enderror" 
                                            id="health_center_id" name="health_center_id" disabled required>
                                        <option value="">Primero seleccione un municipio</option>
                                    </select>
                                    @error('health_center_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Comunidad -->
                            <div class="col-md-3">
                                <div class="mb-3">
                                    <label for="community_id" class="form-label">Comunidad *</label>
                                    <select class="form-select @error('community_id') is-invalid @enderror" 
                                            id="community_id" name="community_id" disabled required>
                                        <option value="">Primero seleccione un centro de salud</option>
                                    </select>
                                    @error('community_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Calle -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="street_id" class="form-label">Calle *</label>
                                    <select class="form-select @error('street_id') is-invalid @enderror" 
                                            id="street_id" name="street_id" disabled required>
                                        <option value="">Primero seleccione una comunidad</option>
                                    </select>
                                    @error('street_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Casa -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="house_id" class="form-label">Casa *</label>
                                    <select class="form-select @error('house_id') is-invalid @enderror" 
                                            id="house_id" name="house_id" disabled required>
                                        <option value="">Primero seleccione una calle</option>
                                    </select>
                                    @if($selectedHouse)
                                        <div class="form-text text-success">
                                            <i class="fas fa-check"></i> Casa pre-seleccionada: {{ $selectedHouse->house_number }}
                                        </div>
                                    @endif
                                    @error('house_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <!-- Anexado -->
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Estado de Residencia</label>
                                    <div class="form-check mt-2">
                                        <input class="form-check-input" type="checkbox" id="is_annexed" name="is_annexed" value="1"
                                               {{ old('is_annexed') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="is_annexed">
                                            Paciente Anexado
                                        </label>
                                    </div>
                                    <div class="form-text">
                                        Marque si el paciente reside temporalmente en esta ubicación
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Dirección Completa (Auto-generada) -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="address" class="form-label">Dirección Completa *</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror" 
                                              id="address" name="address" rows="2" readonly required>{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text text-info">
                                        <i class="fas fa-sync-alt"></i> Esta dirección se generará automáticamente al seleccionar la casa
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Información Demográfica -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-user-friends"></i> Información Demográfica
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="birth_date" class="form-label">Fecha de Nacimiento *</label>
                                    <input type="date" class="form-control @error('birth_date') is-invalid @enderror" 
                                           id="birth_date" name="birth_date" value="{{ old('birth_date') }}" required
                                           max="{{ date('Y-m-d') }}">
                                    @error('birth_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="age" class="form-label">Edad *</label>
                                    <input type="number" class="form-control @error('age') is-invalid @enderror" 
                                           id="age" name="age" min="0" max="150" value="{{ old('age') }}" required>
                                    @error('age')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Sexo *</label>
                                    <select class="form-select @error('gender') is-invalid @enderror" 
                                            id="gender" name="gender" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('gender') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('gender') == 'F' ? 'selected' : '' }}>Femenino</option>
                                    </select>
                                    @error('gender')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información de Contacto -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-phone"></i> Información de Contacto
                                </h5>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Teléfono</label>
                                    <input type="text" class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" name="phone" value="{{ old('phone') }}"
                                           placeholder="Ej: 0412-1234567">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="housing_type" class="form-label">Tipo de Vivienda *</label>
                                    <select class="form-select @error('housing_type') is-invalid @enderror" 
                                            id="housing_type" name="housing_type" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="CASA" {{ old('housing_type') == 'CASA' ? 'selected' : '' }}>Casa</option>
                                        <option value="APARTAMENTO" {{ old('housing_type') == 'APARTAMENTO' ? 'selected' : '' }}>Apartamento</option>
                                        <option value="RANCHO" {{ old('housing_type') == 'RANCHO' ? 'selected' : '' }}>Rancho</option>
                                        <option value="QUINTA" {{ old('housing_type') == 'QUINTA' ? 'selected' : '' }}>Quinta</option>
                                        <option value="OTRO" {{ old('housing_type') == 'OTRO' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                    @error('housing_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información Médica -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-stethoscope"></i> Información Médica
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="dispensary_group" class="form-label">Grupo Dispensario *</label>
                                    <select class="form-select @error('dispensary_group') is-invalid @enderror" 
                                            id="dispensary_group" name="dispensary_group" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="I" {{ old('dispensary_group') == 'I' ? 'selected' : '' }}>I</option>
                                        <option value="II" {{ old('dispensary_group') == 'II' ? 'selected' : '' }}>II</option>
                                        <option value="III" {{ old('dispensary_group') == 'III' ? 'selected' : '' }}>III</option>
                                        <option value="IV" {{ old('dispensary_group') == 'IV' ? 'selected' : '' }}>IV</option>
                                        <option value="V" {{ old('dispensary_group') == 'V' ? 'selected' : '' }}>V</option>
                                        <option value="VI" {{ old('dispensary_group') == 'VI' ? 'selected' : '' }}>VI</option>
                                    </select>
                                    @error('dispensary_group')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="risk_factors" class="form-label">Factores de Riesgo</label>
                                    <select class="form-select @error('risk_factors') is-invalid @enderror" 
                                            id="risk_factors" name="risk_factors" multiple>
                                        <option value="TABAQUISMO" {{ in_array('TABAQUISMO', old('risk_factors', [])) ? 'selected' : '' }}>Tabaquismo</option>
                                        <option value="ALCOHOLISMO" {{ in_array('ALCOHOLISMO', old('risk_factors', [])) ? 'selected' : '' }}>Alcoholismo</option>
                                        <option value="OBESIDAD" {{ in_array('OBESIDAD', old('risk_factors', [])) ? 'selected' : '' }}>Obesidad</option>
                                        <option value="DIABETES" {{ in_array('DIABETES', old('risk_factors', [])) ? 'selected' : '' }}>Diabetes</option>
                                        <option value="HIPERTENSION" {{ in_array('HIPERTENSION', old('risk_factors', [])) ? 'selected' : '' }}>Hipertensión</option>
                                        <option value="CARDIOVASCULAR" {{ in_array('CARDIOVASCULAR', old('risk_factors', [])) ? 'selected' : '' }}>Cardiovascular</option>
                                        <option value="RESPIRATORIO" {{ in_array('RESPIRATORIO', old('risk_factors', [])) ? 'selected' : '' }}>Respiratorio</option>
                                    </select>
                                    @error('risk_factors')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Mantenga presionado Ctrl para seleccionar múltiples opciones</div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="disability" class="form-label">Discapacidad *</label>
                                    <select class="form-select @error('disability') is-invalid @enderror" 
                                            id="disability" name="disability" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="SI" {{ old('disability') == 'SI' ? 'selected' : '' }}>SÍ</option>
                                        <option value="NO" {{ old('disability') == 'NO' ? 'selected' : '' }}>NO</option>
                                    </select>
                                    @error('disability')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información Académica/Laboral -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-graduation-cap"></i> Información Académica y Laboral
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="education" class="form-label">Escolaridad *</label>
                                    <select class="form-select @error('education') is-invalid @enderror" 
                                            id="education" name="education" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="ANALFABETA" {{ old('education') == 'ANALFABETA' ? 'selected' : '' }}>Analfabeta</option>
                                        <option value="PRIMARIA" {{ old('education') == 'PRIMARIA' ? 'selected' : '' }}>Primaria</option>
                                        <option value="SECUNDARIA" {{ old('education') == 'SECUNDARIA' ? 'selected' : '' }}>Secundaria</option>
                                        <option value="TECNICO" {{ old('education') == 'TECNICO' ? 'selected' : '' }}>Técnico</option>
                                        <option value="UNIVERSITARIO" {{ old('education') == 'UNIVERSITARIO' ? 'selected' : '' }}>Universitario</option>
                                        <option value="POSTGRADO" {{ old('education') == 'POSTGRADO' ? 'selected' : '' }}>Postgrado</option>
                                    </select>
                                    @error('education')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="occupation" class="form-label">Ocupación *</label>
                                    <input type="text" class="form-control @error('occupation') is-invalid @enderror" 
                                           id="occupation" name="occupation" value="{{ old('occupation') }}" required
                                           placeholder="Ej: Estudiante, Ama de casa, Obrero...">
                                    @error('occupation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="profession" class="form-label">Título o Profesión</label>
                                    <input type="text" class="form-control @error('profession') is-invalid @enderror" 
                                           id="profession" name="profession" value="{{ old('profession') }}"
                                           placeholder="Ej: Médico, Ingeniero, Licenciado...">
                                    @error('profession')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Información de Consulta -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-calendar-check"></i> Información de Consulta
                                </h5>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="next_appointment" class="form-label">Próxima Consulta *</label>
                                    <input type="date" class="form-control @error('next_appointment') is-invalid @enderror" 
                                           id="next_appointment" name="next_appointment" 
                                           value="{{ old('next_appointment') }}" required
                                           min="{{ date('Y-m-d', strtotime('+1 day')) }}">
                                    @error('next_appointment')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label for="classification" class="form-label">Clasificación *</label>
                                    <select class="form-select @error('classification') is-invalid @enderror" 
                                            id="classification" name="classification" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="AGUDO" {{ old('classification') == 'AGUDO' ? 'selected' : '' }}>Agudo</option>
                                        <option value="CRONICO" {{ old('classification') == 'CRONICO' ? 'selected' : '' }}>Crónico</option>
                                        <option value="DISCAPACITADO" {{ old('classification') == 'DISCAPACITADO' ? 'selected' : '' }}>Discapacitado</option>
                                        <option value="GESTANTE" {{ old('classification') == 'GESTANTE' ? 'selected' : '' }}>Gestante</option>
                                        <option value="NIÑO" {{ old('classification') == 'NIÑO' ? 'selected' : '' }}>Niño</option>
                                        <option value="ADULTO" {{ old('classification') == 'ADULTO' ? 'selected' : '' }}>Adulto</option>
                                        <option value="ADULTO_MAYOR" {{ old('classification') == 'ADULTO_MAYOR' ? 'selected' : '' }}>Adulto Mayor</option>
                                    </select>
                                    @error('classification')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Diagnóstico y Observaciones -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h5 class="text-primary mb-3">
                                    <i class="fas fa-file-medical"></i> Diagnóstico y Observaciones
                                </h5>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="diagnosis" class="form-label">Diagnóstico</label>
                                    <textarea class="form-control @error('diagnosis') is-invalid @enderror" 
                                              id="diagnosis" name="diagnosis" rows="3">{{ old('diagnosis') }}</textarea>
                                    @error('diagnosis')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="mb-3">
                                    <label for="observation" class="form-label">Observación</label>
                                    <textarea class="form-control @error('observation') is-invalid @enderror" 
                                              id="observation" name="observation" rows="3">{{ old('observation') }}</textarea>
                                    @error('observation')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Botones -->
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ route('patients.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-times"></i> Cancelar
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save"></i> Guardar Paciente
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Calcular edad automáticamente
    $('#birth_date').change(function() {
        const birthDate = new Date($(this).val());
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        
        $('#age').val(age);
        
        // Actualizar clasificación automáticamente
        updateClassification(age);
    });

    function updateClassification(age) {
        const classificationSelect = $('#classification');
        let suggestedClassification = '';
        
        if (age < 12) {
            suggestedClassification = 'NIÑO';
        } else if (age >= 12 && age < 18) {
            suggestedClassification = 'ADOLESCENTE';
        } else if (age >= 18 && age < 60) {
            suggestedClassification = 'ADULTO';
        } else {
            suggestedClassification = 'ADULTO_MAYOR';
        }
        
        // Solo sugerir si no hay valor seleccionado
        if (!classificationSelect.val()) {
            classificationSelect.val(suggestedClassification);
        }
    }

    // Sistema de ubicaciones jerárquico
    $('#state_id').change(function() {
        const stateId = $(this).val();
        resetDependentSelects(['municipality_id', 'health_center_id', 'community_id', 'street_id', 'house_id']);
        
        if (stateId) {
            loadMunicipalities(stateId);
        }
    });

    $('#municipality_id').change(function() {
        const municipalityId = $(this).val();
        resetDependentSelects(['health_center_id', 'community_id', 'street_id', 'house_id']);
        
        if (municipalityId) {
            loadHealthCenters(municipalityId);
        }
    });

    $('#health_center_id').change(function() {
        const healthCenterId = $(this).val();
        resetDependentSelects(['community_id', 'street_id', 'house_id']);
        
        if (healthCenterId) {
            loadCommunities(healthCenterId);
        }
    });

    $('#community_id').change(function() {
        const communityId = $(this).val();
        resetDependentSelects(['street_id', 'house_id']);
        
        if (communityId) {
            loadStreets(communityId);
        }
    });

    $('#street_id').change(function() {
        const streetId = $(this).val();
        resetDependentSelects(['house_id']);
        
        if (streetId) {
            loadHouses(streetId);
        }
    });

    $('#house_id').change(function() {
        const houseId = $(this).val();
        if (houseId) {
            updateAddress(houseId);
        }
    });

    // Funciones para cargar datos
    function loadMunicipalities(stateId) {
        $.get(`/ajax/municipalities/${stateId}`, function(data) {
            $('#municipality_id').html('<option value="">Seleccionar Municipio</option>');
            data.forEach(municipality => {
                $('#municipality_id').append(`<option value="${municipality.id}">${municipality.name}</option>`);
            });
            $('#municipality_id').prop('disabled', false);
        });
    }

    function loadHealthCenters(municipalityId) {
        $.get(`/ajax/health-centers/${municipalityId}`, function(data) {
            $('#health_center_id').html('<option value="">Seleccionar Centro de Salud</option>');
            data.forEach(center => {
                $('#health_center_id').append(`<option value="${center.id}">${center.name} (${center.type})</option>`);
            });
            $('#health_center_id').prop('disabled', false);
        });
    }

    function loadCommunities(healthCenterId) {
        $.get(`/ajax/communities/${healthCenterId}`, function(data) {
            $('#community_id').html('<option value="">Seleccionar Comunidad</option>');
            data.forEach(community => {
                $('#community_id').append(`<option value="${community.id}">${community.name}</option>`);
            });
            $('#community_id').prop('disabled', false);
        });
    }

    function loadStreets(communityId) {
        $.get(`/ajax/streets/${communityId}`, function(data) {
            $('#street_id').html('<option value="">Seleccionar Calle</option>');
            data.forEach(street => {
                $('#street_id').append(`<option value="${street.id}">${street.name}</option>`);
            });
            $('#street_id').prop('disabled', false);
        });
    }

    function loadHouses(streetId) {
        $.get(`/ajax/houses/${streetId}`, function(data) {
            $('#house_id').html('<option value="">Seleccionar Casa</option>');
            data.forEach(house => {
                $('#house_id').append(`<option value="${house.id}">Casa ${house.house_number}</option>`);
            });
            $('#house_id').prop('disabled', false);
            
            // Si hay una casa pre-seleccionada
            @if(isset($selectedHouse))
                $('#house_id').val({{ $selectedHouse->id }});
                updateAddress({{ $selectedHouse->id }});
            @endif
        });
    }

    function updateAddress(houseId) {
        $.get(`/ajax/house-details/${houseId}`, function(data) {
            const address = `${data.full_address}, ${data.community_name}, ${data.municipality_name}, ${data.state_name}`;
            $('#address').val(address);
        });
    }

    function resetDependentSelects(selectIds) {
        selectIds.forEach(selectId => {
            $(`#${selectId}`).html('<option value="">Cargando...</option>').prop('disabled', true);
        });
    }

    // Prevenir envío doble del formulario
    $('#patientForm').submit(function(e) {
        const submitBtn = $('#submitBtn');
        submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
        
        // Re-enable after 10 seconds in case of error
        setTimeout(() => {
            submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Paciente');
        }, 10000);
    });

    // Inicializar selects múltiples
    $('#risk_factors').select2({
        placeholder: 'Seleccione los factores de riesgo',
        allowClear: true
    });
});
</script>

<style>
.select2-container--default .select2-selection--multiple {
    border: 1px solid #ced4da;
    border-radius: 0.375rem;
}
.select2-container--default.select2-container--focus .select2-selection--multiple {
    border-color: #86b7fe;
    outline: 0;
    box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
}
</style>
@endpush