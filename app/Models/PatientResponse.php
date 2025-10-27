<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientResponse extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'patient_responses';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patient_id',
        'medical_question_id',
        'answer_value',
    ];

    // ------------------- Relationships -------------------

    /**
     * Get the patient who owns this response.
     */
    public function patient(): BelongsTo
    {
        // Links 'patient_id' in this table to 'id' in the 'patients' table.
        return $this->belongsTo(Patient::class);
    }

    /**
     * Get the medical question this response is answering.
     */
    public function question(): BelongsTo
    {
       
        return $this->belongsTo(MedicalQuestion::class, 'medical_question_id', 'id');
        
    }
}