<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Services\ImageService;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    protected $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|max:2048|dimensions:min_width=100,min_height=100',
            'show_in_menu' => 'boolean',
            'use_as_filter' => 'boolean',
            'status' => 'boolean',
            'meta_title' => 'required|max:60',
            'meta_description' => 'required|max:160',
            'seo_content' => 'nullable',
            'daily_rate' => 'required|numeric|min:0'
        ]);

        if ($request->hasFile('icon')) {
            // Güvenli dosya yükleme
            $iconPath = $this->fileUploadService->upload(
                $request->file('icon'),
                'categories',
                ['scan_virus' => true]
            );

            if ($iconPath) {
                // Resim optimizasyonu
                $versions = ImageService::optimize(
                    Storage::disk('public')->path($iconPath),
                    'categories'
                );
                $validated['icon'] = $versions['original'];

                // Orijinal yüklenen dosyayı sil
                Storage::delete($iconPath);
            } else {
                return back()->with('error', 'İkon yüklenirken bir hata oluştu.');
            }
        }

        Category::create($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla oluşturuldu.');
    }

    public function update(Request $request, Category $category)
    {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'parent_id' => 'nullable|exists:categories,id',
            'icon' => 'nullable|image|max:2048|dimensions:min_width=100,min_height=100',
            'show_in_menu' => 'boolean',
            'use_as_filter' => 'boolean',
            'status' => 'boolean',
            'meta_title' => 'required|max:60',
            'meta_description' => 'required|max:160',
            'seo_content' => 'nullable',
            'daily_rate' => 'required|numeric|min:0'
        ]);

        if ($request->hasFile('icon')) {
            // Eski ikonu sil
            if ($category->icon) {
                ImageService::delete($category->icon);
            }

            // Güvenli dosya yükleme
            $iconPath = $this->fileUploadService->upload(
                $request->file('icon'),
                'categories',
                ['scan_virus' => true]
            );

            if ($iconPath) {
                // Resim optimizasyonu
                $versions = ImageService::optimize(
                    Storage::disk('public')->path($iconPath),
                    'categories'
                );
                $validated['icon'] = $versions['original'];

                // Orijinal yüklenen dosyayı sil
                Storage::delete($iconPath);
            } else {
                return back()->with('error', 'İkon yüklenirken bir hata oluştu.');
            }
        }

        $category->update($validated);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Kategori başarıyla güncellendi.');
    }
}