@extends('layouts.app')

@section('meta_title', $post->meta_title)
@section('meta_description', $post->meta_description)

@section('structured_data')
    <x-structured-data type="post" :model="$post" />
@endsection

@section('content')
    {{-- Mevcut içerik --}}
@endsection