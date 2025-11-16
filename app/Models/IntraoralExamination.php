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
        'soft_tissues_status',
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

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function getFullNameAttribute()
    {
        $first = $this->first_name ?? $this->name ?? '';
        $last  = $this->last_name ?? '';
        return trim($first . ' ' . $last);
    }
}
