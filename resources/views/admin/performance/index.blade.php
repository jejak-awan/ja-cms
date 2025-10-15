@extends('admin.layouts.admin')

@section('title', __('Performance & Cache Management'))

@push('styles')
<style>
    .tab-button {
        @apply px-6 py-4 rounded-lg transition-all duration-200 flex flex-col items-center justify-center gap-2 text-gray-600 dark:text-gray-400 border-2 border-transparent;
        min-height: 100px;
    }
    .tab-button.active {
        @apply text-blue-600 dark:text-blue-400 bg-blue-50 dark:bg-blue-900/30 border-blue-500 dark:border-blue-400;
        box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    }
    .tab-button:hover:not(.active) {
        @apply text-gray-800 dark:text-gray-200 bg-gray-100 dark:bg-gray-700/50 border-gray-300 dark:border-gray-600;
    }
    .tab-button svg {
        @apply flex-shrink-0;
    }
    .tab-button span {
        @apply text-center text-sm font-semibold;
    }
    .stat-card {
        @apply bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6 transition-all hover:shadow-md;
    }
    .chart-container {
        position: relative;
        height: 300px;
    }
</style>
@endpush

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">
                {{ __('Performance & Cache Management') }}
            </h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Monitor, optimize, and manage your application performance') }}
            </p>
        </div>
        <div class="flex space-x-3">
            <button onclick="refreshAllData()" class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                {{ __('Refresh All') }}
            </button>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="stat-card border-l-4 border-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Response Time') }}</p>
                    <p id="avg-response-time" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        <span class="inline-block w-16 h-8 bg-gray-200 dark:bg-gray-700 animate-pulse rounded"></span>
                    </p>
                </div>
                <div class="p-3 bg-blue-100 dark:bg-blue-900 rounded-full">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card border-l-4 border-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Cache Hit Rate') }}</p>
                    <p id="cache-hit-rate" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        <span class="inline-block w-16 h-8 bg-gray-200 dark:bg-gray-700 animate-pulse rounded"></span>
                    </p>
                </div>
                <div class="p-3 bg-green-100 dark:bg-green-900 rounded-full">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card border-l-4 border-yellow-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Memory Usage') }}</p>
                    <p id="memory-usage" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        <span class="inline-block w-16 h-8 bg-gray-200 dark:bg-gray-700 animate-pulse rounded"></span>
                    </p>
                </div>
                <div class="p-3 bg-yellow-100 dark:bg-yellow-900 rounded-full">
                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z"></path>
                    </svg>
                </div>
            </div>
        </div>

        <div class="stat-card border-l-4 border-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('DB Queries') }}</p>
                    <p id="db-queries" class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                        <span class="inline-block w-16 h-8 bg-gray-200 dark:bg-gray-700 animate-pulse rounded"></span>
                    </p>
                </div>
                <div class="p-3 bg-purple-100 dark:bg-purple-900 rounded-full">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>

    <!-- Tabs -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm overflow-hidden">
        <div class="border-b-2 border-gray-200 dark:border-gray-700">
            <nav class="flex gap-2 p-2" role="tablist">
                <button onclick="switchTab('performance')" id="tab-performance" class="tab-button active flex-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    <span class="font-medium">{{ __('Performance Metrics') }}</span>
                </button>
                <button onclick="switchTab('cache')" id="tab-cache" class="tab-button flex-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01"></path>
                    </svg>
                    <span class="font-medium">{{ __('Cache Management') }}</span>
                </button>
                <button onclick="switchTab('recommendations')" id="tab-recommendations" class="tab-button flex-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                    </svg>
                    <span class="font-medium">{{ __('Recommendations') }}</span>
                </button>
            </nav>
        </div>

        <!-- Tab Content -->
        <div class="p-6">
            <div id="content-performance" class="tab-content">
                @include('admin.performance.tabs.metrics')
            </div>
            <div id="content-cache" class="tab-content hidden">
                @include('admin.performance.tabs.cache')
            </div>
            <div id="content-recommendations" class="tab-content hidden">
                @include('admin.performance.tabs.recommendations')
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
<script>
// Global variables
window.performanceChart = null;
window.cacheChart = null;
let currentTab = 'performance';

// Tab switching
function switchTab(tab) {
    // Update buttons
    document.querySelectorAll('.tab-button').forEach(btn => {
        btn.classList.remove('active');
    });
    document.getElementById('tab-' + tab).classList.add('active');
    
    // Update content
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    document.getElementById('content-' + tab).classList.remove('hidden');
    
    currentTab = tab;
    
    // Load data for active tab
    if (tab === 'performance') {
        loadPerformanceMetrics();
    } else if (tab === 'cache') {
        loadCacheData();
    } else if (tab === 'recommendations') {
        loadRecommendations();
    }
}

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    loadQuickStats();
    loadPerformanceMetrics();
    
    // Auto-refresh every 30 seconds
    setInterval(() => {
        loadQuickStats();
        if (currentTab === 'performance') {
            loadPerformanceMetrics();
        } else if (currentTab === 'cache') {
            loadCacheData();
        }
    }, 30000);
});

// Load quick stats
function loadQuickStats() {
    fetch('/admin/performance/data')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('avg-response-time').textContent = (data.data.execution_time || '-') + 'ms';
                document.getElementById('cache-hit-rate').textContent = (data.data.cache_hits || 0) + '%';
                document.getElementById('memory-usage').textContent = data.data.memory_usage || '-';
                document.getElementById('db-queries').textContent = data.data.query_count || 0;
            }
        })
        .catch(error => console.error('Error loading quick stats:', error));
}

// Load performance metrics
function loadPerformanceMetrics() {
    fetch('/admin/performance/data')
        .then(response => response.json())
        .then(data => {
            if (data.success && typeof window.updatePerformanceCharts === 'function') {
                window.updatePerformanceCharts(data.data);
            }
        })
        .catch(error => console.error('Error loading performance metrics:', error));
}

// Load cache data
function loadCacheData() {
    Promise.all([
        fetch('/admin/cache/status').then(r => r.json()),
        fetch('/admin/cache/stats').then(r => r.json()),
        fetch('/admin/cache/metrics').then(r => r.json())
    ])
    .then(([status, stats, metrics]) => {
        if (typeof window.updateCacheStatus === 'function') {
            if (status.success) window.updateCacheStatus(status.data);
            if (stats.success) window.updateCacheStats(stats.data);
            if (metrics.success) window.updateCacheMetrics(metrics.data);
        }
    })
    .catch(error => console.error('Error loading cache data:', error));
}

// Load recommendations
function loadRecommendations() {
    fetch('/admin/performance/recommendations')
        .then(response => response.json())
        .then(data => {
            if (data.success && typeof window.displayRecommendations === 'function') {
                window.displayRecommendations(data.data);
            }
        })
        .catch(error => console.error('Error loading recommendations:', error));
}

// Refresh all data
function refreshAllData() {
    const btn = event.target.closest('button');
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<svg class="animate-spin w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg> Refreshing...';
    btn.disabled = true;
    
    Promise.all([
        loadQuickStats(),
        loadPerformanceMetrics(),
        loadCacheData(),
        loadRecommendations()
    ]).then(() => {
        btn.innerHTML = originalHtml;
        btn.disabled = false;
        showNotification('success', 'Data refreshed successfully');
    });
}

// Show notification
function showNotification(type, message) {
    const bgColor = type === 'success' ? 'bg-green-500' : 'bg-red-500';
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 animate-fade-in-down`;
    notification.textContent = message;
    document.body.appendChild(notification);
    setTimeout(() => notification.remove(), 3000);
}

// Make global
window.switchTab = switchTab;
window.loadQuickStats = loadQuickStats;
window.loadPerformanceMetrics = loadPerformanceMetrics;
window.loadCacheData = loadCacheData;
window.loadRecommendations = loadRecommendations;
window.refreshAllData = refreshAllData;
window.showNotification = showNotification;
</script>
@endpush
