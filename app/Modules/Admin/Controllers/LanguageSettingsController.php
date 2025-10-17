<?php

namespace App\Modules\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Language\Models\Language;
use App\Modules\Language\Services\LocaleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class LanguageSettingsController extends Controller
{
    /**
     * Display language settings
     */
    public function index()
    {
        $languages = Language::orderBy('is_default', 'desc')
            ->orderBy('order')
            ->get();

        $defaultLanguage = Language::where('is_default', true)->first();
        $activeLanguages = Language::where('is_active', true)->get();

        return view('admin.settings.languages', compact(
            'languages',
            'defaultLanguage',
            'activeLanguages'
        ));
    }

    /**
     * Update language settings
     */
    public function update(Request $request)
    {
        $request->validate([
            'default_language' => 'required|exists:languages,code',
            'active_languages' => 'array',
            'active_languages.*' => 'exists:languages,code',
        ]);

        DB::transaction(function () use ($request) {
            // Update default language
            Language::where('is_default', true)->update(['is_default' => false]);
            Language::where('code', $request->default_language)->update(['is_default' => true]);

            // Update active languages
            Language::query()->update(['is_active' => false]);
            if ($request->has('active_languages')) {
                Language::whereIn('code', $request->active_languages)->update(['is_active' => true]);
            }

            // Update language order
            if ($request->has('language_order')) {
                foreach ($request->language_order as $index => $languageId) {
                    Language::where('id', $languageId)->update(['order' => $index]);
                }
            }
        });

        // Clear cache
        Cache::forget('languages.active');
        Cache::forget('languages.default');

        return redirect()->back()->with('success', __('admin.language_settings_updated'));
    }

    /**
     * Toggle language status
     */
    public function toggleStatus(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        
        $language->update([
            'is_active' => !$language->is_active
        ]);

        // Clear cache
        Cache::forget('languages.active');

        return response()->json([
            'success' => true,
            'is_active' => $language->is_active,
            'message' => $language->is_active 
                ? __('admin.language_activated') 
                : __('admin.language_deactivated')
        ]);
    }

    /**
     * Set default language
     */
    public function setDefault(Request $request, $id)
    {
        $language = Language::findOrFail($id);
        
        DB::transaction(function () use ($language) {
            Language::where('is_default', true)->update(['is_default' => false]);
            $language->update(['is_default' => true, 'is_active' => true]);
        });

        // Clear cache
        Cache::forget('languages.default');
        Cache::forget('languages.active');

        return response()->json([
            'success' => true,
            'message' => __('admin.default_language_updated')
        ]);
    }

    /**
     * Update language order
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*' => 'integer|exists:languages,id'
        ]);

        foreach ($request->order as $index => $languageId) {
            Language::where('id', $languageId)->update(['order' => $index]);
        }

        return response()->json([
            'success' => true,
            'message' => __('admin.language_order_updated')
        ]);
    }

    /**
     * Get language statistics
     */
    public function statistics()
    {
        $stats = [
            'total_languages' => Language::count(),
            'active_languages' => Language::where('is_active', true)->count(),
            'default_language' => Language::where('is_default', true)->first(),
            'language_usage' => $this->getLanguageUsageStats(),
        ];

        return response()->json($stats);
    }

    /**
     * Get language usage statistics
     */
    private function getLanguageUsageStats()
    {
        // This would typically come from user sessions or content analysis
        // For now, return mock data
        return [
            'id' => 65, // Indonesian usage percentage
            'en' => 35, // English usage percentage
        ];
    }

    /**
     * Clear language cache
     */
    public function clearCache()
    {
        Cache::forget('languages.active');
        Cache::forget('languages.default');
        Cache::forget('languages.code.id');
        Cache::forget('languages.code.en');

        return response()->json([
            'success' => true,
            'message' => __('admin.language_cache_cleared')
        ]);
    }
}
