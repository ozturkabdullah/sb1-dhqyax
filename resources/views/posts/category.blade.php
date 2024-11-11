@extends('layouts.app')

@section('meta_title', $category->meta_title)
@section('meta_description', $category->meta_description)

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $category->name }}</h1>
        <p class="text-gray-600">{{ $category->meta_description }}</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($posts as $post)
            <article class="bg-white rounded-lg shadow-sm overflow-hidden">
                @if($post->cover_image)
                    <img src="{{ asset('storage/' . $post->cover_image) }}" 
                         alt="{{ $post->title }}"
                         class="w-full h-48 object-cover">
                @endif
                <div class="p-6">
                    <div class="flex items-center text-sm text-gray-500 mb-2">
                        <time>{{ $post->created_at->format('d.m.Y') }}</time>
                    </div>
                    <h2 class="text-xl font-semibold text-gray-800 mb-2">
                        <a href="{{ route('posts.show', $post) }}" class="hover:text-blue-600">
                            {{ $post->title }}
                        </a>
                    </h2>
                    <p class="text-gray-600 mb-4">
                        {{ Str::limit(strip_tags($post->content), 150) }}
                    </p>
                    <div class="flex flex-wrap gap-2">
                        @foreach($post->tags as $tag)
                            <a href="{{ route('posts.tag', $tag) }}" 
                               class="text-sm text-blue-600 hover:text-blue-800">
                                #{{ $tag->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <div class="mt-8">
        {{ $posts->links() }}
    </div>

    @if($category->seo_content)
        <div class="mt-12 bg-white rounded-lg shadow-sm p-6">
            <div class="prose max-w-none text-gray-600">
                {!! $category->seo_content !!}
            </div>
        </div>
    @endif
</div>
@endsection