<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Radiograph extends Model
{
    protected $fillable = [
        'patient_id', 
        'date_taken', 
        'type',
        'image_path', 
        'findings',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    // Optional accessor for easier display:
    public function getPatientNameAttribute()
    {
        if (!$this->patient) {
            return 'Unknown';
        }

        return $this->patient->last_name . ', ' . $this->patient->first_name;
    }

    
}