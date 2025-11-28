<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'municipality_id', 
        'name', 
        'type', 
        'address', 
        'phone',
        'latitude',
        'longitude'
    ];

    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8'
    ];

    public function municipality()
    {
        return $this->belongsTo(Municipality::class);
    }

    public function communities()
    {
        return $this->hasMany(Community::class);
    }

    public function patients()
    {
        return $this->hasManyThrough(
            Patient::class,
            Community::class,
            'health_center_id',
            'house_id',
            'id',
            'id'
        )->join('streets', 'communities.id', '=', 'streets.community_id')
         ->join('houses', 'streets.id', '=', 'houses.street_id');
    }

    public function getPatientsCountAttribute()
    {
        return Patient::whereHas('house.street.community', function($query) {
            $query->where('health_center_id', $this->id);
        })->count();
    }

    // Scope para filtrar por tipo
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }
}