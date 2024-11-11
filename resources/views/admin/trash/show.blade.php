<?php ?>@extends('layouts.admin')

@section('content')
<div class="bg-white rounded-lg shadow-sm p-6">
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-semibold text-gray-800">
                Silinen {{ ucfirst($type) }}
            </h2>
            <a href="{{ route('admin.trash.index') }}" 
               class="text-gray-600 hover:text-gray-900">
                ← Geri Dön
            </a>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    @if($type === 'categories')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Üst Kategori</th>
                    @elseif($type === 'posts' || $type === 'pages')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Başlık</th>
                    @elseif($type === 'rentals')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Kullanıcı</th>
                    @elseif($type === 'users')
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ad Soyad</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">E-posta</th>
                    @endif
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Silinme Tarihi</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">İşlemler</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @foreach($items as $item)
                    <tr>
                        @if($type === 'categories')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->parent?->name ?? '-' }}</td>
                        @elseif($type === 'posts' || $type === 'pages')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->title }}</td>
                        @elseif($type === 'rentals')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->category->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->user->name }}</td>
                        @elseif($type === 'users')
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $item->email }}</td>
                        @endif
                        <td class="px-6 py-4 whitespace-nowrap">{{ $item->deleted_at->format('d.m.Y H:i') }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex space-x-2">
                                <form action="{{ route('admin.trash.restore', [$type, $item->id]) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-green-600 hover:text-green-900">
                                        Geri Yükle
                                    </button>
                                </form>
                                <form action="{{ route('admin.trash.force-delete', [$type, $item->id]) }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900" 
                                            onclick="return confirm('Bu öğeyi kalıcı olarak silmek istediğinize emin misiniz?')">
                                        Kalıcı Sil
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $items->links() }}
    </div>
</div>
@endsection