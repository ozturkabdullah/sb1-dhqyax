<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'rental_id',
        'payment_method',
        'transaction_id',
        'amount',
        'status',
        'payment_details'
    ];

    protected $casts = [
        'payment_details' => 'array'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }
}