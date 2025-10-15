<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class PerformanceMonitoringService
{
    protected $startTime;
    protected $startMemory;
    
    public function __construct()
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
    }
    
    /**
     * Start performance monitoring
     */
    public function startMonitoring(): void
    {
        $this->startTime = microtime(true);
        $this->startMemory = memory_get_usage();
    }
    
    /**
     * Get performance metrics
     */
    public function getPerformanceMetrics(): array
    {
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        
        return [
            'execution_time' => round(($endTime - $this->startTime) * 1000, 2), // milliseconds
            'memory_usage' => $this->formatBytes($endMemory - $this->startMemory),
            'peak_memory' => $this->formatBytes(memory_get_peak_usage()),
            'query_count' => $this->getQueryCount(),
            'cache_hits' => $this->getCacheHits(),
            'cache_misses' => $this->getCacheMisses(),
        ];
    }
    
    /**
     * Monitor database queries
     */
    public function monitorQueries(): void
    {
        DB::listen(function ($query) {
            if ($query->time > 100) { // Log slow queries (>100ms)
                Log::warning('Slow query detected', [
                    'sql' => $query->sql,
                    'bindings' => $query->bindings,
                    'time' => $query->time . 'ms'
                ]);
            }
        });
    }
    
    /**
     * Get query count
     */
    protected function getQueryCount(): int
    {
        return count(DB::getQueryLog());
    }
    
    /**
     * Get cache statistics
     */
    protected function getCacheHits(): int
    {
        try {
            if (class_exists('Redis') && config('cache.default') === 'redis') {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $info = $redis->info();
                return $info['keyspace_hits'] ?? 0;
            }
            return 0; // File cache doesn't track hits
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Get cache misses
     */
    protected function getCacheMisses(): int
    {
        try {
            if (class_exists('Redis') && config('cache.default') === 'redis') {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $info = $redis->info();
                return $info['keyspace_misses'] ?? 0;
            }
            return 0; // File cache doesn't track misses
        } catch (\Exception $e) {
            return 0;
        }
    }
    
    /**
     * Format bytes to human readable
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        
        $bytes /= pow(1024, $pow);
        
        return round($bytes, 2) . ' ' . $units[$pow];
    }
    
    /**
     * Monitor page load performance
     */
    public function monitorPageLoad(Request $request): array
    {
        $metrics = $this->getPerformanceMetrics();
        
        // Log performance data
        Log::info('Page performance', [
            'url' => $request->url(),
            'method' => $request->method(),
            'execution_time' => $metrics['execution_time'],
            'memory_usage' => $metrics['memory_usage'],
            'query_count' => $metrics['query_count'],
        ]);
        
        return $metrics;
    }
    
    /**
     * Get system performance stats
     */
    public function getSystemStats(): array
    {
        return [
            'php_version' => PHP_VERSION,
            'laravel_version' => app()->version(),
            'memory_limit' => ini_get('memory_limit'),
            'max_execution_time' => ini_get('max_execution_time'),
            'cache_driver' => config('cache.default'),
            'session_driver' => config('session.driver'),
            'database_driver' => config('database.default'),
        ];
    }
    
    /**
     * Generate performance report
     */
    public function generatePerformanceReport(): array
    {
        $report = [
            'timestamp' => now()->toISOString(),
            'system' => $this->getSystemStats(),
            'performance' => $this->getPerformanceMetrics(),
            'database' => $this->getDatabaseStats(),
            'cache' => $this->getCacheStats(),
        ];
        
        // Store report in cache for dashboard
        Cache::put('performance.report', $report, 300); // 5 minutes
        
        return $report;
    }
    
    /**
     * Get database statistics
     */
    protected function getDatabaseStats(): array
    {
        try {
            return [
                'connection_count' => DB::getPdo() ? 1 : 0,
                'query_count' => count(DB::getQueryLog()),
                'slow_queries' => $this->getSlowQueries(),
            ];
        } catch (\Exception $e) {
            return ['error' => $e->getMessage()];
        }
    }
    
    /**
     * Get slow queries
     */
    protected function getSlowQueries(): array
    {
        $queries = DB::getQueryLog();
        $slowQueries = [];
        
        foreach ($queries as $query) {
            if ($query['time'] > 100) { // > 100ms
                $slowQueries[] = [
                    'sql' => $query['sql'],
                    'time' => $query['time'],
                    'bindings' => $query['bindings'],
                ];
            }
        }
        
        return $slowQueries;
    }
    
    /**
     * Get cache statistics
     */
    protected function getCacheStats(): array
    {
        try {
            if (class_exists('Redis') && config('cache.default') === 'redis') {
                $redis = \Illuminate\Support\Facades\Redis::connection();
                $info = $redis->info();
                
                return [
                    'driver' => config('cache.default'),
                    'hits' => $info['keyspace_hits'] ?? 0,
                    'misses' => $info['keyspace_misses'] ?? 0,
                    'hit_rate' => $this->calculateHitRate($info),
                    'memory_used' => $info['used_memory_human'] ?? 'Unknown',
                ];
            } else {
                return [
                    'driver' => config('cache.default'),
                    'hits' => 0,
                    'misses' => 0,
                    'hit_rate' => 0,
                    'memory_used' => 'File cache (no stats available)',
                ];
            }
        } catch (\Exception $e) {
            return [
                'driver' => config('cache.default'),
                'error' => $e->getMessage(),
            ];
        }
    }
    
    /**
     * Calculate cache hit rate
     */
    protected function calculateHitRate(array $info): float
    {
        $hits = $info['keyspace_hits'] ?? 0;
        $misses = $info['keyspace_misses'] ?? 0;
        $total = $hits + $misses;
        
        return $total > 0 ? round(($hits / $total) * 100, 2) : 0;
    }
    
    /**
     * Monitor API performance
     */
    public function monitorApiPerformance(Request $request, $response): array
    {
        $metrics = $this->getPerformanceMetrics();
        
        // Log API performance
        Log::info('API performance', [
            'endpoint' => $request->path(),
            'method' => $request->method(),
            'status_code' => $response->getStatusCode(),
            'execution_time' => $metrics['execution_time'],
            'memory_usage' => $metrics['memory_usage'],
            'query_count' => $metrics['query_count'],
        ]);
        
        return $metrics;
    }
    
    /**
     * Get performance recommendations
     */
    public function getPerformanceRecommendations(): array
    {
        $recommendations = [];
        $metrics = $this->getPerformanceMetrics();
        
        // Execution time recommendations
        if ($metrics['execution_time'] > 1000) {
            $recommendations[] = [
                'type' => 'execution_time',
                'message' => 'Page load time is over 1 second. Consider optimizing queries or adding caching.',
                'priority' => 'high'
            ];
        }
        
        // Memory usage recommendations
        if (memory_get_peak_usage() > 128 * 1024 * 1024) { // 128MB
            $recommendations[] = [
                'type' => 'memory_usage',
                'message' => 'High memory usage detected. Consider optimizing data loading.',
                'priority' => 'medium'
            ];
        }
        
        // Query count recommendations
        if ($metrics['query_count'] > 20) {
            $recommendations[] = [
                'type' => 'query_count',
                'message' => 'High number of database queries. Consider eager loading or caching.',
                'priority' => 'medium'
            ];
        }
        
        return $recommendations;
    }
}
