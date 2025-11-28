<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Street extends Model
{
    use HasFactory;

    protected $fillable = ['community_id', 'name', 'description'];

    public function community()
    {
        return $this->belongsTo(Community::class);
    }

    public function houses()
    {
        return $this->hasMany(House::class);
    }

    public function patients()
    {
        return $this->hasManyThrough(Patient::class, House::class);
    }

    public function getTotalResidentsAttribute()
    {
        return $this->patients()->count();
    }

    // Contador de casas
    public function getHousesCountAttribute()
    {
        return $this->houses()->count();
    }

    // Contador de pacientes
    public function getPatientsCountAttribute()
    {
        return $this->patients()->count();
    }
}