<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanBlockedAttempts extends Command
{
    protected $signature = 'auth:clean-attempts';
    protected $description = 'Eski bloke edilmiş giriş denemelerini temizle';

    public function handle()
    {
        // 30 günden eski kayıtları temizle
        DB::table('blocked_attempts')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        DB::table('suspicious_activities')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        DB::table('login_attempts')
            ->where('created_at', '<', now()->subDays(30))
            ->delete();

        $this->info('Eski giriş denemeleri temizlendi.');
    }
}