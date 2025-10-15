<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use App\Modules\Language\Models\Language;
use App\Services\LocaleService;

class DetectLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip if locale already set in session
        if (session()->has('locale')) {
            $locale = session('locale');
            if (LocaleService::isSupported($locale)) {
                App::setLocale($locale);
                return $next($request);
            }
        }

        // Auto-detect browser language
        $this->detectBrowserLanguage($request);

        return $next($request);
    }

    /**
     * Detect browser language and set locale
     */
    private function detectBrowserLanguage(Request $request)
    {
        $browserLanguages = $this->getBrowserLanguages($request);
        $supportedLanguages = $this->getSupportedLanguages();
        $defaultLanguage = $this->getDefaultLanguage();

        // Check if browser language is supported
        foreach ($browserLanguages as $lang) {
            if (in_array($lang, $supportedLanguages)) {
                $this->setLocale($lang);
                return;
            }
        }

        // Fallback to default language
        $this->setLocale($defaultLanguage);
    }

    /**
     * Get browser languages from Accept-Language header
     */
    private function getBrowserLanguages(Request $request)
    {
        $acceptLanguage = $request->header('Accept-Language', '');
        $languages = [];

        if ($acceptLanguage) {
            $parts = explode(',', $acceptLanguage);
            foreach ($parts as $part) {
                $lang = trim(explode(';', $part)[0]);
                // Extract language code (e.g., 'en-US' -> 'en')
                $langCode = explode('-', $lang)[0];
                $languages[] = $langCode;
            }
        }

        return $languages;
    }

    /**
     * Get supported languages from database or config
     */
    private function getSupportedLanguages()
    {
        // Try to get from database first
        $activeLanguages = Language::where('is_active', true)->pluck('code')->toArray();
        
        if (!empty($activeLanguages)) {
            return $activeLanguages;
        }

        // Fallback to config
        return array_keys(config('locales.supported', ['id', 'en']));
    }

    /**
     * Get default language from database or config
     */
    private function getDefaultLanguage()
    {
        // Try to get from database first
        $defaultLanguage = Language::where('is_default', true)->first();
        
        if ($defaultLanguage) {
            return $defaultLanguage->code;
        }

        // Fallback to config
        return config('locales.fallback', 'id');
    }

    /**
     * Set locale and session
     */
    private function setLocale(string $locale)
    {
        if (LocaleService::isSupported($locale)) {
            App::setLocale($locale);
            session(['locale' => $locale]);
        }
    }
}
