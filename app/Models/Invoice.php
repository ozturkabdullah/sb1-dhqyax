<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'rental_id',
        'invoice_number',
        'company_name',
        'tax_number',
        'tax_office',
        'address',
        'city',
        'district',
        'phone',
        'amount'
    ];

    public function rental()
    {
        return $this->belongsTo(Rental::class);
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($invoice) {
            $invoice->invoice_number = 'INV-' . date('Y') . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
        });
    }
}