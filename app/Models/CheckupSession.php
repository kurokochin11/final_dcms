<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckupSession extends Model
{
    use HasFactory;

    // ✅ Allow mass assignment for patient_id
    protected $fillable = [
        'patient_id',
    ];

    // ---------------- Relationships ----------------

    // ✅ Link session to patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // ✅ Link session to all answers submitted in this session
    public function checkupResults()
    {
        return $this->hasMany(CheckupResult::class);
    }
}
