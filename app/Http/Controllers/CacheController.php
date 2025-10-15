<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CacheService;
use App\Services\CacheManager;
use App\Modules\User\Models\UserActivityLog;

/**
 * Cache Controller
 * 
 * Handles cache management in admin panel
 */
class CacheController extends Controller
{
    protected $cacheService;
    protected $cacheManager;
    
    public function __construct(CacheService $cacheService, CacheManager $cacheManager)
    {
        $this->cacheService = $cacheService;
        $this->cacheManager = $cacheManager;
    }
    
    /**
     * Show cache dashboard (redirect to unified performance dashboard)
     */
    public function dashboard()
    {
        // Redirect to unified performance & cache dashboard
        return redirect()->route('admin.performance.dashboard');
    }
    
    /**
     * Get cache status
     */
    public function status()
    {
        return response()->json([
            'success' => true,
            'data' => $this->cacheManager->getStatus()
        ]);
    }
    
    /**
     * Get cache statistics
     */
    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => $this->cacheService->getStats()
        ]);
    }
    
    /**
     * Get cache metrics
     */
    public function metrics()
    {
        return response()->json([
            'success' => true,
            'data' => $this->cacheManager->getMetrics()
        ]);
    }
    
    /**
     * Get cache configuration
     */
    public function config()
    {
        return response()->json([
            'success' => true,
            'data' => $this->cacheManager->getConfig()
        ]);
    }
    
    /**
     * Update cache configuration
     */
    public function updateConfig(Request $request)
    {
        $validated = $request->validate([
            'enabled' => 'required|boolean',
            'driver' => 'required|string|in:file,database,redis,memcached,array',
            'ttl' => 'required|integer|min:60|max:604800', // 1 minute to 1 week
            'debug' => 'required|boolean',
        ]);
        
        $result = $this->cacheManager->updateConfig($validated);
        
        if ($result) {
            // Log activity
            UserActivityLog::logActivity(
                auth()->id(),
                'cache_config_update',
                'Updated cache configuration',
                null,
                null,
                $validated
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Cache configuration updated successfully',
                'data' => $this->cacheManager->getConfig()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to update cache configuration'
        ], 500);
    }
    
    /**
     * Clear all cache
     */
    public function clearAll(Request $request)
    {
        $result = $this->cacheManager->clearAll();
        
        if ($result) {
            // Log activity
            UserActivityLog::logActivity(
                auth()->id(),
                'cache_clear_all',
                'Cleared all cache'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'All cache cleared successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to clear cache'
        ], 500);
    }
    
    /**
     * Clear cache by pattern
     */
    public function clearByPattern(Request $request)
    {
        $validated = $request->validate([
            'pattern' => 'required|string|max:255'
        ]);
        
        $result = $this->cacheManager->clearByPattern($validated['pattern']);
        
        if ($result) {
            // Log activity
            UserActivityLog::logActivity(
                auth()->id(),
                'cache_clear_pattern',
                'Cleared cache by pattern',
                null,
                null,
                ['pattern' => $validated['pattern']]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Cache cleared by pattern successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to clear cache by pattern'
        ], 500);
    }
    
    /**
     * Warm up cache
     */
    public function warmUp(Request $request)
    {
        $result = $this->cacheManager->warmUp();
        
        // Log activity
        UserActivityLog::logActivity(
            auth()->id(),
            'cache_warm_up',
            'Warmed up cache'
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Cache warmed up successfully',
            'data' => $result
        ]);
    }
    
    /**
     * Enable cache
     */
    public function enable()
    {
        $result = $this->cacheManager->enable();
        
        if ($result) {
            // Log activity
            UserActivityLog::logActivity(
                auth()->id(),
                'cache_enable',
                'Enabled cache'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Cache enabled successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to enable cache'
        ], 500);
    }
    
    /**
     * Disable cache
     */
    public function disable()
    {
        $result = $this->cacheManager->disable();
        
        if ($result) {
            // Log activity
            UserActivityLog::logActivity(
                auth()->id(),
                'cache_disable',
                'Disabled cache'
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Cache disabled successfully'
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Failed to disable cache'
        ], 500);
    }
}
