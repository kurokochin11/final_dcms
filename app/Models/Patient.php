<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany; 
class Patient extends Model
{
    protected $fillable = [
        'date_registered',
        'last_name',
        'first_name',
        'middle_name',
          'age',
        'date_of_birth',
        'sex',
        'civil_status',
        'nationality',
        'religion',
        'occupation',
        'address',
        'city',
        'province',
        'zip_code',
        'mobile_number',
        'landline_number',
        'email',
        'referred_by',
    ];

    protected $casts = [
        'date_registered' => 'date',
        'date_of_birth' => 'date',
    ];

    public function emergencyContact(): HasOne
    {
        return $this->hasOne(EmergencyContact::class, 'patient_id', 'id');
    }
    
    public function medicalAnswers(): HasMany
{
    
    return $this->hasMany(PatientResponse::class,'patient_id', 'id');
}

    public function checkupAnswers(): HasMany
{
    
    return $this->hasMany(CheckupResult::class,'patient_id', 'id');

}
 // radiographs relation
    public function radiographs(): HasMany
    {
        return $this->hasMany(Radiograph::class, 'patient_id', 'id');
    }

    
    public function fullName(): string
    {
        return trim($this->first_name . ' ' . ($this->middle_name ? $this->middle_name . ' ' : '') . $this->last_name);
    }
    // Patient.php
public function getFullNameAttribute()
{
    return $this->fullName(); 
}

}