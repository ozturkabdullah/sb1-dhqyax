<?php

namespace App\Console\Commands;

use App\Models\Rental;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckRentalStatus extends Command
{
    protected $signature = 'rentals:check-status';
    protected $description = 'Kiralama durumlarını kontrol et ve güncelle';

    public function handle()
    {
        // Süresi dolan aktif kiralamaları tamamlandı olarak işaretle
        Rental::where('status', 'active')
            ->where('end_date', '<', Carbon::now())
            ->update(['status' => 'completed']);

        // 24 saat içinde ödemesi yapılmayan bekleyen kiralamaları iptal et
        Rental::where('status', 'pending')
            ->where('created_at', '<', Carbon::now()->subDay())
            ->update(['status' => 'cancelled']);

        $this->info('Kiralama durumları güncellendi.');
    }
}