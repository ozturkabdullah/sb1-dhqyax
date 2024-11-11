<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class CleanTempFiles extends Command
{
    protected $signature = 'temp:clean';
    protected $description = 'Geçici dosyaları temizle';

    public function handle()
    {
        $this->cleanUploads();
        $this->cleanCache();
        $this->cleanLogs();
    }

    protected function cleanUploads()
    {
        // 24 saatten eski geçici yüklemeleri temizle
        $tempPath = Storage::disk('local')->path('temp');
        if (is_dir($tempPath)) {
            $files = glob($tempPath . '/*');
            foreach ($files as $file) {
                if (is_file($file) && (time() - filemtime($file) > 86400)) {
                    unlink($file);
                }
            }
        }
    }

    protected function cleanCache()
    {
        // 1 haftadan eski önbellek dosyalarını temizle
        $cachePath = storage_path('framework/cache');
        $this->cleanDirectory($cachePath, 7);
    }

    protected function cleanLogs()
    {
        // 30 günden eski log dosyalarını temizle
        $logPath = storage_path('logs');
        $this->cleanDirectory($logPath, 30);
    }

    protected function cleanDirectory($path, $days)
    {
        if (is_dir($path)) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                if (is_file($file) && (time() - filemtime($file) > $days * 86400)) {
                    unlink($file);
                }
            }
        }
    }
}