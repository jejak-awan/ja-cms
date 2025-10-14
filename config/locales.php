<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Supported Locales
    |--------------------------------------------------------------------------
    |
    | Define all locales that your application supports.
    | Each locale should have a name, native name, and optional flag.
    |
    */

    'supported' => [
        'id' => [
            'name' => 'Indonesian',
            'native' => 'Bahasa Indonesia',
            'flag' => '🇮🇩',
            'direction' => 'ltr',
        ],
        'en' => [
            'name' => 'English',
            'native' => 'English',
            'flag' => '🇬🇧',
            'direction' => 'ltr',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Default Locale
    |--------------------------------------------------------------------------
    |
    | The default locale that will be used if no locale is specified.
    |
    */

    'default' => env('APP_LOCALE', 'id'),

    /*
    |--------------------------------------------------------------------------
    | Fallback Locale
    |--------------------------------------------------------------------------
    |
    | The fallback locale that will be used when a translation is missing.
    |
    */

    'fallback' => env('APP_FALLBACK_LOCALE', 'en'),

    /*
    |--------------------------------------------------------------------------
    | Hide Default Locale in URL
    |--------------------------------------------------------------------------
    |
    | If true, the default locale will not appear in URLs.
    | Example: /artikel instead of /id/artikel
    |
    */

    'hide_default_in_url' => true,

    /*
    |--------------------------------------------------------------------------
    | Use Locale Session
    |--------------------------------------------------------------------------
    |
    | Store user's locale preference in session for persistence.
    |
    */

    'use_session' => true,

    /*
    |--------------------------------------------------------------------------
    | Use Cookie for Locale
    |--------------------------------------------------------------------------
    |
    | Store user's locale preference in a cookie.
    |
    */

    'use_cookie' => true,
    'cookie_name' => 'locale',
    'cookie_lifetime' => 60 * 24 * 365, // 1 year

    /*
    |--------------------------------------------------------------------------
    | Translatable Models
    |--------------------------------------------------------------------------
    |
    | Models that support multi-language content.
    |
    */

    'translatable_models' => [
        'Article' => [
            'fields' => ['title', 'excerpt', 'content'],
        ],
        'Category' => [
            'fields' => ['name', 'description'],
        ],
        'Page' => [
            'fields' => ['title', 'content'],
        ],
    ],

];
