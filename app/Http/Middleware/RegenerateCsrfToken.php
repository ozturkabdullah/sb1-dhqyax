<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RegenerateCsrfToken
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Form submit sonrası CSRF token'ı yenile
        if ($request->isMethod('POST') || $request->isMethod('PUT') || $request->isMethod('DELETE')) {
            Session::regenerateToken();
        }

        return $response;
    }
}