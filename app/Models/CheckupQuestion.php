<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CheckupQuestion extends Model
{
    use HasFactory;

    // This table stores static question data, not user answers.
    protected $table = 'checkup_questions';

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
public function check()
{
    // A CheckupQuestion has many CheckupResults
    return $this->hasMany(CheckupResults::class, 'checkup_question_id', 'id');
}
}