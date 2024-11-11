@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Etiket Düzenle</h2>
        <p class="text-gray-600 mt-1">Etiketi ve SEO ayarlarını düzenleyin.</p>
    </div>

    <form action="{{ route('admin.tags.update', $tag) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Temel Bilgiler</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Etiket Adı</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $tag->name) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="status" id="status" value="1" {{ old('status', $tag->status) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-700">Aktif</label>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO Bilgileri</h3>
                
                <div class="space-y-4">
                    <div>
                        <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Başlık</label>
                        <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title', $tag->meta_title) }}" required
                               class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="mt-1 text-sm text-gray-500">60 karakterden kısa olmalı</p>
                        @error('meta_title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Açıklama</label>
                        <textarea name="meta_description" id="meta_description" rows="3" required
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('meta_description', $tag->meta_description) }}</textarea>
                        <p class="mt-1 text-sm text-gray-500">160 karakterden kısa olmalı</p>
                        @error('meta_description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="seo_content" class="block text-sm font-medium text-gray-700">SEO İçeriği</label>
                        <textarea name="seo_content" id="seo_content" rows="4"
                                  class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('seo_content', $tag->seo_content) }}</textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.tags.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg mr-4 hover:bg-gray-200">
                İptal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Değişiklikleri Kaydet
            </button>
        </div>
    </form>
</div>
@endsection