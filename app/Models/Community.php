<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Community extends Model
{
    use HasFactory;

    protected $fillable = [
        'health_center_id', // Asegúrate de incluir este campo
        'name', 
        'sector', 
        'description',
        'latitude',
        'longitude'
    ];

    // 🔥 RELACIÓN FALTANTE - Agrega esto
    public function healthCenter()
    {
        return $this->belongsTo(HealthCenter::class);
    }

    public function streets()
    {
        return $this->hasMany(Street::class);
    }

    public function houses()
    {
        return $this->hasManyThrough(House::class, Street::class);
    }

    // Relación directa con pacientes a través de houses y streets
    public function patients()
    {
        return $this->hasManyThrough(
            Patient::class,
            House::class,
            'street_id', // Foreign key on houses table (points to streets)
            'house_id',  // Foreign key on patients table (points to houses)
            'id',        // Local key on communities table
            'id'         // Local key on houses table
        )->join('streets', 'houses.street_id', '=', 'streets.id');
    }

    // Método simple para contar pacientes
    public function getPatientsCountAttribute()
    {
        return Patient::whereHas('house.street', function($query) {
            $query->where('community_id', $this->id);
        })->count();
    }

    public function getTotalResidentsAttribute()
    {
        return $this->getPatientsCountAttribute();
    }
}