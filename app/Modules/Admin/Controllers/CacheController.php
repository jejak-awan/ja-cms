<?php

namespace App\Modules\Admin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use App\Providers\CacheCustomServiceProvider;
use App\Modules\Setting\Models\Setting;
use App\Modules\User\Models\UserActivityLog;

class CacheController
{
    // Tampilkan status cache
    public function status()
    {
        return response()->json(CacheCustomServiceProvider::getStatus());
    }

    // Flush semua cache
    public function flush(Request $request)
    {
        if (Config::get('cache_custom.allow_flush')) {
            CacheCustomServiceProvider::flushAll();
            // Log activity
            $userId = auth()->id();
            UserActivityLog::logActivity($userId, 'cache_flush', 'Admin flushed all caches');
            return response()->json(['success' => true, 'message' => 'Cache flushed']);
        }
        return response()->json(['success' => false, 'message' => 'Flush not allowed'], 403);
    }

    // Update setting cache (enable/disable, TTL, debug)
    public function update(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
            'ttl' => 'required|integer|min:0|max:604800',
            'debug' => 'required|boolean',
        ]);

        // Persist ke tabel settings
        Setting::set('cache.enabled', (bool) $validated['enabled'], 'cache', 'boolean');
        Setting::set('cache.ttl', (int) $validated['ttl'], 'cache', 'integer');
        Setting::set('cache.debug', (bool) $validated['debug'], 'cache', 'boolean');

        // Update runtime Config
        Config::set('cache_custom.enabled', (bool) $validated['enabled']);
        Config::set('cache_custom.ttl', (int) $validated['ttl']);
        Config::set('cache_custom.debug', (bool) $validated['debug']);

        // Respect enabled toggle by switching to null driver when disabled
        if (!(bool) $validated['enabled']) {
            Config::set('cache.default', 'null');
        }

        // Log activity
        $userId = auth()->id();
        UserActivityLog::logActivity($userId, 'cache_update', 'Admin updated cache settings', null, null, [
            'enabled' => (bool) $validated['enabled'],
            'ttl' => (int) $validated['ttl'],
            'debug' => (bool) $validated['debug'],
        ]);

        return response()->json(['success' => true, 'config' => CacheCustomServiceProvider::getStatus()]);
    }

    // Tampilkan halaman pengaturan cache
    public function show()
    {
        return view('admin.settings.cache');
    }
}
