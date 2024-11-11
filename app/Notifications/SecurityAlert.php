<?php

namespace App\Notifications;

use App\Models\SecurityLog;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class SecurityAlert extends Notification
{
    use Queueable;

    protected $log;

    public function __construct(SecurityLog $log)
    {
        $this->log = $log;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Güvenlik Uyarısı - ' . config('app.name'))
            ->line('Önemli bir güvenlik olayı tespit edildi:')
            ->line('Olay: ' . $this->log->event_type)
            ->line('IP Adresi: ' . $this->log->ip_address)
            ->line('Tarih: ' . $this->log->created_at->format('d.m.Y H:i:s'))
            ->action('Güvenlik Loglarını Görüntüle', route('admin.security-logs.index'));
    }

    public function toArray($notifiable)
    {
        return [
            'event_type' => $this->log->event_type,
            'severity' => $this->log->severity,
            'ip_address' => $this->log->ip_address,
            'details' => $this->log->details
        ];
    }
}