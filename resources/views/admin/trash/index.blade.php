<?php ?>@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <h2 class="text-2xl font-semibold text-gray-800">Çöp Kutusu</h2>
        <p class="text-gray-600 mt-1">Silinen öğeleri görüntüleyin, geri yükleyin veya kalıcı olarak silin.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        @if($categories > 0)
            <div class="bg-white border rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Kategoriler</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $categories }}</p>
                    </div>
                    <a href="{{ route('admin.trash.show', 'categories') }}" 
                       class="text-blue-600 hover:text-blue-800">
                        Görüntüle →
                    </a>
                </div>
            </div>
        @endif

        @if($posts > 0)
            <div class="bg-white border rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Blog Yazıları</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $posts }}</p>
                    </div>
                    <a href="{{ route('admin.trash.show', 'posts') }}" 
                       class="text-blue-600 hover:text-blue-800">
                        Görüntüle →
                    </a>
                </div>
            </div>
        @endif

        @if($pages > 0)
            <div class="bg-white border rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Sayfalar</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $pages }}</p>
                    </div>
                    <a href="{{ route('admin.trash.show', 'pages') }}" 
                       class="text-blue-600 hover:text-blue-800">
                        Görüntüle →
                    </a>
                </div>
            </div>
        @endif

        @if($rentals > 0)
            <div class="bg-white border rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Kiralamalar</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $rentals }}</p>
                    </div>
                    <a href="{{ route('admin.trash.show', 'rentals') }}" 
                       class="text-blue-600 hover:text-blue-800">
                        Görüntüle →
                    </a>
                </div>
            </div>
        @endif

        @if($users > 0)
            <div class="bg-white border rounded-lg p-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg font-medium text-gray-900">Kullanıcılar</h3>
                        <p class="text-3xl font-bold text-gray-800">{{ $users }}</p>
                    </div>
                    <a href="{{ route('admin.trash.show', 'users') }}" 
                       class="text-blue-600 hover:text-blue-800">
                        Görüntüle →
                    </a>
                </div>
            </div>
        @endif

        @if($categories === 0 && $posts === 0 && $pages === 0 && $rentals === 0 && $users === 0)
            <div class="col-span-3 text-center py-12 text-gray-500">
                Çöp kutusu boş.
            </div>
        @endif
    </div>
</div>
@endsection