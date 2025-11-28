<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HomeVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'visit_date',
        'visit_type',
        'findings',
        'recommendations',
        'visited_by',
        'next_visit_date',
        'medications_prescribed',
        'vital_signs',
        'needs_referral',
        'referral_notes',
        'follow_up_required'
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit_date' => 'date',
        'medications_prescribed' => 'array',
        'vital_signs' => 'array',
        'needs_referral' => 'boolean',
        'follow_up_required' => 'boolean'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('visit_type', $type);
    }

    public function scopeByVisitor($query, $visitor)
    {
        return $query->where('visited_by', 'LIKE', "%{$visitor}%");
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('next_visit_date', '>=', now())
                    ->whereDate('next_visit_date', '<=', now()->addDays(30));
    }

    public function scopeToday($query)
    {
        return $query->whereDate('visit_date', today());
    }
}