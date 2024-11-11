<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;

class CacheClear extends Command
{
    protected $signature = 'cache:cleanup';
    protected $description = 'Tüm önbellekleri temizle';

    public function handle()
    {
        // Uygulama cache'ini temizle
        Cache::flush();
        $this->info('Uygulama önbelleği temizlendi.');

        // Route cache'ini temizle
        Artisan::call('route:clear');
        $this->info('Route önbelleği temizlendi.');

        // Config cache'ini temizle
        Artisan::call('config:clear');
        $this->info('Config önbelleği temizlendi.');

        // View cache'ini temizle
        Artisan::call('view:clear');
        $this->info('View önbelleği temizlendi.');

        return Command::SUCCESS;
    }
}