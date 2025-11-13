<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IntraoralExamination extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'soft_tissues_status',
        'soft_tissues_notes',
        'gingiva_color',
        'gingiva_texture',
        'bleeding_on_probing',
        'bleeding_areas',
        'recession',
        'recession_areas',
        'probing_depths_file',
        'mobility_file',
        'furcation_file',
        'hard_tissues_notes',
        'odontogram',
        'occlusion_class',
        'occlusion_details',
        'premature_contacts',
        'oral_hygiene_status',
        'plaque_index',
        'calculus',
        'mio', 
        'notes',
    ];

    protected $casts = [
        'bleeding_on_probing' => 'boolean',
        'recession' => 'boolean',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}