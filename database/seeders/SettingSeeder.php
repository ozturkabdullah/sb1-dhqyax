<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run()
    {
        $settings = [
            'site_title' => 'Teknik Servis',
            'site_description' => 'Profesyonel Kombi, Klima ve Beyaz Eşya Teknik Servis Hizmetleri',
            'contact_email' => 'info@example.com',
            'contact_phone' => '0850 123 45 67',
            'contact_address' => 'Örnek Mahallesi, Örnek Sokak No:1 Ankara',
            'meta_keywords' => 'kombi servisi, klima servisi, beyaz eşya servisi, teknik servis'
        ];

        foreach ($settings as $key => $value) {
            Setting::set($key, $value);
        }
    }
}