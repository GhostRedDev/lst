<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code'];

    public function municipalities()
    {
        return $this->hasMany(Municipality::class);
    }

    public function healthCenters()
    {
        return $this->hasManyThrough(HealthCenter::class, Municipality::class);
    }

    public function communities()
    {
        return $this->hasManyThrough(Community::class, HealthCenter::class);
    }

    public function getPatientsCountAttribute()
    {
        return Patient::whereHas('house.street.community.healthCenter.municipality', function($query) {
            $query->where('state_id', $this->id);
        })->count();
    }
}