<?php

namespace App\Modules\Theme\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Theme\Models\ThemeCustomization;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CustomizationController extends Controller
{
    /**
     * Show theme customization page
     */
    public function index()
    {
        $adminCustomization = ThemeCustomization::active('admin');
        $publicCustomization = ThemeCustomization::active('public');
        
        return view('admin.themes.customize', compact('adminCustomization', 'publicCustomization'));
    }

    /**
     * Update theme customization
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'theme_type' => 'required|in:admin,public',
            'primary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'secondary_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'accent_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'sidebar_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'background_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'text_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
            'border_color' => 'required|regex:/^#[0-9A-F]{6}$/i',
        ]);

        $customization = ThemeCustomization::updateOrCreate(
            [
                'theme_type' => $validated['theme_type'],
                'is_active' => true,
            ],
            $validated
        );

        // Clear cache
        Cache::flush();

        return redirect()->back()->with('success', __('admin.messages.success.updated', ['resource' => 'Theme Customization']));
    }

    /**
     * Reset to default colors
     */
    public function reset(Request $request)
    {
        $type = $request->query('type', 'admin');
        
        $defaults = ThemeCustomization::getDefaults($type);
        
        ThemeCustomization::updateOrCreate(
            [
                'theme_type' => $type,
                'is_active' => true,
            ],
            $defaults->toArray()
        );

        Cache::flush();

        return redirect()->back()->with('success', 'Theme colors reset to defaults');
    }

    /**
     * Get CSS for theme customization
     */
    public function css($type = 'admin')
    {
        $customization = ThemeCustomization::active($type);
        
        return response($customization->toCss(), 200, [
            'Content-Type' => 'text/css; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
