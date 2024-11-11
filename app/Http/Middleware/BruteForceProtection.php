<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\BruteForceProtectionService;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;

class BruteForceProtection
{
    protected $protectionService;

    public function __construct(BruteForceProtectionService $protectionService)
    {
        $this->protectionService = $protectionService;
    }

    public function handle(Request $request, Closure $next)
    {
        $key = $this->getKey($request);

        // IP bazlı rate limiting
        if (RateLimiter::tooManyAttempts($key, 60)) {
            $this->protectionService->logBlockedAttempt($request);
            return $this->tooManyAttempts($key);
        }

        // Başarısız giriş denemelerini kontrol et
        if ($this->protectionService->isBlocked($request)) {
            return $this->blockedResponse($request);
        }

        // Şüpheli IP kontrolü
        if ($this->protectionService->isSuspiciousIP($request->ip())) {
            $this->protectionService->logSuspiciousAttempt($request);
            return $this->suspiciousResponse();
        }

        $response = $next($request);

        // Başarısız giriş denemesini kaydet
        if ($response->getStatusCode() === 401) {
            RateLimiter::hit($key);
            $this->protectionService->logFailedAttempt($request);
        }

        return $response;
    }

    protected function getKey(Request $request): string
    {
        return 'login:' . $request->ip();
    }

    protected function tooManyAttempts(string $key)
    {
        $seconds = RateLimiter::availableIn($key);

        return response()->json([
            'error' => 'Çok fazla başarısız giriş denemesi.',
            'message' => 'Lütfen ' . ceil($seconds / 60) . ' dakika sonra tekrar deneyin.',
            'blocked_until' => now()->addSeconds($seconds)->toDateTimeString()
        ], 429);
    }

    protected function blockedResponse(Request $request)
    {
        $blockDuration = $this->protectionService->getBlockDuration($request);

        return response()->json([
            'error' => 'Hesabınız kilitlendi.',
            'message' => 'Güvenlik nedeniyle hesabınız ' . $blockDuration . ' dakika süreyle kilitlendi.',
            'blocked_until' => now()->addMinutes($blockDuration)->toDateTimeString()
        ], 423);
    }

    protected function suspiciousResponse()
    {
        return response()->json([
            'error' => 'Şüpheli aktivite tespit edildi.',
            'message' => 'Güvenlik nedeniyle erişiminiz engellendi. Lütfen yönetici ile iletişime geçin.'
        ], 403);
    }
}