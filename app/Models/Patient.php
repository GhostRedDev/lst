<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'house_id', 
        'history_number', 
        'code', 
        'last_names', 
        'names', 
        'address',
        'dispensary_group', 
        'housing_type', 
        'risk_factors', 
        'birth_date', 
        'age',
        'gender', 
        'id_card', 
        'phone', 
        'next_appointment', 
        'diagnosis', 
        'education',
        'occupation', 
        'profession', 
        'disability', 
        'classification', 
        'observation', 
        'is_annexed'
    ];

    protected $casts = [
        'birth_date' => 'date',
        'next_appointment' => 'date',
        'risk_factors' => 'array',
        'is_annexed' => 'boolean'
    ];

    protected $appends = [
        'full_name',
        'current_age',
        'is_pregnant',
        'is_child'
    ];

    // ==================== RELACIONES PRINCIPALES ====================

    /**
     * Relación con la casa donde vive el paciente
     */
    public function house()
    {
        return $this->belongsTo(House::class);
    }

    /**
     * Relación directa con la calle a través de la casa
     */
    public function street()
    {
        return $this->hasOneThrough(
            Street::class, 
            House::class, 
            'id',           // Foreign key on houses table
            'id',           // Foreign key on streets table  
            'house_id',     // Local key on patients table
            'street_id'     // Local key on houses table
        );
    }

    /**
     * Relación directa con la comunidad a través de la calle
     */
    public function community()
    {
        return $this->hasOneThrough(
            Community::class,
            Street::class,
            'id',           // Foreign key on streets table
            'id',           // Foreign key on communities table
            'house_id',     // Local key on patients table
            'community_id'  // Local key on streets table
        )->via('street');
    }

    /**
     * Relación directa con el centro de salud a través de la comunidad
     */
    public function healthCenter()
    {
        return $this->hasOneThrough(
            HealthCenter::class,
            Community::class,
            'id',               // Foreign key on communities table
            'id',               // Foreign key on health_centers table
            'house_id',         // Local key on patients table
            'health_center_id'  // Local key on communities table
        )->via('community');
    }

    /**
     * Relación directa con el municipio a través del centro de salud
     */
    public function municipality()
    {
        return $this->hasOneThrough(
            Municipality::class,
            HealthCenter::class,
            'id',               // Foreign key on health_centers table
            'id',               // Foreign key on municipalities table
            'house_id',         // Local key on patients table
            'municipality_id'   // Local key on health_centers table
        )->via('healthCenter');
    }

    /**
     * Relación directa con el estado a través del municipio
     */
    public function state()
    {
        return $this->hasOneThrough(
            State::class,
            Municipality::class,
            'id',           // Foreign key on municipalities table
            'id',           // Foreign key on states table
            'house_id',     // Local key on patients table
            'state_id'      // Local key on municipalities table
        )->via('municipality');
    }

    // ==================== MÓDULOS ESPECIALIZADOS ====================

    /**
     * Controles de embarazo
     */
    public function pregnancies()
    {
        return $this->hasMany(Pregnancy::class);
    }

    /**
     * Controles de niño sano
     */
    public function childHealths()
    {
        return $this->hasMany(ChildHealth::class);
    }

    /**
     * Visitas domiciliarias
     */
    public function homeVisits()
    {
        return $this->hasMany(HomeVisit::class);
    }

    /**
     * Registros de vacunación
     */
    public function vaccinations()
    {
        return $this->hasMany(Vaccination::class);
    }

    // ==================== SCOPES DE BÚSQUEDA ====================

    /**
     * Scope para filtrar por comunidad
     */
    public function scopeByCommunity($query, $communityId)
    {
        return $query->whereHas('house.street', function($q) use ($communityId) {
            $q->where('community_id', $communityId);
        });
    }

    /**
     * Scope para filtrar por calle
     */
    public function scopeByStreet($query, $streetId)
    {
        return $query->whereHas('house', function($q) use ($streetId) {
            $q->where('street_id', $streetId);
        });
    }

    /**
     * Scope para filtrar por casa específica
     */
    public function scopeByHouse($query, $houseId)
    {
        return $query->where('house_id', $houseId);
    }

    /**
     * Scope para pacientes anexados
     */
    public function scopeAnnexed($query)
    {
        return $query->where('is_annexed', true);
    }

    /**
     * Scope para pacientes gestantes activas
     */
    public function scopePregnant($query)
    {
        return $query->whereHas('pregnancies', function($q) {
            $q->where('active', true);
        });
    }

    /**
     * Scope para niños (menores de 12 años)
     */
    public function scopeChildren($query)
    {
        return $query->where('age', '<', 12)
                    ->orWhere('classification', 'NIÑO');
    }

    /**
     * Scope para pacientes con discapacidad
     */
    public function scopeWithDisability($query)
    {
        return $query->where('disability', 'SI');
    }

    /**
     * Scope para búsqueda general
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function($q) use ($search) {
            $q->where('history_number', 'LIKE', "%{$search}%")
              ->orWhere('last_names', 'LIKE', "%{$search}%")
              ->orWhere('names', 'LIKE', "%{$search}%")
              ->orWhere('id_card', 'LIKE', "%{$search}%")
              ->orWhere('phone', 'LIKE', "%{$search}%")
              ->orWhereRaw("CONCAT(last_names, ' ', names) LIKE ?", ["%{$search}%"]);
        });
    }

    // ==================== ATRIBUTOS CALCULADOS ====================

    /**
     * Nombre completo del paciente
     */
    public function getFullNameAttribute()
    {
        return "{$this->last_names} {$this->names}";
    }

    /**
     * Edad actual calculada automáticamente
     */
    public function getCurrentAgeAttribute()
    {
        return $this->birth_date ? now()->diffInYears($this->birth_date) : $this->age;
    }

    /**
     * Verifica si el paciente está embarazada
     */
    public function getIsPregnantAttribute()
    {
        return $this->pregnancies()->where('active', true)->exists();
    }

    /**
     * Verifica si el paciente es niño
     */
    public function getIsChildAttribute()
    {
        return $this->current_age < 12 || $this->classification === 'NIÑO';
    }

    /**
     * Dirección completa formateada
     */
    public function getFullAddressAttribute()
    {
        if ($this->house && $this->house->street && $this->house->street->community) {
            return "{$this->house->full_address}";
        }
        
        return $this->address ?? 'Dirección no especificada';
    }

    /**
     * Estado de la próxima cita
     */
    public function getAppointmentStatusAttribute()
    {
        if (!$this->next_appointment) {
            return 'Sin cita';
        }

        $today = now();
        $appointmentDate = $this->next_appointment;

        if ($appointmentDate->isPast()) {
            return 'Vencida';
        } elseif ($appointmentDate->isToday()) {
            return 'Hoy';
        } elseif ($appointmentDate->diffInDays($today) <= 7) {
            return 'Próxima';
        } else {
            return 'Programada';
        }
    }

    // ==================== MÉTODOS DE UTILIDAD ====================

    /**
     * Obtener la ubicación jerárquica completa
     */
    public function getLocationHierarchy()
    {
        return [
            'house' => $this->house ? $this->house->house_number : 'N/A',
            'street' => $this->street ? $this->street->name : 'N/A',
            'community' => $this->community ? $this->community->name : 'N/A',
            'health_center' => $this->healthCenter ? $this->healthCenter->name : 'N/A',
            'municipality' => $this->municipality ? $this->municipality->name : 'N/A',
            'state' => $this->state ? $this->state->name : 'N/A',
        ];
    }

    /**
     * Verificar si tiene controles pendientes
     */
    public function hasPendingControls()
    {
        return $this->next_appointment && $this->next_appointment->isFuture() ||
               $this->is_pregnant ||
               ($this->is_child && $this->childHealths()->count() > 0);
    }

    /**
     * Obtener el último control de niño sano
     */
    public function getLastChildHealthControl()
    {
        return $this->childHealths()->latest()->first();
    }

    /**
     * Obtener el embarazo activo
     */
    public function getActivePregnancy()
    {
        return $this->pregnancies()->where('active', true)->first();
    }

    // ==================== EVENTOS DEL MODELO ====================

    protected static function boot()
    {
        parent::boot();

        // Calcular edad automáticamente antes de guardar
        static::saving(function ($patient) {
            if ($patient->birth_date && !$patient->age) {
                $patient->age = now()->diffInYears($patient->birth_date);
            }
        });

        // Actualizar contador de residentes en la casa
        static::saved(function ($patient) {
            if ($patient->house) {
                $patient->house->update([
                    'total_residents' => $patient->house->patients()->count()
                ]);
            }
        });

        static::deleted(function ($patient) {
            if ($patient->house) {
                $patient->house->update([
                    'total_residents' => $patient->house->patients()->count()
                ]);
            }
        });
    }

    // ==================== VALIDACIONES PERSONALIZADAS ====================

    /**
     * Validar que el paciente sea mayor de edad para ciertos procedimientos
     */
    public function isAdult()
    {
        return $this->current_age >= 18;
    }

    /**
     * Validar si puede recibir controles de gestante
     */
    public function canHavePregnancyControl()
    {
        return $this->gender === 'F' && $this->current_age >= 12 && $this->current_age <= 50;
    }

    /**
     * Validar si puede recibir controles de niño sano
     */
    public function canHaveChildHealthControl()
    {
        return $this->current_age < 12;
    }
}