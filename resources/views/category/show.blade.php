@extends('layouts.app')

@section('meta_title', $category->meta_title)
@section('meta_description', $category->meta_description)

@section('structured_data')
    <x-structured-data type="category" :model="$category" />
@endsection

@section('content')
    {{-- Mevcut içerik --}}
@endsection