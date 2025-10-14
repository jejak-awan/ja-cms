<?php

return [
    // Enable or disable caching globally
    'enabled' => env('CACHE_CUSTOM_ENABLED', true),

    // Default cache TTL (in seconds)
    'ttl' => env('CACHE_CUSTOM_TTL', 3600),

    // Enable debug mode (shows cache status/info in admin panel)
    'debug' => env('CACHE_CUSTOM_DEBUG', false),

    // Allow admin to flush all cache from panel
    'allow_flush' => env('CACHE_CUSTOM_ALLOW_FLUSH', true),

    // Optional: per-module cache settings
    'modules' => [
        'article' => [
            'enabled' => true,
            'ttl' => 1800,
        ],
        'category' => [
            'enabled' => true,
            'ttl' => 1800,
        ],
        'page' => [
            'enabled' => true,
            'ttl' => 1800,
        ],
        'tag' => [
            'enabled' => true,
            'ttl' => 1800,
        ],
        // Tambah modul lain jika perlu
    ],
];
