<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Active Theme
    |--------------------------------------------------------------------------
    | 
    | Theme aktif untuk frontend/public
    |
    */
    'active' => env('THEME_ACTIVE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Active Admin Theme
    |--------------------------------------------------------------------------
    | 
    | Theme aktif untuk admin panel
    |
    */
    'admin_active' => env('ADMIN_THEME_ACTIVE', 'default'),

    /*
    |--------------------------------------------------------------------------
    | Theme Path
    |--------------------------------------------------------------------------
    | 
    | Path ke folder theme views dan assets
    | Untuk multi-theme support pada public dan admin
    |
    */
    'path' => resource_path('views/public'),          // Public theme views path
    'public_path' => public_path('themes/public'),    // Public theme assets path
    'admin_path' => public_path('themes/admin'),      // Admin theme assets path

    /*
    |--------------------------------------------------------------------------
    | Available Themes
    |--------------------------------------------------------------------------
    | 
    | Daftar theme yang tersedia
    |
    */
    'themes' => [
        'default' => [
            'name' => 'Default Theme',
            'description' => 'Clean and simple default theme',
            'version' => '1.0.0',
            'author' => 'Laravel CMS',
            'screenshot' => '/images/themes/default.png',
        ],
        'modern' => [
            'name' => 'Modern Theme',
            'description' => 'Modern and sleek theme',
            'version' => '1.0.0',
            'author' => 'Laravel CMS',
            'screenshot' => '/images/themes/modern.png',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Available Admin Themes
    |--------------------------------------------------------------------------
    | 
    | Daftar admin theme yang tersedia
    |
    */
    'admin_themes' => [
        'default' => [
            'name' => 'Default Admin',
            'description' => 'Professional admin theme with sidebar',
            'version' => '1.0.0',
            'author' => 'Laravel CMS',
            'screenshot' => '/images/admin-themes/default.png',
            'colors' => [
                'primary' => '#3B82F6',
                'sidebar' => '#1F2937',
            ],
        ],
        'dark' => [
            'name' => 'Dark Admin',
            'description' => 'Modern dark theme for admin',
            'version' => '1.0.0',
            'author' => 'Laravel CMS',
            'screenshot' => '/images/admin-themes/dark.png',
            'colors' => [
                'primary' => '#8B5CF6',
                'sidebar' => '#111827',
            ],
        ],
    ],
];
