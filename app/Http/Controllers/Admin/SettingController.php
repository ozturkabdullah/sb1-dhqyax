<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'settings.site_title' => 'required|string|max:255',
            'settings.site_description' => 'required|string|max:500',
            'settings.contact_email' => 'required|email',
            'settings.contact_phone' => 'required|string|max:20',
            'settings.contact_address' => 'required|string',
            'settings.google_maps_embed' => 'nullable|string',
            'settings.facebook_url' => 'nullable|url',
            'settings.twitter_url' => 'nullable|url',
            'settings.instagram_url' => 'nullable|url',
            'settings.linkedin_url' => 'nullable|url',
            'settings.google_analytics' => 'nullable|string',
            'settings.meta_keywords' => 'nullable|string',
            'logo' => 'nullable|image|max:1024',
            'favicon' => 'nullable|image|max:512'
        ]);

        foreach ($validated['settings'] as $key => $value) {
            Setting::set($key, $value);
        }

        if ($request->hasFile('logo')) {
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $logoPath = $request->file('logo')->store('settings', 'public');
            Setting::set('site_logo', $logoPath);
        }

        if ($request->hasFile('favicon')) {
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $faviconPath = $request->file('favicon')->store('settings', 'public');
            Setting::set('site_favicon', $faviconPath);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Ayarlar başarıyla güncellendi.');
    }
}