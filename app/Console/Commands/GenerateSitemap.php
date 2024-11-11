<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;

class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'XML sitemap oluştur';

    public function handle()
    {
        $this->info('Sitemap oluşturuluyor...');

        $sitemap = SitemapGenerator::create(config('app.url'))
            ->getSitemap();

        // Ana sayfa
        $sitemap->add(Url::create('/')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY)
            ->setPriority(1.0));

        // Kategoriler
        Category::where('status', true)->each(function (Category $category) use ($sitemap) {
            $sitemap->add(Url::create("/category/{$category->slug}")
                ->setLastModificationDate($category->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY)
                ->setPriority(0.8));
        });

        // Blog yazıları
        Post::where('status', true)->each(function (Post $post) use ($sitemap) {
            $sitemap->add(Url::create("/blog/{$post->slug}")
                ->setLastModificationDate($post->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.6));
        });

        // Statik sayfalar
        Page::where('status', true)->each(function (Page $page) use ($sitemap) {
            $sitemap->add(Url::create("/page/{$page->slug}")
                ->setLastModificationDate($page->updated_at)
                ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
                ->setPriority(0.5));
        });

        // İletişim sayfası
        $sitemap->add(Url::create('/contact')
            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY)
            ->setPriority(0.5));

        $sitemap->writeToFile(public_path('sitemap.xml'));

        $this->info('Sitemap başarıyla oluşturuldu: ' . public_path('sitemap.xml'));
    }
}