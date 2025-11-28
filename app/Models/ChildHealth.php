<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChildHealth extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'weight',
        'height', 
        'head_circumference',
        'bmi',
        'development_notes',
        'vaccination_status',
        'nutritional_status',
        'next_control_date',
        'observations'
    ];

    protected $casts = [
        'weight' => 'decimal:2',
        'height' => 'decimal:2',
        'head_circumference' => 'decimal:2',
        'bmi' => 'decimal:2',
        'next_control_date' => 'date',
        'vaccination_status' => 'array'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scopes
    public function scopeByNutritionalStatus($query, $status)
    {
        return $query->where('nutritional_status', $status);
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('next_control_date', '>=', now())
                    ->whereDate('next_control_date', '<=', now()->addDays(30));
    }
}