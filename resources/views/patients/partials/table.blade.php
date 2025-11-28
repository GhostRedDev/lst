@if($patients->count() > 0)
    <table class="table table-hover table-striped">
        <thead class="table-dark">
            <tr>
                <th>N° Historia</th>
                <th>Apellidos y Nombres</th>
                <th>Cédula</th>
                <th>Edad</th>
                <th>Sexo</th>
                <th>Ubicación</th>
                <th>Clasificación</th>
                <th>Próxima Cita</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($patients as $patient)
            <tr>
                <td>
                    <strong class="text-primary">{{ $patient->history_number }}</strong>
                    @if($patient->is_annexed)
                        <span class="badge bg-warning badge-sm" data-bs-toggle="tooltip" title="Paciente Anexado">
                            <i class="fas fa-home"></i>
                        </span>
                    @endif
                    @if($patient->disability == 'SI')
                        <span class="badge bg-danger badge-sm" data-bs-toggle="tooltip" title="Con Discapacidad">
                            <i class="fas fa-wheelchair"></i>
                        </span>
                    @endif
                </td>
                <td>
                    <div class="fw-bold">{{ $patient->last_names }} {{ $patient->names }}</div>
                    @if($patient->phone)
                        <small class="text-muted">
                            <i class="fas fa-phone"></i> {{ $patient->phone }}
                        </small>
                    @endif
                </td>
                <td>{{ $patient->id_card }}</td>
                <td>
                    <span class="badge bg-info">{{ $patient->age }} años</span>
                </td>
                <td>
                    @if($patient->gender == 'M')
                        <span class="badge bg-primary">M</span>
                    @else
                        <span class="badge bg-pink">F</span>
                    @endif
                </td>
                <td>
                    <small class="text-muted">
                        @if($patient->house && $patient->house->street && $patient->house->street->community)
                            {{ $patient->house->street->community->name }}
                        @else
                            <span class="text-warning">Sin ubicación</span>
                        @endif
                    </small>
                </td>
                <td>
                    @php
                        $classificationColors = [
                            'AGUDO' => 'bg-success',
                            'CRONICO' => 'bg-warning',
                            'DISCAPACITADO' => 'bg-danger',
                            'GESTANTE' => 'bg-pink',
                            'NIÑO' => 'bg-info',
                            'ADULTO' => 'bg-primary',
                            'ADULTO_MAYOR' => 'bg-secondary'
                        ];
                        $color = $classificationColors[$patient->classification] ?? 'bg-secondary';
                    @endphp
                    <span class="badge {{ $color }}">{{ $patient->classification }}</span>
                </td>
                <td>
                    @if($patient->next_appointment)
                        @php
                            $appointmentDate = \Carbon\Carbon::parse($patient->next_appointment);
                            $today = \Carbon\Carbon::today();
                            $diffDays = $today->diffInDays($appointmentDate, false);
                            
                            if ($diffDays < 0) {
                                $status = 'Vencida';
                                $badgeClass = 'bg-danger';
                            } elseif ($diffDays == 0) {
                                $status = 'Hoy';
                                $badgeClass = 'bg-warning';
                            } elseif ($diffDays <= 7) {
                                $status = 'Próxima';
                                $badgeClass = 'bg-info';
                            } else {
                                $status = 'Programada';
                                $badgeClass = 'bg-success';
                            }
                        @endphp
                        <span class="badge {{ $badgeClass }}" data-bs-toggle="tooltip" title="{{ $appointmentDate->format('d/m/Y') }}">
                            {{ $status }}
                        </span>
                    @else
                        <span class="badge bg-secondary">Sin cita</span>
                    @endif
                </td>
                <td>
                    <div class="btn-group btn-group-sm" role="group">
                        <!-- Ver -->
                        <button type="button" class="btn btn-info view-patient" 
                                data-id="{{ $patient->id }}" 
                                data-bs-toggle="tooltip" title="Ver detalles">
                            <i class="fas fa-eye"></i>
                        </button>
                        
                        <!-- Editar -->
                        <a href="{{ route('patients.edit', $patient->id) }}" class="btn btn-warning"
                           data-bs-toggle="tooltip" title="Editar paciente">
                            <i class="fas fa-edit"></i>
                        </a>
                        
                        <!-- Módulos Especializados -->
                        @if($patient->classification == 'GESTANTE' || $patient->gender == 'F')
                            <a href="{{ route('pregnancies.index') }}?patient_id={{ $patient->id }}" 
                               class="btn btn-success" data-bs-toggle="tooltip" title="Control de Gestante">
                                <i class="fas fa-baby"></i>
                            </a>
                        @endif
                        
                        @if($patient->classification == 'NIÑO' || $patient->age < 12)
                            <a href="{{ route('child-health.index') }}?patient_id={{ $patient->id }}" 
                               class="btn btn-info" data-bs-toggle="tooltip" title="Control Niño Sano">
                                <i class="fas fa-child"></i>
                            </a>
                        @endif
                        
                        <!-- Visita Domiciliaria -->
                        <a href="{{ route('home-visits.index') }}?patient_id={{ $patient->id }}" 
                           class="btn btn-teal" data-bs-toggle="tooltip" title="Visita Domiciliaria">
                            <i class="fas fa-home"></i>
                        </a>
                        
                        <!-- Eliminar -->
                        <button type="button" class="btn btn-danger delete-patient" 
                                data-id="{{ $patient->id }}"
                                data-name="{{ $patient->last_names }} {{ $patient->names }}"
                                data-bs-toggle="tooltip" title="Eliminar paciente">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="d-flex justify-content-between align-items-center mt-3">
        <div class="text-muted">
            Mostrando {{ $patients->firstItem() }} - {{ $patients->lastItem() }} de {{ $patients->total() }} registros
        </div>
        <div>
            {{ $patients->links() }}
        </div>
    </div>
@else
    <div class="text-center py-5">
        <div class="empty-state">
            <i class="fas fa-users fa-4x text-muted mb-3"></i>
            <h4 class="text-muted">No se encontraron pacientes</h4>
            <p class="text-muted">No hay pacientes registrados que coincidan con los criterios de búsqueda.</p>
            <a href="{{ route('patients.create') }}" class="btn btn-primary">
                <i class="fas fa-plus me-2"></i>Registrar Primer Paciente
            </a>
        </div>
    </div>
@endif