<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LoginAttempt;
use Illuminate\Support\Facades\Cache;

class LoginAttemptMiddleware
{
    protected $maxAttempts = 5;
    protected $decayMinutes = 30;

    public function handle(Request $request, Closure $next)
    {
        $key = $this->getKey($request);
        $attempts = Cache::get($key, 0);

        if ($attempts >= $this->maxAttempts) {
            return response()->json([
                'message' => 'Çok fazla başarısız giriş denemesi. Lütfen ' . $this->decayMinutes . ' dakika sonra tekrar deneyin.'
            ], 429);
        }

        $response = $next($request);

        if ($response->getStatusCode() === 401) {
            Cache::put($key, $attempts + 1, now()->addMinutes($this->decayMinutes));

            LoginAttempt::create([
                'email' => $request->email,
                'ip_address' => $request->ip(),
                'success' => false
            ]);
        }

        return $response;
    }

    protected function getKey(Request $request): string
    {
        return 'login_attempts:' . $request->ip() . ':' . $request->email;
    }
}