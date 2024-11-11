<?php

return [
    /*
     * Sitemap dosyasının oluşturulacağı dizin
     */
    'path' => public_path('sitemap.xml'),

    /*
     * Sitemap'te gösterilmeyecek URL'ler
     */
    'exclude' => [
        '/admin/*',
        '/login',
        '/register',
        '/password/*',
        '/email/*',
    ],

    /*
     * Sitemap'in ne sıklıkla güncelleneceği (dakika)
     */
    'cache_duration' => 60,

    /*
     * Sitemap'te gösterilecek maksimum URL sayısı
     */
    'max_urls' => 50000,
];