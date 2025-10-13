<?php

namespace App\Modules\Setting\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Setting\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key')->toArray();
        
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($request->settings as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'group' => $this->getGroupFromKey($key),
                ]
            );
        }

        // Clear settings cache
        Cache::forget('settings');

        return redirect()->back()->with('success', 'Settings updated successfully');
    }

    private function getGroupFromKey($key)
    {
        if (str_starts_with($key, 'site_')) return 'general';
        if (str_starts_with($key, 'seo_')) return 'seo';
        if (str_starts_with($key, 'social_')) return 'social';
        return 'general';
    }
}
