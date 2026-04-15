<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = [
        'patient_id',
        'treatment_plan',
        'tooth_number',
        'amount'
    ];

    // ✅ علاقة مع Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
