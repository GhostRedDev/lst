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

    <!-- Mostrar errores -->
    @if($errors->any())
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        <strong>Por favor corrige los siguientes errores:</strong>
        <ul class="mb-0">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-triangle me-2"></i>
        {{ session('error') }}
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
                                    @if(isset($selectedHouse))
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
                                              id="address" name="address" rows="2" readonly>{{ old('address') }}</textarea>
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
                                            id="risk_factors" name="risk_factors[]" multiple>
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
<!-- Cargar jQuery primero -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
// Esperar a que jQuery esté disponible
function checkJQuery() {
    if (window.jQuery) {
        initializeScript();
    } else {
        setTimeout(checkJQuery, 100);
    }
}

function initializeScript() {
    $(document).ready(function() {
        console.log('jQuery cargado correctamente, inicializando script...');

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
            
            if (!classificationSelect.val()) {
                classificationSelect.val(suggestedClassification);
            }
        }

        // Sistema de ubicaciones jerárquico
        $('#state_id').change(function() {
            const stateId = $(this).val();
            console.log('Estado seleccionado:', stateId);
            resetDependentSelects(['municipality_id', 'health_center_id', 'community_id', 'street_id', 'house_id']);
            
            if (stateId) {
                loadMunicipalities(stateId);
            } else {
                $('#municipality_id').html('<option value="">Seleccionar Municipio</option>').prop('disabled', true);
            }
        });

        $('#municipality_id').change(function() {
            const municipalityId = $(this).val();
            console.log('Municipio seleccionado:', municipalityId);
            resetDependentSelects(['health_center_id', 'community_id', 'street_id', 'house_id']);
            
            if (municipalityId) {
                loadHealthCenters(municipalityId);
            } else {
                $('#health_center_id').html('<option value="">Seleccionar Centro de Salud</option>').prop('disabled', true);
            }
        });

        $('#health_center_id').change(function() {
            const healthCenterId = $(this).val();
            console.log('Centro de salud seleccionado:', healthCenterId);
            resetDependentSelects(['community_id', 'street_id', 'house_id']);
            
            if (healthCenterId) {
                loadCommunities(healthCenterId);
            } else {
                $('#community_id').html('<option value="">Seleccionar Comunidad</option>').prop('disabled', true);
            }
        });

        $('#community_id').change(function() {
            const communityId = $(this).val();
            console.log('Comunidad seleccionada:', communityId);
            resetDependentSelects(['street_id', 'house_id']);
            
            if (communityId) {
                loadStreets(communityId);
            } else {
                $('#street_id').html('<option value="">Seleccionar Calle</option>').prop('disabled', true);
            }
        });

        $('#street_id').change(function() {
            const streetId = $(this).val();
            console.log('Calle seleccionada:', streetId);
            resetDependentSelects(['house_id']);
            
            if (streetId) {
                loadHouses(streetId);
            } else {
                $('#house_id').html('<option value="">Seleccionar Casa</option>').prop('disabled', true);
            }
        });

        $('#house_id').change(function() {
            const houseId = $(this).val();
            console.log('Casa seleccionada:', houseId);
            if (houseId) {
                updateAddress(houseId);
            } else {
                $('#address').val('');
            }
        });

        // Funciones para cargar datos
        function loadMunicipalities(stateId) {
            console.log('Cargando municipios para estado:', stateId);
            $.ajax({
                url: `/ajax/municipalities/${stateId}`,
                type: 'GET',
                success: function(data) {
                    console.log('Municipios cargados:', data);
                    $('#municipality_id').html('<option value="">Seleccionar Municipio</option>');
                    if (data.length > 0) {
                        data.forEach(municipality => {
                            $('#municipality_id').append(`<option value="${municipality.id}">${municipality.name}</option>`);
                        });
                        $('#municipality_id').prop('disabled', false);
                    } else {
                        $('#municipality_id').html('<option value="">No hay municipios</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando municipios:', error);
                    $('#municipality_id').html('<option value="">Error al cargar</option>');
                }
            });
        }

        function loadHealthCenters(municipalityId) {
            console.log('Cargando centros de salud para municipio:', municipalityId);
            $.ajax({
                url: `/ajax/health-centers/${municipalityId}`,
                type: 'GET',
                success: function(data) {
                    console.log('Centros de salud cargados:', data);
                    $('#health_center_id').html('<option value="">Seleccionar Centro de Salud</option>');
                    if (data.length > 0) {
                        data.forEach(center => {
                            $('#health_center_id').append(`<option value="${center.id}">${center.name} (${center.type})</option>`);
                        });
                        $('#health_center_id').prop('disabled', false);
                    } else {
                        $('#health_center_id').html('<option value="">No hay centros de salud</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando centros de salud:', error);
                    $('#health_center_id').html('<option value="">Error al cargar</option>');
                }
            });
        }

        function loadCommunities(healthCenterId) {
            console.log('Cargando comunidades para centro de salud:', healthCenterId);
            $.ajax({
                url: `/ajax/communities/${healthCenterId}`,
                type: 'GET',
                success: function(data) {
                    console.log('Comunidades cargadas:', data);
                    $('#community_id').html('<option value="">Seleccionar Comunidad</option>');
                    if (data.length > 0) {
                        data.forEach(community => {
                            $('#community_id').append(`<option value="${community.id}">${community.name}</option>`);
                        });
                        $('#community_id').prop('disabled', false);
                    } else {
                        $('#community_id').html('<option value="">No hay comunidades</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando comunidades:', error);
                    $('#community_id').html('<option value="">Error al cargar</option>');
                }
            });
        }

        function loadStreets(communityId) {
            console.log('Cargando calles para comunidad:', communityId);
            $.ajax({
                url: `/ajax/streets/${communityId}`,
                type: 'GET',
                success: function(data) {
                    console.log('Calles cargadas:', data);
                    $('#street_id').html('<option value="">Seleccionar Calle</option>');
                    if (data.length > 0) {
                        data.forEach(street => {
                            $('#street_id').append(`<option value="${street.id}">${street.name}</option>`);
                        });
                        $('#street_id').prop('disabled', false);
                    } else {
                        $('#street_id').html('<option value="">No hay calles</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando calles:', error);
                    $('#street_id').html('<option value="">Error al cargar</option>');
                }
            });
        }

        function loadHouses(streetId) {
            console.log('Cargando casas para calle:', streetId);
            $.ajax({
                url: `/ajax/houses/${streetId}`,
                type: 'GET',
                success: function(data) {
                    console.log('Casas cargadas:', data);
                    $('#house_id').html('<option value="">Seleccionar Casa</option>');
                    if (data.length > 0) {
                        data.forEach(house => {
                            $('#house_id').append(`<option value="${house.id}">Casa ${house.house_number}</option>`);
                        });
                        $('#house_id').prop('disabled', false);
                        
                        // Si hay una casa pre-seleccionada
                        @if(isset($selectedHouse))
                            $('#house_id').val({{ $selectedHouse->id }});
                            updateAddress({{ $selectedHouse->id }});
                        @endif
                    } else {
                        $('#house_id').html('<option value="">No hay casas</option>');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando casas:', error);
                    $('#house_id').html('<option value="">Error al cargar</option>');
                }
            });
        }

        function updateAddress(houseId) {
            console.log('Actualizando dirección para casa:', houseId);
            $.ajax({
                url: `/ajax/house-details/${houseId}`,
                type: 'GET',
                success: function(data) {
                    console.log('Detalles de casa:', data);
                    if (data.error) {
                        $('#address').val('Error al cargar la dirección');
                        return;
                    }
                    const address = `${data.full_address}, ${data.community_name}, ${data.health_center_name}, ${data.municipality_name}, ${data.state_name}`;
                    $('#address').val(address);
                },
                error: function(xhr, status, error) {
                    console.error('Error cargando detalles de casa:', error);
                    $('#address').val('Error al cargar la dirección');
                }
            });
        }

        function resetDependentSelects(selectIds) {
            selectIds.forEach(selectId => {
                $(`#${selectId}`).html('<option value="">Cargando...</option>').prop('disabled', true);
            });
        }

        // Prevenir envío doble del formulario
        $('#patientForm').submit(function() {
            const submitBtn = $('#submitBtn');
            submitBtn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Guardando...');
            
            setTimeout(() => {
                submitBtn.prop('disabled', false).html('<i class="fas fa-save"></i> Guardar Paciente');
            }, 10000);
        });

        // Cargar datos si hay valores antiguos
        @if(old('state_id'))
            console.log('Cargando datos antiguos...');
            loadMunicipalities({{ old('state_id') }});
            
            @if(old('municipality_id'))
            setTimeout(() => {
                $('#municipality_id').val({{ old('municipality_id') }}).prop('disabled', false);
                loadHealthCenters({{ old('municipality_id') }});
            }, 1000);
            @endif

            @if(old('health_center_id'))
            setTimeout(() => {
                $('#health_center_id').val({{ old('health_center_id') }}).prop('disabled', false);
                loadCommunities({{ old('health_center_id') }});
            }, 1500);
            @endif

            @if(old('community_id'))
            setTimeout(() => {
                $('#community_id').val({{ old('community_id') }}).prop('disabled', false);
                loadStreets({{ old('community_id') }});
            }, 2000);
            @endif

            @if(old('street_id'))
            setTimeout(() => {
                $('#street_id').val({{ old('street_id') }}).prop('disabled', false);
                loadHouses({{ old('street_id') }});
            }, 2500);
            @endif

            @if(old('house_id'))
            setTimeout(() => {
                $('#house_id').val({{ old('house_id') }}).prop('disabled', false);
                updateAddress({{ old('house_id') }});
            }, 3000);
            @endif
        @endif

        // Inicializar tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();

        console.log('Script de pacientes inicializado correctamente');
    });
}

// Iniciar la verificación de jQuery
checkJQuery();
</script>
@endpush