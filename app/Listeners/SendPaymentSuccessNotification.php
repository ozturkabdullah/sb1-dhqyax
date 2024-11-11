<?php

namespace App\Listeners;

use App\Events\PaymentSuccessful;
use App\Mail\PaymentSuccessfulMail;
use App\Mail\AdminPaymentNotification;
use Illuminate\Support\Facades\Mail;

class SendPaymentSuccessNotification
{
    public function handle(PaymentSuccessful $event)
    {
        $rental = $event->rental;

        Mail::to($rental->user->email)
            ->send(new PaymentSuccessfulMail($rental));

        Mail::to(config('mail.admin_address'))
            ->send(new AdminPaymentNotification($rental, 'success'));
    }
}