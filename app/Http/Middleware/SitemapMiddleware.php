<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Sitemap\SitemapGenerator;
use Spatie\Sitemap\Tags\Url;
use Carbon\Carbon;

class SitemapMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        // Sitemap'i günlük olarak güncelle
        if (Carbon::now()->hour === 0 && !file_exists(public_path('sitemap.xml'))) {
            SitemapGenerator::create(config('app.url'))
                ->hasCrawled(function (Url $url) {
                    // Admin sayfalarını hariç tut
                    if (strpos($url->url, '/admin') !== false) {
                        return;
                    }

                    // Özel sayfalar için öncelik ve güncelleme sıklığı
                    if ($url->segment(1) === '') {
                        $url->setPriority(1.0)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_DAILY);
                    } elseif ($url->segment(1) === 'blog') {
                        $url->setPriority(0.8)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_WEEKLY);
                    } else {
                        $url->setPriority(0.6)
                            ->setChangeFrequency(Url::CHANGE_FREQUENCY_MONTHLY);
                    }

                    return $url;
                })
                ->writeToFile(public_path('sitemap.xml'));
        }

        return $response;
    }
}