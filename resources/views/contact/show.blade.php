@extends('layouts.app')

@section('meta_title', 'İletişim - ' . config('app.name'))
@section('meta_description', 'Bizimle iletişime geçin. Teknik servis hizmetlerimiz hakkında bilgi almak için form doldurabilir veya direkt arayabilirsiniz.')

@section('structured_data')
    <x-structured-data type="contact" />
@endsection

@section('content')
    {{-- Mevcut içerik --}}
@endsection