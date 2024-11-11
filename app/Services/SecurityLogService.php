<?php

namespace App\Services;

use App\Models\SecurityLog;
use App\Notifications\SecurityAlert;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Cache;

class SecurityLogService
{
    const SEVERITY_INFO = 'info';
    const SEVERITY_WARNING = 'warning';
    const SEVERITY_DANGER = 'danger';

    // Şüpheli aktivite eşikleri
    const THRESHOLD_LOGIN_ATTEMPTS = 5;
    const THRESHOLD_PASSWORD_CHANGES = 3;
    const THRESHOLD_API_REQUESTS = 100;

    /**
     * Güvenlik olayını logla
     */
    public function log(string $eventType, string $severity, array $details = [], $userId = null): void
    {
        $log = SecurityLog::create([
            'event_type' => $eventType,
            'severity' => $severity,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'user_id' => $userId ?? auth()->id(),
            'details' => $details
        ]);

        // Kritik olayları hemen bildir
        if ($severity === self::SEVERITY_DANGER) {
            $this->notifyAdmins($log);
        }

        // Şüpheli aktivite kontrolü
        $this->checkSuspiciousActivity($eventType, $log->ip_address);
    }

    /**
     * Başarısız giriş denemesini logla
     */
    public function logFailedLogin(string $email): void
    {
        $attempts = $this->incrementLoginAttempts($email);

        $severity = $attempts >= self::THRESHOLD_LOGIN_ATTEMPTS 
            ? self::SEVERITY_DANGER 
            : self::SEVERITY_WARNING;

        $this->log('failed_login', $severity, [
            'email' => $email,
            'attempts' => $attempts
        ]);
    }

    /**
     * Şifre değişikliğini logla
     */
    public function logPasswordChange(): void
    {
        $changes = $this->getPasswordChanges();

        $this->log('password_change', self::SEVERITY_INFO, [
            'changes_last_24h' => $changes
        ]);
    }

    /**
     * Şüpheli aktiviteleri kontrol et
     */
    protected function checkSuspiciousActivity(string $eventType, string $ipAddress): void
    {
        $key = "security_events:{$eventType}:{$ipAddress}";
        $count = Cache::increment($key);
        Cache::expire($key, now()->addHour());

        if ($this->isThresholdExceeded($eventType, $count)) {
            $this->log('suspicious_activity', self::SEVERITY_DANGER, [
                'event_type' => $eventType,
                'count' => $count,
                'timeframe' => '1 hour'
            ]);
        }
    }

    /**
     * Eşik değerlerini kontrol et
     */
    protected function isThresholdExceeded(string $eventType, int $count): bool
    {
        return match($eventType) {
            'failed_login' => $count >= self::THRESHOLD_LOGIN_ATTEMPTS,
            'password_change' => $count >= self::THRESHOLD_PASSWORD_CHANGES,
            'api_request' => $count >= self::THRESHOLD_API_REQUESTS,
            default => false
        };
    }

    /**
     * Yöneticilere bildirim gönder
     */
    protected function notifyAdmins(SecurityLog $log): void
    {
        $admins = \App\Models\User::role('admin')->get();
        Notification::send($admins, new SecurityAlert($log));
    }

    /**
     * Başarısız giriş denemelerini takip et
     */
    protected function incrementLoginAttempts(string $email): int
    {
        $key = "login_attempts:{$email}";
        $attempts = Cache::increment($key);
        Cache::expire($key, now()->addHour());
        return $attempts;
    }

    /**
     * Son 24 saatteki şifre değişikliklerini say
     */
    protected function getPasswordChanges(): int
    {
        return SecurityLog::where('event_type', 'password_change')
            ->where('created_at', '>=', now()->subDay())
            ->count();
    }
}