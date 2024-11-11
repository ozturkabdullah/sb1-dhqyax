<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserStatistic extends Model
{
    protected $fillable = [
        'user_id',
        'action_type',
        'page_url',
        'device_type',
        'browser',
        'platform',
        'ip_address',
        'additional_data'
    ];

    protected $casts = [
        'additional_data' => 'array'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeLastMonth($query)
    {
        return $query->where('created_at', '>=', now()->subMonth());
    }

    public function scopeLastWeek($query)
    {
        return $query->where('created_at', '>=', now()->subWeek());
    }
}