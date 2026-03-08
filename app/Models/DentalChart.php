<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DentalChart extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'tooth_data',
        'occlusion',
        'periodontal_condition',
        'oral_hygiene',
        'abnormalities',
        'general_condition',
        'nature_of_treatment',
        'allergies',
        'blood_pressure',
        'drugs_taken',
    ];

    /**
     * The attributes that should be cast.
     * This automatically turns the JSON in the DB into an array in PHP.
     */
    protected $casts = [
        'tooth_data' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Get the patient that owns the dental chart session.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}