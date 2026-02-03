<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected $fillable = [
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
}
