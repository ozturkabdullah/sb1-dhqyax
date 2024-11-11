<?php

namespace App\Traits;

trait HasBreadcrumbs
{
    protected function getBreadcrumbs(): array
    {
        $breadcrumbs = [];
        
        if (isset($this->category)) {
            // Kategori hiyerarşisini oluştur
            $category = $this->category;
            $path = [];
            
            while ($category) {
                array_unshift($path, [
                    'title' => $category->name,
                    'url' => $category->parent_id ? route('category.show', $category->slug) : null
                ]);
                $category = $category->parent;
            }
            
            $breadcrumbs = array_merge($breadcrumbs, $path);
        }

        // Mevcut sayfa başlığını ekle
        if (isset($this->title)) {
            $breadcrumbs[] = [
                'title' => $this->title
            ];
        }

        return $breadcrumbs;
    }
}