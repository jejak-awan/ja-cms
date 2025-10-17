<?php

namespace App\Modules\Language\Services;

use App\Modules\Language\Models\Language;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;

class LocaleService
{
    /**
     * Get all active languages.
     */
    public static function active(): \Illuminate\Database\Eloquent\Collection
    {
        return Cache::remember('languages.active', 3600, function () {
            return Language::where('is_active', true)
                ->orderBy('order')
                ->get();
        });
    }

    /**
     * Get default language.
     */
    public static function default(): ?Language
    {
        return Cache::remember('languages.default', 3600, function () {
            return Language::default();
        });
    }

    /**
     * Get language by code.
     */
    public static function byCode(string $code): ?Language
    {
        return Cache::remember("languages.code.{$code}", 3600, function () use ($code) {
            return Language::byCode($code);
        });
    }

    /**
     * Get current locale.
     */
    public static function current(): string
    {
        return App::getLocale();
    }

    /**
     * Set current locale.
     */
    public static function set(string $locale): void
    {
        if (self::isSupported($locale)) {
            App::setLocale($locale);
        }
    }

    /**
     * Check if locale is supported.
     */
    public static function isSupported(string $locale): bool
    {
        $supported = array_keys(config('locales.supported', []));
        return in_array($locale, $supported);
    }

    /**
     * Get all supported locales from config.
     */
    public static function supported(): array
    {
        return config('locales.supported', []);
    }

    /**
     * Get localized route name.
     * Example: route('articles.show', ['locale' => 'en', 'article' => $article])
     */
    public static function route(string $name, array $parameters = [], bool $absolute = true): string
    {
        $locale = $parameters['locale'] ?? self::current();
        
        // Remove locale from parameters as it's handled by route prefix
        unset($parameters['locale']);
        
        // If hiding default locale in URL
        if (config('locales.hide_default_in_url') && $locale === config('locales.default')) {
            $locale = null;
        }
        
        $routeName = $locale ? "{$locale}.{$name}" : $name;
        
        return route($routeName, $parameters, $absolute);
    }

    /**
     * Generate alternate URLs for all languages (for hreflang).
     */
    public static function alternateUrls(string $routeName, array $parameters = []): array
    {
        $urls = [];
        $languages = self::active();
        
        foreach ($languages as $language) {
            $params = array_merge($parameters, ['locale' => $language->code]);
            $urls[$language->code] = self::route($routeName, $params);
        }
        
        return $urls;
    }

    /**
     * Clear language cache.
     */
    public static function clearCache(): void
    {
        Cache::forget('languages.active');
        Cache::forget('languages.default');
        
        $supported = array_keys(config('locales.supported', []));
        foreach ($supported as $code) {
            Cache::forget("languages.code.{$code}");
        }
    }
}
