<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show');
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,'.$user->id,
            'phone' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profil bilgileriniz başarıyla güncellendi.');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', new MatchOldPassword],
            'password' => 'required|string|min:8|confirmed|different:current_password',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->password),
            'password_updated_at' => now(),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Şifreniz başarıyla güncellendi.');
    }
}