<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Post;
use App\Services\PageCacheService;

class HomeController extends Controller
{
    public function index()
    {
        return PageCacheService::remember('home', function () {
            $mainCategories = Category::with('children')
                ->whereNull('parent_id')
                ->where('status', true)
                ->get();

            $latestPosts = Post::with(['category', 'tags'])
                ->where('status', true)
                ->latest()
                ->take(6)
                ->get();

            return view('home', compact('mainCategories', 'latestPosts'));
        });
    }
}