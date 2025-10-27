<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExtraoralExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'facial_symmetry',
        'facial_symmetry_notes',
        'lymph_nodes',
        'lymph_nodes_location',
        'tmj_pain',
        'tmj_clicking',
        'tmj_limited_opening',
        'mio',
        'notes',
    ];

    protected $casts = [
        'tmj_pain' => 'boolean',
        'tmj_clicking' => 'boolean',
        'tmj_limited_opening' => 'boolean',
        'mio' => 'integer',
    ];

    public function patient()
    {
        return $this->belongsTo(\App\Models\Patient::class);
    }
}
