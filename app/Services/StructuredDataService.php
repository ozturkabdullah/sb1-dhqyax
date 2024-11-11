<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Post;
use App\Models\Setting;

class StructuredDataService
{
    /**
     * Ana sayfa için structured data
     */
    public function getHomePageSchema(): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => config('app.name'),
            'url' => url('/'),
            'logo' => asset('storage/' . Setting::get('site_logo')),
            'description' => Setting::get('site_description'),
            'address' => [
                '@type' => 'PostalAddress',
                'addressLocality' => Setting::get('contact_city'),
                'addressRegion' => Setting::get('contact_district'),
                'streetAddress' => Setting::get('contact_address'),
                'addressCountry' => 'TR'
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => Setting::get('contact_phone'),
                'contactType' => 'customer service',
                'email' => Setting::get('contact_email'),
                'areaServed' => 'TR',
                'availableLanguage' => ['Turkish']
            ],
            'sameAs' => $this->getSocialLinks()
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Kategori sayfası için structured data
     */
    public function getCategorySchema(Category $category): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Service',
            'name' => $category->name,
            'description' => $category->meta_description,
            'provider' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url' => url('/')
            ],
            'areaServed' => [
                '@type' => 'City',
                'name' => 'Ankara'
            ],
            'category' => $category->parent ? $category->parent->name : null,
            'offers' => [
                '@type' => 'Offer',
                'price' => $category->daily_rate,
                'priceCurrency' => 'TRY',
                'priceValidUntil' => date('Y-m-d', strtotime('+1 year')),
                'availability' => $category->isCurrentlyRented() ? 'https://schema.org/OutOfStock' : 'https://schema.org/InStock'
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Blog yazısı için structured data
     */
    public function getPostSchema(Post $post): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $post->title,
            'description' => $post->meta_description,
            'image' => $post->cover_image ? asset('storage/' . $post->cover_image) : null,
            'datePublished' => $post->created_at->toIso8601String(),
            'dateModified' => $post->updated_at->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'url' => url('/')
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => asset('storage/' . Setting::get('site_logo'))
                ]
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => url()->current()
            ],
            'keywords' => $post->tags->pluck('name')->implode(', ')
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * İletişim sayfası için structured data
     */
    public function getContactPageSchema(): string
    {
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'name' => 'İletişim',
            'description' => 'Teknik servis hizmetlerimiz için bizimle iletişime geçin.',
            'mainEntity' => [
                '@type' => 'Organization',
                'name' => config('app.name'),
                'telephone' => Setting::get('contact_phone'),
                'email' => Setting::get('contact_email'),
                'address' => [
                    '@type' => 'PostalAddress',
                    'addressLocality' => Setting::get('contact_city'),
                    'addressRegion' => Setting::get('contact_district'),
                    'streetAddress' => Setting::get('contact_address'),
                    'addressCountry' => 'TR'
                ]
            ]
        ];

        return json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT);
    }

    /**
     * Sosyal medya bağlantılarını al
     */
    protected function getSocialLinks(): array
    {
        $links = [];

        if ($facebook = Setting::get('facebook_url')) {
            $links[] = $facebook;
        }
        if ($twitter = Setting::get('twitter_url')) {
            $links[] = $twitter;
        }
        if ($instagram = Setting::get('instagram_url')) {
            $links[] = $instagram;
        }
        if ($linkedin = Setting::get('linkedin_url')) {
            $links[] = $linkedin;
        }

        return $links;
    }
}