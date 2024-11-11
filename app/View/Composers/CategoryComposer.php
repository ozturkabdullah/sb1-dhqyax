<?php

namespace App\View\Composers;

use App\Models\Category;
use Illuminate\View\View;

class CategoryComposer
{
    public function compose(View $view)
    {
        $menuCategories = Category::where('show_in_menu', true)
            ->where('status', true)
            ->whereNull('parent_id')
            ->get();

        $footerCategories = Category::where('status', true)
            ->whereNull('parent_id')
            ->take(6)
            ->get();

        $view->with('menuCategories', $menuCategories)
             ->with('footerCategories', $footerCategories);
    }
}