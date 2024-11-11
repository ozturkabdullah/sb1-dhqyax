@extends('layouts.app')

@section('content')
<div class="min-h-screen bg-gray-100 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-4 shadow sm:rounded-lg sm:px-10 text-center">
            <h1 class="text-9xl font-bold text-red-600">500</h1>
            <h2 class="mt-4 text-2xl font-bold text-gray-900">Sunucu Hatası</h2>
            <p class="mt-2 text-gray-600">Üzgünüz, bir hata oluştu. Lütfen daha sonra tekrar deneyin.</p>
            <div class="mt-6">
                <a href="{{ route('home') }}" 
                   class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Ana Sayfaya Dön
                </a>
            </div>
        </div>
    </div>
</div>
@endsection