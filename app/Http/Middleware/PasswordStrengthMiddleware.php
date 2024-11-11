<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class PasswordStrengthMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('password')) {
            $password = $request->input('password');

            // Minimum 8 karakter
            if (strlen($password) < 8) {
                return back()->withErrors(['password' => 'Şifre en az 8 karakter olmalıdır.']);
            }

            // En az bir büyük harf
            if (!preg_match('/[A-Z]/', $password)) {
                return back()->withErrors(['password' => 'Şifre en az bir büyük harf içermelidir.']);
            }

            // En az bir küçük harf
            if (!preg_match('/[a-z]/', $password)) {
                return back()->withErrors(['password' => 'Şifre en az bir küçük harf içermelidir.']);
            }

            // En az bir rakam
            if (!preg_match('/[0-9]/', $password)) {
                return back()->withErrors(['password' => 'Şifre en az bir rakam içermelidir.']);
            }

            // En az bir özel karakter
            if (!preg_match('/[^A-Za-z0-9]/', $password)) {
                return back()->withErrors(['password' => 'Şifre en az bir özel karakter içermelidir.']);
            }

            // Yaygın şifreleri kontrol et
            $commonPasswords = ['Password123!', '12345678', 'qwerty123'];
            if (in_array($password, $commonPasswords)) {
                return back()->withErrors(['password' => 'Bu şifre çok yaygın. Lütfen daha güvenli bir şifre seçin.']);
            }
        }

        return $next($request);
    }
}