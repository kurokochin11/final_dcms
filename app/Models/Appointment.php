<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_id',
        'created_by',
        'appointment_date',
        'status',
        'notes',
        'rescheduled_at', 
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
        'rescheduled_at'   => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
