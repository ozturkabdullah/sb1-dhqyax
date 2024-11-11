<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\PageCacheService;

class ClearPageCache extends Command
{
    protected $signature = 'cache:clear-pages {type? : Temizlenecek önbellek tipi (home, category, post, page)}';
    protected $description = 'Sayfa önbelleklerini temizle';

    public function handle()
    {
        $type = $this->argument('type');

        if ($type) {
            switch ($type) {
                case 'home':
                    PageCacheService::forget(route('home'));
                    $this->info('Ana sayfa önbelleği temizlendi.');
                    break;
                case 'category':
                    PageCacheService::flush();
                    $this->info('Tüm kategori sayfaları önbelleği temizlendi.');
                    break;
                case 'post':
                    PageCacheService::flush();
                    $this->info('Tüm blog yazıları önbelleği temizlendi.');
                    break;
                case 'page':
                    PageCacheService::flush();
                    $this->info('Tüm statik sayfalar önbelleği temizlendi.');
                    break;
                default:
                    $this->error('Geçersiz önbellek tipi.');
                    return;
            }
        } else {
            PageCacheService::flush();
            $this->info('Tüm sayfa önbellekleri temizlendi.');
        }
    }
}