<?php

namespace App\Services;

use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    const THUMB_WIDTH = 300;
    const MEDIUM_WIDTH = 800;
    const QUALITY = 80;
    const FORMAT = 'webp';

    /**
     * Resmi optimize et ve kaydet
     */
    public static function optimize($image, string $path, array $sizes = ['thumb', 'medium', 'original'])
    {
        $filename = Str::random(40) . '.' . self::FORMAT;
        $fullPath = $path . '/' . $filename;

        // Orijinal resmi optimize et
        $img = Image::make($image);
        
        $versions = [];

        foreach ($sizes as $size) {
            $sizePath = $path . '/' . $size;
            Storage::makeDirectory('public/' . $sizePath);

            switch ($size) {
                case 'thumb':
                    $versions[$size] = self::saveThumb($img, $sizePath . '/' . $filename);
                    break;
                case 'medium':
                    $versions[$size] = self::saveMedium($img, $sizePath . '/' . $filename);
                    break;
                case 'original':
                    $versions[$size] = self::saveOriginal($img, $sizePath . '/' . $filename);
                    break;
            }
        }

        return $versions;
    }

    /**
     * Küçük resim oluştur
     */
    private static function saveThumb($img, string $path): string
    {
        $thumb = clone $img;
        $thumb->resize(self::THUMB_WIDTH, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        self::optimizeAndSave($thumb, $path);
        return $path;
    }

    /**
     * Orta boy resim oluştur
     */
    private static function saveMedium($img, string $path): string
    {
        $medium = clone $img;
        $medium->resize(self::MEDIUM_WIDTH, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        
        self::optimizeAndSave($medium, $path);
        return $path;
    }

    /**
     * Orijinal resmi optimize et
     */
    private static function saveOriginal($img, string $path): string
    {
        self::optimizeAndSave($img, $path);
        return $path;
    }

    /**
     * Resmi optimize et ve kaydet
     */
    private static function optimizeAndSave($img, string $path): void
    {
        $img->encode(self::FORMAT, self::QUALITY);
        Storage::put('public/' . $path, $img->stream());
    }

    /**
     * Resmi sil
     */
    public static function delete(string $path): void
    {
        $directory = dirname($path);
        $filename = basename($path);

        foreach (['thumb', 'medium', 'original'] as $size) {
            Storage::delete('public/' . $directory . '/' . $size . '/' . $filename);
        }
    }
}