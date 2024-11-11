@extends('layouts.app')

@section('meta_title', $page->meta_title)
@section('meta_description', $page->meta_description)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto">
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h1 class="text-3xl font-bold text-gray-800 mb-6">{{ $page->title }}</h1>
            
            <div class="prose max-w-none">
                {!! $page->content !!}
            </div>

            @if($page->seo_content)
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <div class="prose max-w-none text-gray-600">
                        {!! $page->seo_content !!}
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection