@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Site Ayarları</h2>
        <p class="text-gray-600 mt-1">Genel site ayarlarını buradan yönetebilirsiniz.</p>
    </div>

    <form action="{{ route('admin.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Genel Ayarlar</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="site_title" class="block text-sm font-medium text-gray-700">Site Başlığı</label>
                        <input type="text" name="settings[site_title]" id="site_title" 
                               value="{{ old('settings.site_title', Setting::get('site_title')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="site_description" class="block text-sm font-medium text-gray-700">Site Açıklaması</label>
                        <textarea name="settings[site_description]" id="site_description" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('settings.site_description', Setting::get('site_description')) }}</textarea>
                    </div>

                    <div>
                        <label for="logo" class="block text-sm font-medium text-gray-700">Logo</label>
                        @if(Setting::get('site_logo'))
                            <div class="mt-2 mb-2">
                                <img src="{{ asset('storage/' . Setting::get('site_logo')) }}" 
                                     alt="Site Logo" class="h-12">
                            </div>
                        @endif
                        <input type="file" name="logo" id="logo" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label for="favicon" class="block text-sm font-medium text-gray-700">Favicon</label>
                        @if(Setting::get('site_favicon'))
                            <div class="mt-2 mb-2">
                                <img src="{{ asset('storage/' . Setting::get('site_favicon')) }}" 
                                     alt="Favicon" class="h-8">
                            </div>
                        @endif
                        <input type="file" name="favicon" id="favicon" accept="image/*"
                               class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">İletişim Bilgileri</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="contact_email" class="block text-sm font-medium text-gray-700">E-posta Adresi</label>
                        <input type="email" name="settings[contact_email]" id="contact_email"
                               value="{{ old('settings.contact_email', Setting::get('contact_email')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="contact_phone" class="block text-sm font-medium text-gray-700">Telefon</label>
                        <input type="text" name="settings[contact_phone]" id="contact_phone"
                               value="{{ old('settings.contact_phone', Setting::get('contact_phone')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="contact_address" class="block text-sm font-medium text-gray-700">Adres</label>
                        <textarea name="settings[contact_address]" id="contact_address" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('settings.contact_address', Setting::get('contact_address')) }}</textarea>
                    </div>

                    <div>
                        <label for="google_maps_embed" class="block text-sm font-medium text-gray-700">Google Maps Embed Kodu</label>
                        <textarea name="settings[google_maps_embed]" id="google_maps_embed" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('settings.google_maps_embed', Setting::get('google_maps_embed')) }}</textarea>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Sosyal Medya</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook URL</label>
                        <input type="url" name="settings[facebook_url]" id="facebook_url"
                               value="{{ old('settings.facebook_url', Setting::get('facebook_url')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="twitter_url" class="block text-sm font-medium text-gray-700">Twitter URL</label>
                        <input type="url" name="settings[twitter_url]" id="twitter_url"
                               value="{{ old('settings.twitter_url', Setting::get('twitter_url')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram URL</label>
                        <input type="url" name="settings[instagram_url]" id="instagram_url"
                               value="{{ old('settings.instagram_url', Setting::get('instagram_url')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>

                    <div>
                        <label for="linkedin_url" class="block text-sm font-medium text-gray-700">LinkedIn URL</label>
                        <input type="url" name="settings[linkedin_url]" id="linkedin_url"
                               value="{{ old('settings.linkedin_url', Setting::get('linkedin_url')) }}"
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO ve Analytics</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-gray-700">Meta Keywords</label>
                        <textarea name="settings[meta_keywords]" id="meta_keywords" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('settings.meta_keywords', Setting::get('meta_keywords')) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">Virgülle ayırarak yazın</p>
                    </div>

                    <div>
                        <label for="google_analytics" class="block text-sm font-medium text-gray-700">Google Analytics Kodu</label>
                        <textarea name="settings[google_analytics]" id="google_analytics" rows="3"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('settings.google_analytics', Setting::get('google_analytics')) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Ayarları Kaydet
            </button>
        </div>
    </form>
</div>
@endsection