<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Services\StructuredDataService;
use App\Models\Setting;

class SeoMiddleware
{
    protected $structuredDataService;

    public function __construct(StructuredDataService $structuredDataService)
    {
        $this->structuredDataService = $structuredDataService;
    }

    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if ($response->status() === 200 && !$request->ajax()) {
            $content = $response->getContent();

            // Meta etiketleri ekle
            $metaTags = $this->getMetaTags($request);
            $content = str_replace('</head>', $metaTags . '</head>', $content);

            // Canonical URL ekle
            $canonical = url()->current();
            $content = str_replace('</head>', '<link rel="canonical" href="' . $canonical . '">' . '</head>', $content);

            // Structured data ekle
            $structuredData = $this->getStructuredData($request);
            if ($structuredData) {
                $content = str_replace('</head>', $structuredData . '</head>', $content);
            }

            $response->setContent($content);
        }

        return $response;
    }

    protected function getMetaTags(Request $request): string
    {
        $tags = [];

        // Temel meta etiketleri
        $tags[] = '<meta name="robots" content="' . $this->getRobotsContent($request) . '">';
        $tags[] = '<meta name="author" content="' . config('app.name') . '">';
        $tags[] = '<meta name="viewport" content="width=device-width, initial-scale=1">';

        // Open Graph meta etiketleri
        $tags[] = '<meta property="og:site_name" content="' . config('app.name') . '">';
        $tags[] = '<meta property="og:locale" content="tr_TR">';
        $tags[] = '<meta property="og:type" content="website">';
        $tags[] = '<meta property="og:url" content="' . url()->current() . '">';

        // Twitter Card meta etiketleri
        $tags[] = '<meta name="twitter:card" content="summary">';
        $tags[] = '<meta name="twitter:site" content="' . Setting::get('twitter_username') . '">';

        return implode("\n    ", $tags);
    }

    protected function getRobotsContent(Request $request): string
    {
        if ($request->is('admin/*') || 
            $request->is('login') || 
            $request->is('register') || 
            $request->is('password/*')) {
            return 'noindex, nofollow';
        }

        return 'index, follow';
    }

    protected function getStructuredData(Request $request): ?string
    {
        // Sayfa türüne göre structured data oluştur
        if ($request->is('/')) {
            return $this->structuredDataService->getHomePageSchema();
        }

        return null;
    }
}