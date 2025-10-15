<?php

namespace App\Http\Controllers;

use App\Services\CacheService;
use App\Services\DatabaseOptimizationService;
use App\Services\ImageOptimizationService;
use App\Services\PerformanceMonitoringService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PerformanceController extends Controller
{
    protected $cacheService;
    protected $dbService;
    protected $imageService;
    protected $monitoringService;
    
    public function __construct(
        CacheService $cacheService,
        DatabaseOptimizationService $dbService,
        ImageOptimizationService $imageService,
        PerformanceMonitoringService $monitoringService
    ) {
        $this->cacheService = $cacheService;
        $this->dbService = $dbService;
        $this->imageService = $imageService;
        $this->monitoringService = $monitoringService;
    }
    
    /**
     * Show unified performance dashboard
     */
    public function dashboard()
    {
        return view('admin.performance.index');
    }
    
    /**
     * Show old performance dashboard (kept for backward compatibility)
     */
    public function oldDashboard()
    {
        $cacheStats = $this->cacheService->getStats();
        $dbStats = $this->dbService->getDatabaseStats();
        $systemStats = $this->monitoringService->getSystemStats();
        $recommendations = $this->monitoringService->getPerformanceRecommendations();
        
        return view('admin.performance.dashboard-old', compact(
            'cacheStats', 'dbStats', 'systemStats', 'recommendations'
        ));
    }
    
    /**
     * Get performance metrics
     */
    public function metrics(): JsonResponse
    {
        $metrics = $this->monitoringService->getPerformanceMetrics();
        
        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }
    
    /**
     * Get cache statistics
     */
    public function cacheStats(): JsonResponse
    {
        $stats = $this->cacheService->getStats();
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    /**
     * Clear cache
     */
    public function clearCache(): JsonResponse
    {
        $this->cacheService->flush();
        
        return response()->json([
            'success' => true,
            'message' => 'Cache cleared successfully'
        ]);
    }
    
    /**
     * Warm up cache
     */
    public function warmUpCache(): JsonResponse
    {
        $result = $this->cacheService->warmUp();
        
        return response()->json([
            'success' => true,
            'message' => 'Cache warmed up successfully',
            'data' => $result
        ]);
    }
    
    /**
     * Add database indexes
     */
    public function addIndexes(): JsonResponse
    {
        $results = $this->dbService->addPerformanceIndexes();
        
        return response()->json([
            'success' => true,
            'message' => 'Database indexes added successfully',
            'data' => $results
        ]);
    }
    
    /**
     * Optimize database
     */
    public function optimizeDatabase(): JsonResponse
    {
        $results = $this->dbService->optimizeDatabase();
        
        return response()->json([
            'success' => true,
            'message' => 'Database optimized successfully',
            'data' => $results
        ]);
    }
    
    /**
     * Get database statistics
     */
    public function databaseStats(): JsonResponse
    {
        $stats = $this->dbService->getDatabaseStats();
        
        return response()->json([
            'success' => true,
            'data' => $stats
        ]);
    }
    
    /**
     * Optimize image
     */
    public function optimizeImage(Request $request): JsonResponse
    {
        $request->validate([
            'path' => 'required|string',
            'options' => 'array'
        ]);
        
        try {
            $result = $this->imageService->optimizeImage(
                $request->path,
                $request->get('options', [])
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Image optimized successfully',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    
    /**
     * Batch optimize images
     */
    public function batchOptimizeImages(Request $request): JsonResponse
    {
        $request->validate([
            'paths' => 'required|array',
            'options' => 'array'
        ]);
        
        $results = $this->imageService->batchOptimize(
            $request->paths,
            $request->get('options', [])
        );
        
        return response()->json([
            'success' => true,
            'message' => 'Images optimized successfully',
            'data' => $results
        ]);
    }
    
    /**
     * Generate performance report
     */
    public function generateReport(): JsonResponse
    {
        $report = $this->monitoringService->generatePerformanceReport();
        
        return response()->json([
            'success' => true,
            'data' => $report
        ]);
    }
    
    /**
     * Get performance recommendations
     */
    public function recommendations(): JsonResponse
    {
        $recommendations = $this->monitoringService->getPerformanceRecommendations();
        
        return response()->json([
            'success' => true,
            'data' => $recommendations
        ]);
    }
    
    /**
     * Monitor page performance
     */
    public function monitorPage(Request $request): JsonResponse
    {
        $metrics = $this->monitoringService->monitorPageLoad($request);
        
        return response()->json([
            'success' => true,
            'data' => $metrics
        ]);
    }
}
