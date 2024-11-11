<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use App\Events\PaymentSuccessful;
use App\Events\PaymentFailed;
use App\Listeners\SendPaymentSuccessNotification;
use App\Listeners\SendPaymentFailedNotification;
use App\Models\Rental;
use App\Observers\RentalObserver;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        PaymentSuccessful::class => [
            SendPaymentSuccessNotification::class,
        ],
        PaymentFailed::class => [
            SendPaymentFailedNotification::class,
        ],
    ];

    public function boot()
    {
        Rental::observe(RentalObserver::class);
    }
}