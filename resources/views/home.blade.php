@extends('layouts.app')

@section('meta_title', config('app.name') . ' - Profesyonel Teknik Servis Hizmetleri')
@section('meta_description', 'Kombi, klima ve beyaz eşya teknik servis hizmetleri. Profesyonel ve güvenilir hizmet.')

@section('structured_data')
    <x-structured-data type="home" />
@endsection

@section('content')
    {{-- Mevcut içerik --}}
@endsection