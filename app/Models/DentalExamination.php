<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DentalExamination extends Model {
    protected $fillable = [
        'patient_id', 'occlusion', 'periodontal_condition', 'oral_hygiene',
        'abnormalities', 'general_condition', 'physician', 'nature_of_treatment',
        'allergies', 'previous_bleeding', 'chronic_ailments', 'blood_pressure',
        'drugs_taken', 'tooth_data'
    ];

    protected $casts = [
        'tooth_data' => 'array', // Automatically handles JSON to Array conversion
    ];
    
    public function patient()
{
    return $this->belongsTo(Patient::class);
}
}
