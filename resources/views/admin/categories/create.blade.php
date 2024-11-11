@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Yeni Kategori Ekle</h2>
        <p class="text-gray-600 mt-1">Yeni bir kategori oluşturun ve SEO ayarlarını yapılandırın.</p>
    </div>

    <form action="{{ route('admin.categories.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Temel Bilgiler</h3>
                
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Kategori Adı</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    @error('name')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="parent_id" class="block text-sm font-medium text-gray-700">Üst Kategori</label>
                    <select name="parent_id" id="parent_id" 
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Ana Kategori</option>
                        @foreach($parentCategories as $parentCategory)
                            <option value="{{ $parentCategory->id }}" {{ old('parent_id') == $parentCategory->id ? 'selected' : '' }}>
                                {{ $parentCategory->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="icon" class="block text-sm font-medium text-gray-700">Kategori İkonu</label>
                    <input type="file" name="icon" id="icon" accept="image/*" 
                           class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <p class="mt-1 text-sm text-gray-500">PNG, JPG, GIF formatında max. 1MB</p>
                </div>

                <div class="mb-4">
                    <label for="daily_rate" class="block text-sm font-medium text-gray-700">Günlük Kiralama Ücreti (₺)</label>
                    <input type="number" name="daily_rate" id="daily_rate" value="{{ old('daily_rate', 0) }}" step="0.01" min="0"
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                </div>
            </div>

            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">SEO ve Görünürlük</h3>
                
                <div class="mb-4">
                    <label for="meta_title" class="block text-sm font-medium text-gray-700">Meta Başlık</label>
                    <input type="text" name="meta_title" id="meta_title" value="{{ old('meta_title') }}" 
                           class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <p class="mt-1 text-sm text-gray-500">60 karakterden kısa olmalı</p>
                </div>

                <div class="mb-4">
                    <label for="meta_description" class="block text-sm font-medium text-gray-700">Meta Açıklama</label>
                    <textarea name="meta_description" id="meta_description" rows="3" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('meta_description') }}</textarea>
                    <p class="mt-1 text-sm text-gray-500">160 karakterden kısa olmalı</p>
                </div>

                <div class="mb-4">
                    <label for="seo_content" class="block text-sm font-medium text-gray-700">SEO İçeriği</label>
                    <textarea name="seo_content" id="seo_content" rows="5" 
                              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">{{ old('seo_content') }}</textarea>
                </div>

                <div class="space-y-4">
                    <div class="flex items-center">
                        <input type="checkbox" name="show_in_menu" id="show_in_menu" value="1" {{ old('show_in_menu') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="show_in_menu" class="ml-2 block text-sm text-gray-700">Menüde Göster</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="use_as_filter" id="use_as_filter" value="1" {{ old('use_as_filter') ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="use_as_filter" class="ml-2 block text-sm text-gray-700">Filtre Olarak Kullan</label>
                    </div>

                    <div class="flex items-center">
                        <input type="checkbox" name="status" id="status" value="1" {{ old('status', true) ? 'checked' : '' }}
                               class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="status" class="ml-2 block text-sm text-gray-700">Aktif</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-6 flex justify-end">
            <a href="{{ route('admin.categories.index') }}" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-lg mr-4 hover:bg-gray-200">
                İptal
            </a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Kategori Oluştur
            </button>
        </div>
    </form>
</div>
@endsection