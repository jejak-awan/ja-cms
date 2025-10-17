<?php

namespace App\Modules\Language\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpFoundation\Response;

class LocalizeRoutes
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = $this->getLocaleFromUrl($request);
        
        if ($locale && $this->isSupported($locale)) {
            App::setLocale($locale);
        }
        
        return $next($request);
    }

    /**
     * Get locale from URL segment.
     *
     * @param Request $request
     * @return string|null
     */
    protected function getLocaleFromUrl(Request $request): ?string
    {
        $segments = $request->segments();
        
        if (empty($segments)) {
            return null;
        }
        
        $firstSegment = $segments[0];
        
        // Check if first segment is a valid locale
        if ($this->isSupported($firstSegment)) {
            return $firstSegment;
        }
        
        return null;
    }

    /**
     * Check if locale is supported.
     *
     * @param string|null $locale
     * @return bool
     */
    protected function isSupported(?string $locale): bool
    {
        if (!$locale) {
            return false;
        }
        
        $supported = array_keys(config('locales.supported', []));
        return in_array($locale, $supported);
    }
}