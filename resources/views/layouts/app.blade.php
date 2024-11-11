<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('meta_title', config('app.name'))</title>
    <meta name="description" content="@yield('meta_description', 'Teknik Servis Hizmetleri')">
    @yield('meta_tags')
    @yield('structured_data')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    {{-- Mevcut içerik --}}
</body>
</html>