<?php

namespace App\Modules\Language\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * Priority order:
     * 1. URL segment (/en/articles)
     * 2. Session
     * 3. Cookie
     * 4. Browser language
     * 5. Default from config
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->detectLocale($request);
        
        // Validate locale is supported
        if (!$this->isSupported($locale)) {
            $locale = config('locales.default', 'id');
        }

        // Set application locale
        App::setLocale($locale);
        
        // Store in session if enabled
        if (config('locales.use_session', true)) {
            Session::put('locale', $locale);
        }

        // Prepare cookie response
        $response = $next($request);
        
        // Store in cookie if enabled
        if (config('locales.use_cookie', true)) {
            $cookieName = config('locales.cookie_name', 'locale');
            $cookieLifetime = config('locales.cookie_lifetime', 60 * 24 * 365);
            
            Cookie::queue($cookieName, $locale, $cookieLifetime);
        }

        return $response;
    }

    /**
     * Detect locale from multiple sources.
     */
    protected function detectLocale(Request $request): string
    {
        // 1. Check URL segment
        $urlLocale = $request->segment(1);
        if ($this->isSupported($urlLocale)) {
            return $urlLocale;
        }

        // 2. Check query parameter (?lang=en)
        if ($request->has('lang') && $this->isSupported($request->query('lang'))) {
            return $request->query('lang');
        }

        // 3. Check session
        if (config('locales.use_session') && Session::has('locale')) {
            $sessionLocale = Session::get('locale');
            if ($this->isSupported($sessionLocale)) {
                return $sessionLocale;
            }
        }

        // 4. Check cookie
        if (config('locales.use_cookie')) {
            $cookieName = config('locales.cookie_name', 'locale');
            $cookieLocale = Cookie::get($cookieName);
            if ($this->isSupported($cookieLocale)) {
                return $cookieLocale;
            }
        }

        // 5. Check browser Accept-Language header
        $browserLocale = $this->getBrowserLocale($request);
        if ($this->isSupported($browserLocale)) {
            return $browserLocale;
        }

        // 6. Return default
        return config('locales.default', 'id');
    }

    /**
     * Check if locale is supported.
     */
    protected function isSupported(?string $locale): bool
    {
        if (!$locale) {
            return false;
        }

        $supported = array_keys(config('locales.supported', []));
        return in_array($locale, $supported);
    }

    /**
     * Get locale from browser Accept-Language header.
     */
    protected function getBrowserLocale(Request $request): ?string
    {
        $acceptLanguage = $request->header('Accept-Language');
        
        if (!$acceptLanguage) {
            return null;
        }

        // Parse Accept-Language header (e.g., "en-US,en;q=0.9,id;q=0.8")
        preg_match_all('/([a-z]{2})(?:-[A-Z]{2})?/', $acceptLanguage, $matches);
        
        if (!empty($matches[1])) {
            foreach ($matches[1] as $lang) {
                if ($this->isSupported($lang)) {
                    return $lang;
                }
            }
        }

        return null;
    }
}
