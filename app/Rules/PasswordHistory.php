<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

class PasswordHistory implements Rule
{
    protected $count;

    public function __construct($count = 3)
    {
        $this->count = $count;
    }

    public function passes($attribute, $value)
    {
        $user = auth()->user();
        
        // Mevcut şifreyi kontrol et
        if (Hash::check($value, $user->password)) {
            return false;
        }

        // Son şifreleri kontrol et
        foreach ($user->passwordHistories()->latest()->take($this->count)->get() as $history) {
            if (Hash::check($value, $history->password)) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'Bu şifre son ' . $this->count . ' şifrenizden biri olamaz.';
    }
}