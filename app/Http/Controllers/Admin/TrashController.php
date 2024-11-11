<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use App\Models\Page;
use App\Models\Rental;
use App\Models\User;
use Illuminate\Http\Request;

class TrashController extends Controller
{
    public function index()
    {
        $categories = Category::onlyTrashed()->count();
        $posts = Post::onlyTrashed()->count();
        $pages = Page::onlyTrashed()->count();
        $rentals = Rental::onlyTrashed()->count();
        $users = User::onlyTrashed()->count();

        return view('admin.trash.index', compact(
            'categories',
            'posts',
            'pages',
            'rentals',
            'users'
        ));
    }

    public function show($type)
    {
        $items = match($type) {
            'categories' => Category::onlyTrashed()->paginate(15),
            'posts' => Post::onlyTrashed()->paginate(15),
            'pages' => Page::onlyTrashed()->paginate(15),
            'rentals' => Rental::onlyTrashed()->paginate(15),
            'users' => User::onlyTrashed()->paginate(15),
            default => abort(404)
        };

        return view('admin.trash.show', compact('items', 'type'));
    }

    public function restore($type, $id)
    {
        $model = match($type) {
            'categories' => Category::class,
            'posts' => Post::class,
            'pages' => Page::class,
            'rentals' => Rental::class,
            'users' => User::class,
            default => abort(404)
        };

        $model::withTrashed()->findOrFail($id)->restore();

        return back()->with('success', 'Öğe başarıyla geri yüklendi.');
    }

    public function forceDelete($type, $id)
    {
        $model = match($type) {
            'categories' => Category::class,
            'posts' => Post::class,
            'pages' => Page::class,
            'rentals' => Rental::class,
            'users' => User::class,
            default => abort(404)
        };

        $model::withTrashed()->findOrFail($id)->forceDelete();

        return back()->with('success', 'Öğe kalıcı olarak silindi.');
    }
}