<?php

namespace App\Observers;

use App\Models\Rental;
use App\Events\PaymentSuccessful;
use App\Events\PaymentFailed;

class RentalObserver
{
    public function updated(Rental $rental)
    {
        // Ödeme durumu değiştiğinde olayları tetikle
        if ($rental->isDirty('status')) {
            if ($rental->status === 'active') {
                event(new PaymentSuccessful($rental));
            } elseif ($rental->status === 'cancelled' && $rental->payment) {
                event(new PaymentFailed($rental));
            }
        }
    }

    public function deleting(Rental $rental)
    {
        // İlişkili kayıtları temizle
        if ($rental->payment) {
            $rental->payment->delete();
        }
        if ($rental->invoice) {
            $rental->invoice->delete();
        }
    }
}