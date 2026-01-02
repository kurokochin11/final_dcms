<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
    ];

    /* ==============================
     | Relationships
     |==============================
     */

    // 🔹 A medical session belongs to ONE patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // 🔹 A medical session has MANY medical answers (patient_responses)
    public function responses()
    {
        return $this->hasMany(PatientResponse::class, 'medical_session_id');
    }
    // ✅ THIS IS REQUIRED
    public function medicalAnswers()
    {
        return $this->hasMany(PatientResponse::class, 'medical_session_id');
}
}