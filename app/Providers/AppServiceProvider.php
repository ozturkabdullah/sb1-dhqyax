<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Session;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use App\Observers\SitemapObserver;
use App\View\Composers\CategoryComposer;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // HTTPS zorunlu
        if (!$this->app->environment('local')) {
            URL::forceScheme('https');
        }

        // MySQL için varsayılan string uzunluğu
        Schema::defaultStringLength(191);

        // Pagination için Tailwind kullan
        Paginator::useBootstrap();

        // Sitemap observer'ları kaydet
        Category::observe(SitemapObserver::class);
        Post::observe(SitemapObserver::class);
        Page::observe(SitemapObserver::class);

        // Global view composer'ları kaydet
        View::composer('layouts.app', CategoryComposer::class);

        // CSRF token'ı her sayfa yüklendiğinde yenile
        View::composer('*', function ($view) {
            if (!app()->runningInConsole()) {
                $view->with('_token', Session::token());
            }
        });
    }
}