<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    protected function schedule(Schedule $schedule)
    {
        // Her gün gece yarısı sitemap'i güncelle
        $schedule->command('sitemap:generate')
            ->dailyAt('00:00')
            ->withoutOverlapping();

        // Her gün kiralama durumlarını kontrol et
        $schedule->command('rentals:check-status')->daily();
        
        // Her hafta geçici dosyaları temizle
        $schedule->command('temp:clean')->weekly();
    }

    protected function commands()
    {
        $this->load(__DIR__.'/Commands');
        require base_path('routes/console.php');
    }
}