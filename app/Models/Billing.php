<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
        'patient_id',
        'date',
        'service_rendered',
        'amount',
        'payment_method',
        'receipt_no',
        'outstanding_balance',
    ];

    protected $casts = [
        'date' => 'date',
    ];
    
    public function patient()
{
    return $this->belongsTo(Patient::class);
}
}
