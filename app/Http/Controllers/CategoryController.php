<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Services\PageCacheService;
use App\Traits\HasBreadcrumbs;

class CategoryController extends Controller
{
    use HasBreadcrumbs;

    public function show(Category $category)
    {
        return PageCacheService::remember('category', function () use ($category) {
            $this->category = $category;
            
            $breadcrumbs = $this->getBreadcrumbs();
            
            $childCategories = $category->children()
                ->where('status', true)
                ->get();

            return view('category.show', compact('category', 'childCategories', 'breadcrumbs'));
        });
    }
}