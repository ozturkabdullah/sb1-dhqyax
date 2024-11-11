<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;

class CacheService
{
    // Cache süreleri (dakika)
    const CACHE_SHORT = 5;
    const CACHE_MEDIUM = 60;
    const CACHE_LONG = 1440; // 24 saat
    const CACHE_VERY_LONG = 10080; // 1 hafta

    /**
     * Cache'den veri al veya yoksa callback'i çalıştır
     */
    public static function remember(string $key, $callback, int $minutes = self::CACHE_MEDIUM)
    {
        return Cache::remember($key, Carbon::now()->addMinutes($minutes), $callback);
    }

    /**
     * Cache'den veri al veya yoksa callback'i çalıştır (sonsuza kadar)
     */
    public static function rememberForever(string $key, $callback)
    {
        return Cache::rememberForever($key, $callback);
    }

    /**
     * Cache'i temizle
     */
    public static function forget(string $key)
    {
        return Cache::forget($key);
    }

    /**
     * Belirli bir tag'e ait tüm cache'leri temizle
     */
    public static function forgetByTag(string $tag)
    {
        return Cache::tags($tag)->flush();
    }

    /**
     * Cache'de veri var mı kontrol et
     */
    public static function has(string $key): bool
    {
        return Cache::has($key);
    }
}