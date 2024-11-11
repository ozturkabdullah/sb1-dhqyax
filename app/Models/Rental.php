<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Rental extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'category_id',
        'start_date',
        'end_date',
        'total_amount',
        'status'
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function invoice()
    {
        return $this->hasOne(Invoice::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function getRemainingDaysAttribute()
    {
        if ($this->status !== 'active') {
            return 0;
        }
        return now()->diffInDays($this->end_date, false);
    }

    public function calculateTotalAmount()
    {
        $days = $this->start_date->diffInDays($this->end_date);
        return $this->category->daily_rate * $days;
    }
}