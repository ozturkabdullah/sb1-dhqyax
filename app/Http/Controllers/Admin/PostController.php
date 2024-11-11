<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Category;
use App\Models\Tag;
use App\Services\ImageService;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::with(['category', 'tags'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);
        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $categories = Category::where('status', true)->get();
        $tags = Tag::where('status', true)->get();
        return view('admin.posts.create', compact('categories', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:255',
            'content' => 'required',
            'cover_image' => 'nullable|image|max:2048|dimensions:min_width=800,min_height=400',
            'status' => 'boolean',
            'meta_title' => 'required|max:60',
            'meta_description' => 'required|max:160',
            'seo_content' => 'nullable',
            'tags' => 'array|exists:tags,id'
        ]);

        if ($request->hasFile('cover_image')) {
            $versions = ImageService::optimize(
                $request->file('cover_image'),
                'posts'
            );
            $validated['cover_image'] = $versions['original'];
        }

        $post = Post::create($validated);

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'İçerik başarıyla oluşturuldu.');
    }

    public function edit(Post $post)
    {
        $categories = Category::where('status', true)->get();
        $tags = Tag::where('status', true)->get();
        return view('admin.posts.edit', compact('post', 'categories', 'tags'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|max:255',
            'content' => 'required',
            'cover_image' => 'nullable|image|max:2048|dimensions:min_width=800,min_height=400',
            'status' => 'boolean',
            'meta_title' => 'required|max:60',
            'meta_description' => 'required|max:160',
            'seo_content' => 'nullable',
            'tags' => 'array|exists:tags,id'
        ]);

        if ($request->hasFile('cover_image')) {
            // Eski resmi sil
            if ($post->cover_image) {
                ImageService::delete($post->cover_image);
            }

            $versions = ImageService::optimize(
                $request->file('cover_image'),
                'posts'
            );
            $validated['cover_image'] = $versions['original'];
        }

        $post->update($validated);

        if (isset($validated['tags'])) {
            $post->tags()->sync($validated['tags']);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'İçerik başarıyla güncellendi.');
    }

    public function destroy(Post $post)
    {
        if ($post->cover_image) {
            ImageService::delete($post->cover_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'İçerik başarıyla silindi.');
    }
}