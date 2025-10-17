<?php

namespace App\Modules\Language\Services;

use App\Modules\Language\Models\LanguageOverride;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

/**
 * Translation Service - WordPress-style text domain system
 * 
 * Features:
 * - Domain-based translation loading (like WordPress)
 * - Translation caching for performance
 * - Missing translation tracking
 * - Dynamic translation loading
 * - Joomla-style database overrides
 */
class TranslationService
{
    /**
     * Loaded text domains
     */
    protected static array $loadedDomains = [];

    /**
     * Missing translations tracker
     */
    protected static array $missingTranslations = [];

    /**
     * Cache duration in minutes
     */
    protected static int $cacheDuration = 60;

    /**
     * Translate string with text domain support (WordPress-style)
     * 
     * @param string $key Translation key
     * @param string $domain Text domain (e.g., 'admin', 'frontend', 'article-module')
     * @param array $replace Replacement parameters
     * @param string|null $locale Locale override
     * @return string
     */
    public static function trans(string $key, string $domain = 'default', array $replace = [], ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        
        // Check for database overrides first (Joomla-style)
        $override = self::getOverride($key, $domain, $locale);
        if ($override !== null) {
            return self::replaceParameters($override, $replace);
        }

        // Load domain translations if not already loaded
        if (!isset(self::$loadedDomains[$domain][$locale])) {
            self::loadDomain($domain, $locale);
        }

        // Build full translation key
        $fullKey = $domain === 'default' ? $key : "{$domain}.{$key}";
        
        // Get translation
        $translation = __($fullKey, $replace, $locale);

        // Track missing translations in non-production
        if ($translation === $fullKey && config('app.debug')) {
            self::trackMissing($key, $domain, $locale);
        }

        return $translation;
    }

    /**
     * Load text domain translations
     * 
     * @param string $domain
     * @param string $locale
     * @return void
     */
    protected static function loadDomain(string $domain, string $locale): void
    {
        $cacheKey = "translations.{$domain}.{$locale}";
        
        $translations = Cache::remember($cacheKey, self::$cacheDuration * 60, function () use ($domain, $locale) {
            $langPath = lang_path("{$locale}/{$domain}.php");
            
            if (File::exists($langPath)) {
                return require $langPath;
            }
            
            return [];
        });

        self::$loadedDomains[$domain][$locale] = true;
    }

    /**
     * Get override from database (Joomla-style)
     * 
     * @param string $key
     * @param string $domain
     * @param string $locale
     * @return string|null
     */
    public static function getOverride(string $key, string $domain, string $locale): ?string
    {
        $cacheKey = "translation.override.{$locale}.{$domain}.{$key}";
        
        return Cache::remember($cacheKey, self::$cacheDuration * 60, function () use ($key, $domain, $locale) {
            $override = LanguageOverride::active()
                ->where('locale', $locale)
                ->where('domain', $domain)
                ->where('key', $key)
                ->first();
            
            return $override?->value;
        });
    }

    /**
     * Set translation override (Joomla-style)
     * 
     * @param string $key
     * @param string $value
     * @param string $domain
     * @param string $locale
     * @param string|null $originalValue
     * @return LanguageOverride
     */
    public static function setOverride(
        string $key,
        string $value,
        string $domain = 'default',
        string $locale = null,
        ?string $originalValue = null
    ): LanguageOverride {
        $locale = $locale ?? app()->getLocale();
        
        $override = LanguageOverride::updateOrCreate(
            [
                'locale' => $locale,
                'domain' => $domain,
                'key' => $key,
            ],
            [
                'value' => $value,
                'original_value' => $originalValue ?? self::trans($key, $domain, [], $locale),
                'status' => 'active',
                'created_by' => auth()->id(),
            ]
        );

        // Clear cache
        self::clearCache($domain, $locale);
        
        return $override;
    }

    /**
     * Remove translation override
     * 
     * @param string $key
     * @param string $domain
     * @param string $locale
     * @return bool
     */
    public static function removeOverride(string $key, string $domain = 'default', ?string $locale = null): bool
    {
        $locale = $locale ?? app()->getLocale();
        
        $deleted = LanguageOverride::where('locale', $locale)
            ->where('domain', $domain)
            ->where('key', $key)
            ->delete();

        if ($deleted) {
            self::clearCache($domain, $locale);
        }

        return $deleted > 0;
    }

    /**
     * Replace parameters in translation string
     * 
     * @param string $string
     * @param array $replace
     * @return string
     */
    protected static function replaceParameters(string $string, array $replace): string
    {
        foreach ($replace as $key => $value) {
            $string = str_replace(":{$key}", $value, $string);
        }
        
        return $string;
    }

    /**
     * Track missing translation
     * 
     * @param string $key
     * @param string $domain
     * @param string $locale
     * @return void
     */
    protected static function trackMissing(string $key, string $domain, string $locale): void
    {
        $trackKey = "{$domain}:{$key}:{$locale}";
        
        if (!isset(self::$missingTranslations[$trackKey])) {
            self::$missingTranslations[$trackKey] = [
                'key' => $key,
                'domain' => $domain,
                'locale' => $locale,
                'count' => 0,
                'first_seen' => now(),
            ];
        }
        
        self::$missingTranslations[$trackKey]['count']++;
        self::$missingTranslations[$trackKey]['last_seen'] = now();

        // Log to file
        Log::channel('daily')->warning("Missing translation: {$domain}.{$key} [{$locale}]");
    }

    /**
     * Get missing translations
     * 
     * @return array
     */
    public static function getMissingTranslations(): array
    {
        return self::$missingTranslations;
    }

    /**
     * Get translation statistics
     * 
     * @return array
     */
    public static function getStatistics(): array
    {
        $locales = array_keys(config('locales.supported', []));
        $stats = [];

        foreach ($locales as $locale) {
            $total = self::countTranslations($locale);
            $missing = count(array_filter(self::$missingTranslations, function ($item) use ($locale) {
                return $item['locale'] === $locale;
            }));
            
            $stats[$locale] = [
                'total' => $total,
                'missing' => $missing,
                'translated' => $total - $missing,
                'percentage' => $total > 0 ? round((($total - $missing) / $total) * 100, 2) : 0,
            ];
        }

        return $stats;
    }

    /**
     * Count translations for a locale
     * 
     * @param string $locale
     * @return int
     */
    protected static function countTranslations(string $locale): int
    {
        $langPath = lang_path($locale);
        
        if (!File::isDirectory($langPath)) {
            return 0;
        }

        $count = 0;
        $files = File::files($langPath);

        foreach ($files as $file) {
            if ($file->getExtension() === 'php') {
                $translations = require $file->getPathname();
                $count += self::countRecursive($translations);
            }
        }

        return $count;
    }

    /**
     * Count translations recursively
     * 
     * @param array $array
     * @return int
     */
    protected static function countRecursive(array $array): int
    {
        $count = 0;
        
        foreach ($array as $value) {
            if (is_array($value)) {
                $count += self::countRecursive($value);
            } else {
                $count++;
            }
        }

        return $count;
    }

    /**
     * Preload multiple domains
     * 
     * @param array $domains
     * @param string|null $locale
     * @return void
     */
    public static function preload(array $domains, ?string $locale = null): void
    {
        $locale = $locale ?? app()->getLocale();
        
        foreach ($domains as $domain) {
            if (!isset(self::$loadedDomains[$domain][$locale])) {
                self::loadDomain($domain, $locale);
            }
        }
    }

    /**
     * Export translations to JSON for JavaScript
     * 
     * @param string $domain
     * @param string|null $locale
     * @return string
     */
    public static function exportToJson(string $domain = 'default', ?string $locale = null): string
    {
        $locale = $locale ?? app()->getLocale();
        $cacheKey = "translations.json.{$domain}.{$locale}";
        
        return Cache::remember($cacheKey, self::$cacheDuration * 60, function () use ($domain, $locale) {
            $langPath = lang_path("{$locale}/{$domain}.php");
            
            if (!File::exists($langPath)) {
                return json_encode([]);
            }

            $translations = require $langPath;
            
            // Apply database overrides
            $overrides = LanguageOverride::active()
                ->where('locale', $locale)
                ->where('domain', $domain)
                ->get();

            foreach ($overrides as $override) {
                $translations[$override->key] = $override->value;
            }

            return json_encode($translations, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        });
    }

    /**
     * Clear translation cache
     * 
     * @param string|null $domain
     * @param string|null $locale
     * @return void
     */
    public static function clearCache(?string $domain = null, ?string $locale = null): void
    {
        if ($domain && $locale) {
            // Clear specific domain and locale
            Cache::forget("translations.{$domain}.{$locale}");
            Cache::forget("translations.json.{$domain}.{$locale}");
            
            // Clear all override caches for this domain/locale
            Cache::tags(["translation.override.{$locale}.{$domain}"])->flush();
        } elseif ($domain) {
            // Clear all locales for this domain
            $locales = array_keys(config('locales.supported', []));
            foreach ($locales as $loc) {
                self::clearCache($domain, $loc);
            }
        } elseif ($locale) {
            // Clear all domains for this locale
            $langPath = lang_path($locale);
            if (File::isDirectory($langPath)) {
                $files = File::files($langPath);
                foreach ($files as $file) {
                    $dom = $file->getFilenameWithoutExtension();
                    self::clearCache($dom, $locale);
                }
            }
        } else {
            // Clear all translation caches
            Cache::tags(['translations'])->flush();
        }
    }
}
