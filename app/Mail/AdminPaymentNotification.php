<?php

namespace App\Mail;

use App\Models\Rental;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $rental;
    public $status;

    public function __construct(Rental $rental, $status)
    {
        $this->rental = $rental;
        $this->status = $status;
    }

    public function build()
    {
        $subject = $this->status === 'success' ? 'Yeni Başarılı Ödeme' : 'Başarısız Ödeme Bildirimi';
        
        return $this->markdown('emails.admin.payment-notification')
            ->subject($subject . ' - ' . config('app.name'));
    }
}