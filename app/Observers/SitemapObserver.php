<?php

namespace App\Observers;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Eloquent\Model;

class SitemapObserver
{
    public function saved(Model $model)
    {
        $this->regenerateSitemap();
    }

    public function deleted(Model $model)
    {
        $this->regenerateSitemap();
    }

    protected function regenerateSitemap()
    {
        // Sitemap'i yeniden oluştur
        Artisan::queue('sitemap:generate');
    }
}