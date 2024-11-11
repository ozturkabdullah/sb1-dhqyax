<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\LoginAttempt;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Carbon\Carbon;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = RouteServiceProvider::HOME;
    protected $maxAttempts = 5;
    protected $decayMinutes = 30;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    protected function attemptLogin(Request $request)
    {
        $key = $this->throttleKey($request);
        
        if (RateLimiter::tooManyAttempts($key, $this->maxAttempts)) {
            $seconds = RateLimiter::availableIn($key);
            throw ValidationException::withMessages([
                $this->username() => [trans('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ])],
            ])->status(429);
        }

        $credentials = $this->credentials($request);
        $credentials['status'] = true;

        $attempt = $this->guard()->attempt(
            $credentials, $request->filled('remember')
        );

        // Login girişimini kaydet
        LoginAttempt::create([
            'email' => $request->email,
            'ip_address' => $request->ip(),
            'success' => $attempt
        ]);

        if (!$attempt) {
            RateLimiter::hit($key, $this->decayMinutes * 60);
        } else {
            // Başarılı giriş bilgilerini güncelle
            $user = $this->guard()->user();
            $user->update([
                'last_login_at' => Carbon::now(),
                'last_login_ip' => $request->ip()
            ]);
        }

        return $attempt;
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            $this->username() => 'required|string|email',
            'password' => 'required|string',
            'g-recaptcha-response' => 'required|captcha'
        ], [
            'g-recaptcha-response.required' => 'Lütfen robot olmadığınızı doğrulayın.',
            'g-recaptcha-response.captcha' => 'reCAPTCHA doğrulaması başarısız oldu.'
        ]);
    }

    protected function throttleKey(Request $request)
    {
        return Str::lower($request->input($this->username())).'|'.$request->ip();
    }

    protected function authenticated(Request $request, $user)
    {
        if (!$user->status) {
            $this->guard()->logout();
            throw ValidationException::withMessages([
                $this->username() => ['Bu hesap pasif durumda.'],
            ]);
        }
    }

    protected function loggedOut(Request $request)
    {
        return redirect()->route('login');
    }
}