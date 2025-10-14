<?php

use App\Services\LocaleService;
use Illuminate\Support\Facades\App;

if (!function_exists('current_locale')) {
    /**
     * Get current application locale.
     *
     * @return string
     */
    function current_locale(): string
    {
        return App::getLocale();
    }
}

if (!function_exists('set_locale')) {
    /**
     * Set application locale.
     *
     * @param string $locale
     * @return void
     */
    function set_locale(string $locale): void
    {
        LocaleService::set($locale);
    }
}

if (!function_exists('is_locale')) {
    /**
     * Check if current locale matches given locale.
     *
     * @param string $locale
     * @return bool
     */
    function is_locale(string $locale): bool
    {
        return current_locale() === $locale;
    }
}

if (!function_exists('active_languages')) {
    /**
     * Get all active languages.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    function active_languages()
    {
        return LocaleService::active();
    }
}

if (!function_exists('default_language')) {
    /**
     * Get default language.
     *
     * @return \App\Modules\Language\Models\Language|null
     */
    function default_language()
    {
        return LocaleService::default();
    }
}

if (!function_exists('localized_route')) {
    /**
     * Generate localized route URL.
     *
     * @param string $name
     * @param array $parameters
     * @param bool $absolute
     * @return string
     */
    function localized_route(string $name, array $parameters = [], bool $absolute = true): string
    {
        return LocaleService::route($name, $parameters, $absolute);
    }
}

if (!function_exists('alternate_urls')) {
    /**
     * Get alternate URLs for all languages (for hreflang tags).
     *
     * @param string $routeName
     * @param array $parameters
     * @return array
     */
    function alternate_urls(string $routeName, array $parameters = []): array
    {
        return LocaleService::alternateUrls($routeName, $parameters);
    }
}

if (!function_exists('trans_field')) {
    /**
     * Get translated field value from model.
     *
     * @param mixed $model
     * @param string $field
     * @param string|null $locale
     * @return mixed
     */
    function trans_field($model, string $field, ?string $locale = null)
    {
        if (!$model) {
            return null;
        }

        // If model has trans method (Translatable trait)
        if (method_exists($model, 'trans')) {
            return $model->trans($field, $locale);
        }

        // Otherwise try direct field access
        $locale = $locale ?? current_locale();
        $fieldName = "{$field}_{$locale}";
        
        return $model->$fieldName ?? $model->$field ?? null;
    }
}

if (!function_exists('trans_array')) {
    /**
     * Get translated array (useful for dropdowns).
     *
     * @param array $array
     * @param string $keyPrefix
     * @return array
     */
    function trans_array(array $array, string $keyPrefix = ''): array
    {
        $result = [];
        
        foreach ($array as $key => $value) {
            $transKey = $keyPrefix ? "{$keyPrefix}.{$key}" : $key;
            $result[$key] = __($transKey);
        }
        
        return $result;
    }
}

if (!function_exists('locale_flag')) {
    /**
     * Get flag emoji for locale.
     *
     * @param string|null $locale
     * @return string
     */
    function locale_flag(?string $locale = null): string
    {
        $locale = $locale ?? current_locale();
        $languages = config('locales.supported', []);
        
        return $languages[$locale]['flag'] ?? '🌐';
    }
}

if (!function_exists('locale_name')) {
    /**
     * Get language name for locale.
     *
     * @param string|null $locale
     * @param bool $native
     * @return string
     */
    function locale_name(?string $locale = null, bool $native = false): string
    {
        $locale = $locale ?? current_locale();
        $languages = config('locales.supported', []);
        
        if (!isset($languages[$locale])) {
            return $locale;
        }
        
        return $native 
            ? $languages[$locale]['native'] 
            : $languages[$locale]['name'];
    }
}

if (!function_exists('is_rtl')) {
    /**
     * Check if current locale is RTL (right-to-left).
     *
     * @param string|null $locale
     * @return bool
     */
    function is_rtl(?string $locale = null): bool
    {
        $locale = $locale ?? current_locale();
        $languages = config('locales.supported', []);
        
        return ($languages[$locale]['direction'] ?? 'ltr') === 'rtl';
    }
}

if (!function_exists('format_date_locale')) {
    /**
     * Format date according to current locale.
     *
     * @param mixed $date
     * @param string $format
     * @return string
     */
    function format_date_locale($date, string $format = 'medium'): string
    {
        if (!$date) {
            return '';
        }

        $carbon = $date instanceof \Carbon\Carbon ? $date : \Carbon\Carbon::parse($date);
        
        // Set locale for Carbon
        $carbon->locale(current_locale());
        
        return match($format) {
            'short' => $carbon->format('d/m/Y'),
            'medium' => $carbon->format('d M Y'),
            'long' => $carbon->isoFormat('D MMMM YYYY'),
            'full' => $carbon->isoFormat('dddd, D MMMM YYYY'),
            default => $carbon->format($format),
        };
    }
}

if (!function_exists('trans_choice_locale')) {
    /**
     * Translate with pluralization support.
     *
     * @param string $key
     * @param int $count
     * @param array $replace
     * @return string
     */
    function trans_choice_locale(string $key, int $count, array $replace = []): string
    {
        return trans_choice($key, $count, array_merge(['count' => $count], $replace));
    }
}
