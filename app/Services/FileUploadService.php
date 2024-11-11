<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class FileUploadService
{
    // İzin verilen MIME tipleri
    protected const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
        'application/pdf',
        'application/msword',
        'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
    ];

    // İzin verilen maksimum dosya boyutu (5MB)
    protected const MAX_FILE_SIZE = 5 * 1024 * 1024;

    // Tehlikeli dosya uzantıları
    protected const DANGEROUS_EXTENSIONS = [
        'php', 'php3', 'php4', 'phtml', 'exe', 'bat', 'sh', 'cmd', 'dll', 'jsp', 'jspx'
    ];

    /**
     * Dosyayı güvenli bir şekilde yükle
     */
    public function upload(UploadedFile $file, string $path, array $options = []): ?string
    {
        try {
            // Temel güvenlik kontrolleri
            $this->validateFile($file);

            // Dosya adını temizle ve benzersiz yap
            $filename = $this->sanitizeFilename($file->getClientOriginalName());
            $uniqueName = $this->generateUniqueName($filename);

            // Dosyayı yükle
            $fullPath = $file->storeAs($path, $uniqueName, [
                'disk' => $options['disk'] ?? 'public'
            ]);

            // Dosya bütünlüğünü kontrol et
            if (!$this->verifyFileIntegrity($fullPath)) {
                Storage::delete($fullPath);
                throw new \Exception('Dosya bütünlüğü doğrulanamadı.');
            }

            // Virüs taraması (opsiyonel)
            if (isset($options['scan_virus']) && $options['scan_virus']) {
                if (!$this->scanForVirus($fullPath)) {
                    Storage::delete($fullPath);
                    throw new \Exception('Dosya virüs içeriyor olabilir.');
                }
            }

            return $fullPath;
        } catch (\Exception $e) {
            \Log::error('Dosya yükleme hatası: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Dosyayı doğrula
     */
    protected function validateFile(UploadedFile $file): void
    {
        // Dosya boyutu kontrolü
        if ($file->getSize() > self::MAX_FILE_SIZE) {
            throw new \Exception('Dosya boyutu çok büyük.');
        }

        // MIME tipi kontrolü
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            throw new \Exception('Desteklenmeyen dosya tipi.');
        }

        // Uzantı kontrolü
        $extension = strtolower($file->getClientOriginalExtension());
        if (in_array($extension, self::DANGEROUS_EXTENSIONS)) {
            throw new \Exception('Tehlikeli dosya uzantısı.');
        }

        // Dosya içeriği ile MIME tipi uyumluluğu
        if (!$this->validateMimeType($file)) {
            throw new \Exception('Dosya tipi uyumsuzluğu.');
        }
    }

    /**
     * MIME tipini doğrula
     */
    protected function validateMimeType(UploadedFile $file): bool
    {
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mimeType = $finfo->file($file->getPathname());
        return $mimeType === $file->getMimeType();
    }

    /**
     * Dosya adını temizle
     */
    protected function sanitizeFilename(string $filename): string
    {
        // Türkçe karakterleri dönüştür
        $filename = str_replace(
            ['ı', 'İ', 'ğ', 'Ğ', 'ü', 'Ü', 'ş', 'Ş', 'ö', 'Ö', 'ç', 'Ç'],
            ['i', 'i', 'g', 'g', 'u', 'u', 's', 's', 'o', 'o', 'c', 'c'],
            $filename
        );

        // Sadece alfanumerik karakterler, nokta ve tire
        $filename = preg_replace('/[^a-zA-Z0-9.-]/', '', $filename);

        // Çoklu noktaları tekil yap
        $filename = preg_replace('/\.+/', '.', $filename);

        return $filename;
    }

    /**
     * Benzersiz dosya adı oluştur
     */
    protected function generateUniqueName(string $filename): string
    {
        $name = pathinfo($filename, PATHINFO_FILENAME);
        $extension = pathinfo($filename, PATHINFO_EXTENSION);
        
        return Str::slug($name) . '_' . Str::random(10) . '.' . $extension;
    }

    /**
     * Dosya bütünlüğünü kontrol et
     */
    protected function verifyFileIntegrity(string $path): bool
    {
        $file = Storage::get($path);
        
        // Dosya başlığını kontrol et
        $header = substr($file, 0, 8);
        
        // PHP dosya imzalarını kontrol et
        $phpSignatures = ['<?php', '<?=', '<%', '<script'];
        foreach ($phpSignatures as $signature) {
            if (stripos($header, $signature) !== false) {
                return false;
            }
        }

        // Dosya boyutunu kontrol et
        if (Storage::size($path) === 0) {
            return false;
        }

        return true;
    }

    /**
     * Virüs taraması yap (ClamAV entegrasyonu örneği)
     */
    protected function scanForVirus(string $path): bool
    {
        // ClamAV entegrasyonu örneği
        if (extension_loaded('clamav')) {
            $scanner = new \ClamAV\Scanner();
            return $scanner->scan(Storage::path($path)) === \ClamAV\Scanner::CLEAN;
        }

        return true;
    }

    /**
     * Dosyayı sil
     */
    public function delete(string $path): bool
    {
        try {
            return Storage::delete($path);
        } catch (\Exception $e) {
            \Log::error('Dosya silme hatası: ' . $e->getMessage());
            return false;
        }
    }
}