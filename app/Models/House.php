<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class House extends Model
{
    use HasFactory;

    protected $fillable = ['street_id', 'house_number', 'description', 'total_residents'];

    public function street()
    {
        return $this->belongsTo(Street::class);
    }

    public function community()
    {
        return $this->hasOneThrough(
            Community::class, 
            Street::class,
            'id', // Foreign key on streets table
            'id', // Foreign key on communities table
            'street_id', // Local key on houses table
            'community_id' // Local key on streets table
        );
    }

    public function patients()
    {
        return $this->hasMany(Patient::class);
    }

    public function getFullAddressAttribute()
    {
        if ($this->street && $this->street->community) {
            return "Casa {$this->house_number}, {$this->street->name}, {$this->street->community->name}";
        }
        return "Casa {$this->house_number}";
    }

    // Contador de pacientes
    public function getPatientsCountAttribute()
    {
        return $this->patients()->count();
    }

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($house) {
            // Actualizar el contador de residentes
            $house->total_residents = $house->patients()->count();
        });
    }
}