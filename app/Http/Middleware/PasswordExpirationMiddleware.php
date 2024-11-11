<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PasswordExpirationMiddleware
{
    protected $expirationDays = 90;

    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $passwordUpdatedAt = new Carbon($user->password_updated_at ?? $user->created_at);

            if ($passwordUpdatedAt->addDays($this->expirationDays)->isPast()) {
                return redirect()->route('password.expired');
            }
        }

        return $next($request);
    }
}