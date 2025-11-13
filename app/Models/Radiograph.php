<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Radiograph extends Model
{
    protected $fillable = [
        'patient_name', 'date_taken', 'type', 'image_path', 'findings',
    ];
}
