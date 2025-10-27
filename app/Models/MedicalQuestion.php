<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MedicalQuestion extends Model
{
    use HasFactory;

    // This table stores static question data, not user answers.
    protected $table = 'medical_questions';

    // 'id' is the primary key (1-40)
    protected $primaryKey = 'id'; 

    // The ID is manually managed, not auto-incrementing
    public $incrementing = false; 

    // The ID is an integer type
    protected $keyType = 'int'; 

    // Define which fields can be mass-assigned (optional, since this data is static)
    protected $fillable = [
        'id', 'question_set', 'question_text', 'input_type', 'placeholder_text', 'notes'
    ];
    
    // In the MedicalQuestion model
public function responses()
{
    // A MedicalQuestion has many PatientResponses
    return $this->hasMany(PatientResponse::class, 'medical_question_id', 'id');
}
}