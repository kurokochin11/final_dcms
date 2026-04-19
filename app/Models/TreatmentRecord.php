<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TreatmentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'date',
        'treatment',
        'tooth_number',
        'amount'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}