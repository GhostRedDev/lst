<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Municipality extends Model
{
    use HasFactory;

    protected $fillable = ['state_id', 'name', 'code'];

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function healthCenters()
    {
        return $this->hasMany(HealthCenter::class);
    }

    public function communities()
    {
        return $this->hasManyThrough(Community::class, HealthCenter::class);
    }

    public function getPatientsCountAttribute()
    {
        return Patient::whereHas('house.street.community.healthCenter', function($query) {
            $query->where('municipality_id', $this->id);
        })->count();
    }
}