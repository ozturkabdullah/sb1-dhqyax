<?php

namespace App\Observers;

use App\Models\Category;
use App\Services\PageCacheService;

class CategoryObserver
{
    public function saved(Category $category)
    {
        // Ana sayfa önbelleğini temizle
        PageCacheService::forget(route('home'));
        
        // Kategori sayfası önbelleğini temizle
        PageCacheService::flushCategory($category->id);

        // Üst kategori varsa onun önbelleğini de temizle
        if ($category->parent_id) {
            PageCacheService::flushCategory($category->parent_id);
        }
    }

    public function deleted(Category $category)
    {
        // Ana sayfa önbelleğini temizle
        PageCacheService::forget(route('home'));
        
        // Kategori sayfası önbelleğini temizle
        PageCacheService::flushCategory($category->id);

        // Üst kategori varsa onun önbelleğini de temizle
        if ($category->parent_id) {
            PageCacheService::flushCategory($category->parent_id);
        }
    }
}