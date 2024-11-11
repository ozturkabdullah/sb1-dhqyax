<?php

namespace App\Observers;

use App\Models\Post;
use App\Services\PageCacheService;

class PostObserver
{
    public function saved(Post $post)
    {
        // Ana sayfa önbelleğini temizle
        PageCacheService::forget(route('home'));
        
        // Blog yazısı önbelleğini temizle
        PageCacheService::flushPost($post->id);

        // Kategori sayfası önbelleğini temizle
        if ($post->category_id) {
            PageCacheService::flushCategory($post->category_id);
        }

        // Etiket sayfalarının önbelleğini temizle
        foreach ($post->tags as $tag) {
            PageCacheService::forget(route('posts.tag', $tag));
        }
    }

    public function deleted(Post $post)
    {
        // Ana sayfa önbelleğini temizle
        PageCacheService::forget(route('home'));
        
        // Blog yazısı önbelleğini temizle
        PageCacheService::flushPost($post->id);

        // Kategori sayfası önbelleğini temizle
        if ($post->category_id) {
            PageCacheService::flushCategory($post->category_id);
        }

        // Etiket sayfalarının önbelleğini temizle
        foreach ($post->tags as $tag) {
            PageCacheService::forget(route('posts.tag', $tag));
        }
    }
}