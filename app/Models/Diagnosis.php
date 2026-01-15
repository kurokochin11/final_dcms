<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Diagnosis extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'dental_caries',
        'periodontal_disease',
        'pulpal_periapical',
        'occlusal_diagnosis',
        'other_oral_conditions',
          'diagnosis_date', 
    ];

    /**
     * Get the patient that owns the diagnosis.
     */
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
