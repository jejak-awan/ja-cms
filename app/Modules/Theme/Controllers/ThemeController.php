<?php

namespace App\Modules\Theme\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Theme\Models\Theme;
use App\Modules\Theme\Services\ThemeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    protected $themeService;

    public function __construct(ThemeService $themeService)
    {
        $this->themeService = $themeService;
    }

    /**
     * Display themes list
     */
    public function index(Request $request)
    {
        // Sync themes from filesystem
        $this->themeService->syncThemes();

        $themes = Theme::orderBy('is_active', 'desc')
            ->orderBy('name')
            ->get()
            ->groupBy('type');

        $publicThemes = $themes->get('public', collect());
        $adminThemes = $themes->get('admin', collect());

        $activePublic = $publicThemes->where('is_active', true)->first();
        $activeAdmin = $adminThemes->where('is_active', true)->first();

        return view('admin.themes.index', compact('publicThemes', 'adminThemes', 'activePublic', 'activeAdmin'));
    }

    /**
     * Activate a theme
     */
    public function activate(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        
        // Deactivate other themes of same type
        Theme::where('type', $theme->type)
            ->where('id', '!=', $theme->id)
            ->update(['is_active' => false]);
        
        // Activate this theme
        $theme->update(['is_active' => true]);

        // Update config
        $this->themeService->setActiveTheme($theme->slug, $theme->type);

        // Clear cache
        Cache::flush();

        return redirect()->back()->with('success', "Theme '{$theme->name}' activated successfully!");
    }

    /**
     * Show theme settings
     */
    public function settings($id)
    {
        $theme = Theme::findOrFail($id);
        return view('admin.themes.settings', compact('theme'));
    }

    /**
     * Update theme settings
     */
    public function updateSettings(Request $request, $id)
    {
        $theme = Theme::findOrFail($id);
        
        $settings = $request->except(['_token', '_method']);
        $theme->update(['settings' => $settings]);

        return redirect()->back()->with('success', 'Theme settings updated successfully!');
    }
}

