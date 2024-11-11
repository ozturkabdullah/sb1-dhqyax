<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BruteForceProtectionService
{
    const MAX_ATTEMPTS = 5;
    const INITIAL_BLOCK_DURATION = 30; // dakika
    const MAX_BLOCK_DURATION = 24 * 60; // 24 saat
    const SUSPICIOUS_THRESHOLD = 10;

    public function isBlocked(Request $request): bool
    {
        $key = $this->getBlockKey($request);
        return Cache::has($key);
    }

    public function getBlockDuration(Request $request): int
    {
        $attempts = $this->getFailedAttempts($request);
        $multiplier = floor($attempts / self::MAX_ATTEMPTS);
        
        $duration = self::INITIAL_BLOCK_DURATION * pow(2, $multiplier);
        return min($duration, self::MAX_BLOCK_DURATION);
    }

    public function isSuspiciousIP(string $ip): bool
    {
        // Son 24 saatteki başarısız denemeleri kontrol et
        $attempts = DB::table('login_attempts')
            ->where('ip_address', $ip)
            ->where('success', false)
            ->where('created_at', '>=', now()->subDay())
            ->count();

        return $attempts >= self::SUSPICIOUS_THRESHOLD;
    }

    public function logFailedAttempt(Request $request): void
    {
        $attempts = $this->incrementFailedAttempts($request);

        if ($attempts >= self::MAX_ATTEMPTS) {
            $this->blockUser($request);
        }

        DB::table('login_attempts')->insert([
            'ip_address' => $request->ip(),
            'email' => $request->input('email'),
            'user_agent' => $request->userAgent(),
            'success' => false,
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function logBlockedAttempt(Request $request): void
    {
        DB::table('blocked_attempts')->insert([
            'ip_address' => $request->ip(),
            'email' => $request->input('email'),
            'user_agent' => $request->userAgent(),
            'reason' => 'rate_limit_exceeded',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function logSuspiciousAttempt(Request $request): void
    {
        DB::table('suspicious_activities')->insert([
            'ip_address' => $request->ip(),
            'email' => $request->input('email'),
            'user_agent' => $request->userAgent(),
            'reason' => 'multiple_failed_attempts',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    protected function getBlockKey(Request $request): string
    {
        return 'auth.blocked.' . $request->ip();
    }

    protected function getAttemptsKey(Request $request): string
    {
        return 'auth.attempts.' . $request->ip();
    }

    protected function getFailedAttempts(Request $request): int
    {
        return Cache::get($this->getAttemptsKey($request), 0);
    }

    protected function incrementFailedAttempts(Request $request): int
    {
        $key = $this->getAttemptsKey($request);
        $attempts = Cache::get($key, 0) + 1;
        Cache::put($key, $attempts, now()->addHours(24));
        return $attempts;
    }

    protected function blockUser(Request $request): void
    {
        $duration = $this->getBlockDuration($request);
        Cache::put(
            $this->getBlockKey($request),
            true,
            now()->addMinutes($duration)
        );
    }
}