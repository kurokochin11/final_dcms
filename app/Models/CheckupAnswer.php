<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckupAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'question',
        'answer',
    ];

    // Each answer belongs to a patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
