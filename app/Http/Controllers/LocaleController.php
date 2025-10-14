<?php

namespace App\Http\Controllers;

use App\Services\LocaleService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class LocaleController extends Controller
{
    /**
     * Switch application locale
     *
     * @param Request $request
     * @param string $locale
     * @return RedirectResponse
     */
    public function switch(Request $request, string $locale): RedirectResponse
    {
        // Validate locale against supported list
        if (!LocaleService::isSupported($locale)) {
            return back()->with('error', __('messages.invalid_locale'));
        }

        // Set application locale immediately
        set_locale($locale);

        // Store in session for subsequent requests
        if (config('locales.use_session', true)) {
            Session::put('locale', $locale);
        }

        // Persist via cookie when enabled
        if (config('locales.use_cookie', true)) {
            Cookie::queue(
                config('locales.cookie_name', 'locale'),
                $locale,
                config('locales.cookie_lifetime', 60 * 24 * 365)
            );
        }

        $language = LocaleService::byCode($locale);

        $message = __('messages.locale_switched', [
            'locale' => $language?->native_name
                ?? $language?->name
                ?? locale_name($locale),
            'flag' => $language?->flag
                ?? locale_flag($locale),
        ]);

        return back()->with('success', $message);
    }

    /**
     * Get current locale info (for AJAX requests)
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function current()
    {
        $languages = active_languages();

        if (!$languages instanceof \Illuminate\Support\Collection) {
            $languages = collect($languages ?? []);
        }

        return response()->json([
            'locale' => current_locale(),
            'name' => locale_name(),
            'flag' => locale_flag(),
            'direction' => is_rtl() ? 'rtl' : 'ltr',
            'available_locales' => $languages->map(function ($language, $key) {
                if ($language instanceof \App\Modules\Language\Models\Language) {
                    $code = $language->code;
                    $name = $language->native_name ?: $language->name;
                    $flag = $language->flag;
                    $direction = $language->direction;
                } elseif (is_array($language)) {
                    $code = $language['code'] ?? $key;
                    $name = $language['native_name'] ?? $language['name'] ?? null;
                    $flag = $language['flag'] ?? null;
                    $direction = $language['direction'] ?? null;
                } else {
                    $code = is_string($language) ? $language : $key;
                    $name = null;
                    $flag = null;
                    $direction = null;
                }

                if (!$code) {
                    return null;
                }

                return [
                    'code' => $code,
                    'name' => $name ?? locale_name($code),
                    'native' => $language instanceof \App\Modules\Language\Models\Language
                        ? ($language->native_name ?? locale_name($code, true))
                        : ($language['native_name'] ?? locale_name($code, true)),
                    'flag' => $flag ?? locale_flag($code),
                    'direction' => $direction ?? config("locales.supported.{$code}.direction", 'ltr'),
                ];
            })->filter()->values(),
        ]);
    }
}
