<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Rules\PasswordHistory;

class PasswordExpirationController extends Controller
{
    public function showChangePasswordForm()
    {
        return view('auth.passwords.expired');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'different:current_password',
                new PasswordHistory(3) // Son 3 şifreyi kontrol et
            ],
        ]);

        $user = auth()->user();

        // Şifre geçmişini kaydet
        $user->passwordHistories()->create([
            'password' => $user->password
        ]);

        // Şifreyi güncelle
        $user->password = Hash::make($request->password);
        $user->password_updated_at = Carbon::now();
        $user->save();

        // Diğer cihazlardaki oturumları sonlandır
        auth()->logoutOtherDevices($request->current_password);

        return redirect()->route('home')
            ->with('success', 'Şifreniz başarıyla güncellendi. Güvenliğiniz için diğer cihazlardaki oturumlarınız sonlandırıldı.');
    }
}