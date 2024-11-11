<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class XssProtection
{
    public function handle(Request $request, Closure $next)
    {
        if (!in_array(strtolower($request->method()), ['put', 'post'])) {
            return $next($request);
        }

        $input = $request->all();

        array_walk_recursive($input, function(&$input) {
            if (!is_array($input)) {
                // HTML içeriği olması gereken alanları kontrol et
                if (!$this->isHtmlAllowed($input)) {
                    $input = strip_tags($input);
                }
                
                // XSS koruması
                $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');
            }
        });

        $request->merge($input);

        return $next($request);
    }

    protected function isHtmlAllowed($input)
    {
        // HTML içeriğine izin verilen alanlar
        $allowedFields = [
            'content',
            'seo_content',
            'description'
        ];

        foreach ($allowedFields as $field) {
            if (strpos(request()->path(), $field) !== false) {
                return true;
            }
        }

        return false;
    }
}