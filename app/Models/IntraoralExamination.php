<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntraoralExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'soft_tissues',
        'gingiva_color',
        'gingiva_texture',
        'bleeding',
        'bleeding_area',
        'recession',
        'recession_area',
        'probing_depths',
        'mobility',
        'furcation',
        'odontogram',
        'teeth_condition',
        'occlusion_class',
        'occlusion_other',
        'premature_contacts',
        'hygiene_status',
        'plaque_index',
        'calculus',
    ];

    // Relation to Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
    public function getFullNameAttribute()
{
    // if you have first_name/last_name use them, otherwise use a single name field
    $first = $this->first_name ?? $this->name ?? '';
    $last  = $this->last_name ?? '';
    return trim($first . ' ' . $last);
}
}
