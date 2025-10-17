<?php

namespace App\Modules\Language\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Language\Models\Language;
use App\Modules\Language\Models\LanguageOverride;
use App\Modules\Language\Services\TranslationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

/**
 * Language Override Controller
 * 
 * Joomla-style admin interface for managing translation overrides
 */
class LanguageOverrideController extends Controller
{
    /**
     * Display list of language overrides
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $locale = $request->get('locale');
        $domain = $request->get('domain');

        $query = LanguageOverride::query();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('key', 'like', "%{$search}%")
                  ->orWhere('value', 'like', "%{$search}%");
            });
        }

        if ($locale) {
            $query->where('locale', $locale);
        }

        if ($domain) {
            $query->where('domain', $domain);
        }

        $overrides = $query->orderBy('locale')
            ->orderBy('domain')
            ->orderBy('key')
            ->paginate(50);

        return view('admin.translations.index', compact('overrides'));
    }

    /**
     * Show form to create new override
     */
    public function create(Request $request): View
    {
        // Pre-fill from query params (useful from missing translations page)
        $prefill = (object) [
            'locale' => $request->get('locale', ''),
            'domain' => $request->get('domain', 'messages'),
            'key' => $request->get('key', ''),
            'value' => $request->get('reference', ''),
        ];

        return view('admin.translations.create', compact('prefill'));
    }

    /**
     * Store new language override
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'locale' => 'required|string|size:2',
            'domain' => 'required|string|max:50',
            'key' => 'required|string|max:255',
            'value' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $override = LanguageOverride::create([
            'locale' => $validated['locale'],
            'domain' => $validated['domain'],
            'key' => $validated['key'],
            'value' => $validated['value'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        // Clear translation cache
        TranslationService::clearCache($validated['domain'], $validated['locale']);

        return redirect()
            ->route('admin.translations.index')
            ->with('success', __('Translation override created successfully'));
    }

    /**
     * Show form to edit override
     */
    public function edit(LanguageOverride $override): View
    {
        return view('admin.translations.edit', compact('override'));
    }

    /**
     * Update language override
     */
    public function update(Request $request, LanguageOverride $override)
    {
        $validated = $request->validate([
            'value' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        $override->update([
            'value' => $validated['value'],
            'is_active' => $validated['is_active'] ?? $override->is_active,
        ]);

        // Clear translation cache
        TranslationService::clearCache($override->domain, $override->locale);

        return redirect()
            ->route('admin.translations.index')
            ->with('success', __('Translation override updated successfully'));
    }

    /**
     * Delete language override
     */
    public function destroy(LanguageOverride $override)
    {
        $domain = $override->domain;
        $locale = $override->locale;

        $override->delete();

        // Clear translation cache
        TranslationService::clearCache($domain, $locale);

        return redirect()
            ->route('admin.translations.index')
            ->with('success', __('Translation override deleted successfully'));
    }

    /**
     * Toggle override status
     */
    public function toggle(LanguageOverride $override)
    {
        $override->update([
            'is_active' => !$override->is_active,
        ]);

        // Clear translation cache
        TranslationService::clearCache($override->domain, $override->locale);

        return redirect()->back()
            ->with('success', __('Translation override status updated'));
    }

    /**
     * Clear all translation cache
     */
    public function clearCache(Request $request)
    {
        $domain = $request->get('domain');
        $locale = $request->get('locale');

        TranslationService::clearCache($domain, $locale);

        return redirect()->back()
            ->with('success', __('Translation cache cleared successfully'));
    }

    /**
     * Get translation statistics
     */
    public function statistics(): View
    {
        $stats = TranslationService::getStatistics();

        return view('admin.translations.statistics', compact('stats'));
    }

    /**
     * Get missing translations
     */
    public function missing(): View
    {
        $missing = TranslationService::getMissingTranslations();
        $domains = collect($missing)->flatMap(fn($locales) => array_keys($locales))->unique()->values()->all();

        return view('admin.translations.missing', compact('missing', 'domains'));
    }

    /**
     * Export overrides to JSON
     */
    public function export(Request $request)
    {
        $locale = $request->get('locale', app()->getLocale());
        $domain = $request->get('domain');

        $query = LanguageOverride::active()->forLocale($locale);

        if ($domain) {
            $query->forDomain($domain);
        }

        $overrides = $query->get()->map(function ($override) {
            return [
                'locale' => $override->locale,
                'domain' => $override->domain,
                'key' => $override->key,
                'value' => $override->value,
                'original_value' => $override->original_value,
            ];
        });

        $filename = "language-overrides-{$locale}" . ($domain ? "-{$domain}" : '') . '.json';

        return response()->json($overrides, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Import overrides from JSON
     */
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:json',
        ]);

        $json = file_get_contents($request->file('file')->getRealPath());
        $data = json_decode($json, true);

        if (!is_array($data)) {
            return back()->with('error', _t('invalid_import_file', 'admin'));
        }

        $count = 0;
        foreach ($data as $item) {
            if (isset($item['locale'], $item['domain'], $item['key'], $item['value'])) {
                LanguageOverride::setOverride(
                    $item['locale'],
                    $item['key'],
                    $item['value'],
                    $item['domain'],
                    $item['original_value'] ?? null
                );
                $count++;
            }
        }

        // Clear cache
        Cache::tags(['language-overrides'])->flush();

        return redirect()
            ->route('admin.language.overrides.index')
            ->with('success', _t('overrides_imported', 'admin', ['count' => $count]));
    }

    /**
     * Get available translation domains
     */
    protected function getAvailableDomains(): array
    {
        $domains = ['default', 'admin', 'frontend', 'messages', 'validation', 'auth'];

        // Get domains from lang files
        $langPath = lang_path(app()->getLocale());
        if (is_dir($langPath)) {
            $files = glob($langPath . '/*.php');
            foreach ($files as $file) {
                $domain = basename($file, '.php');
                if (!in_array($domain, $domains)) {
                    $domains[] = $domain;
                }
            }
        }

        // Get domains from existing overrides
        $existingDomains = LanguageOverride::select('domain')
            ->distinct()
            ->pluck('domain')
            ->toArray();

        $domains = array_unique(array_merge($domains, $existingDomains));
        sort($domains);

        return $domains;
    }
}
