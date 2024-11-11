<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Str;

class PageCacheService
{
    // Önbellek süreleri (dakika)
    const CACHE_DURATION = [
        'home' => 60,      // Ana sayfa
        'category' => 30,  // Kategori sayfaları
        'post' => 120,     // Blog yazıları
        'page' => 1440,    // Statik sayfalar
    ];

    /**
     * Sayfa içeriğini önbellekten al veya oluştur
     */
    public static function remember(string $type, $callback)
    {
        $key = self::getCacheKey();
        $duration = self::CACHE_DURATION[$type] ?? 30;

        // Yönetici girişi yapmış kullanıcılar için önbelleği devre dışı bırak
        if (auth()->check() && auth()->user()->hasRole('admin')) {
            return $callback();
        }

        return Cache::remember($key, now()->addMinutes($duration), $callback);
    }

    /**
     * Önbellek anahtarı oluştur
     */
    protected static function getCacheKey(): string
    {
        $url = Request::url();
        $queryParams = Request::query();
        $locale = app()->getLocale();
        
        // Query parametrelerini sırala
        ksort($queryParams);
        
        $queryString = http_build_query($queryParams);
        
        return 'page.' . $locale . '.' . md5($url . $queryString);
    }

    /**
     * Belirli bir sayfanın önbelleğini temizle
     */
    public static function forget(string $url): void
    {
        $pattern = 'page.*.' . md5($url . '*');
        $keys = Cache::get('cache_keys.' . $pattern, []);
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Tüm sayfa önbelleklerini temizle
     */
    public static function flush(): void
    {
        $pattern = 'page.*';
        $keys = Cache::get('cache_keys.' . $pattern, []);
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Kategori sayfalarının önbelleğini temizle
     */
    public static function flushCategory(int $categoryId): void
    {
        $pattern = 'page.*.category.' . $categoryId;
        $keys = Cache::get('cache_keys.' . $pattern, []);
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Blog yazılarının önbelleğini temizle
     */
    public static function flushPost(int $postId): void
    {
        $pattern = 'page.*.post.' . $postId;
        $keys = Cache::get('cache_keys.' . $pattern, []);
        
        foreach ($keys as $key) {
            Cache::forget($key);
        }
    }
}