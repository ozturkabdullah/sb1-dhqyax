<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
        'group'
    ];

    public static function get($key, $default = null)
    {
        $setting = Cache::rememberForever('setting.' . $key, function () use ($key) {
            return static::where('key', $key)->first();
        });

        return $setting ? $setting->value : $default;
    }

    public static function set($key, $value, $group = 'general')
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value, 'group' => $group]
        );

        Cache::forget('setting.' . $key);
        
        return $setting;
    }

    protected static function boot()
    {
        parent::boot();

        static::saved(function ($setting) {
            Cache::forget('setting.' . $setting->key);
        });

        static::deleted(function ($setting) {
            Cache::forget('setting.' . $setting->key);
        });
    }
}