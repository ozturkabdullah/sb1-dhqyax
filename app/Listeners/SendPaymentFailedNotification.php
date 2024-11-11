<?php

namespace App\Listeners;

use App\Events\PaymentFailed;
use App\Mail\PaymentFailedMail;
use App\Mail\AdminPaymentNotification;
use Illuminate\Support\Facades\Mail;

class SendPaymentFailedNotification
{
    public function handle(PaymentFailed $event)
    {
        $rental = $event->rental;

        Mail::to($rental->user->email)
            ->send(new PaymentFailedMail($rental));

        Mail::to(config('mail.admin_address'))
            ->send(new AdminPaymentNotification($rental, 'failed'));
    }
}