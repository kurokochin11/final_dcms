<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreatmentPlan extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'phases',
        'treatment_options',
        'risks_and_benefits',
        'alternatives',
        'estimated_costs',
        'payment_options',
        'consent_given',
        'patient_signature',
        'dentist_signature',
        'consent_date',
    ];

    protected $casts = [
        'phases' => 'array',
        'consent_given' => 'boolean',
        'consent_date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class);
    }
}
