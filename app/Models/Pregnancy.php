<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pregnancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'last_menstrual_period',
        'estimated_delivery',
        'prenatal_controls',
        'risk_factors',
        'current_week',
        'active',
        'notes'
    ];

    protected $casts = [
        'last_menstrual_period' => 'date',
        'estimated_delivery' => 'date',
        'risk_factors' => 'array',
        'active' => 'boolean'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getCurrentWeekAttribute()
    {
        if (!$this->last_menstrual_period) return null;
        
        return now()->diffInWeeks($this->last_menstrual_period);
    }
}