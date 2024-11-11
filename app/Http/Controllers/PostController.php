<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Services\PageCacheService;
use App\Traits\HasBreadcrumbs;

class PostController extends Controller
{
    use HasBreadcrumbs;

    public function show(Post $post)
    {
        return PageCacheService::remember('post', function () use ($post) {
            $this->category = $post->category;
            $this->title = $post->title;
            
            $breadcrumbs = $this->getBreadcrumbs();

            $relatedPosts = Post::where('category_id', $post->category_id)
                ->where('id', '!=', $post->id)
                ->where('status', true)
                ->take(3)
                ->get();

            return view('posts.show', compact('post', 'relatedPosts', 'breadcrumbs'));
        });
    }
}