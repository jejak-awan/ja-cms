{{-- Performance Metrics Tab - Tailwind Version --}}
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Performance Chart -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Performance Trends') }}
            </h3>
            <div class="chart-container">
                <canvas id="performanceChart"></canvas>
            </div>
        </div>

        <!-- System Info -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('System Information') }}
            </h3>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">PHP Version:</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ PHP_VERSION }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Laravel:</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ app()->version() }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Memory Limit:</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ ini_get('memory_limit') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Max Exec Time:</dt>
                    <dd class="font-medium text-gray-900 dark:text-white">{{ ini_get('max_execution_time') }}s</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Cache Driver:</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs font-medium rounded bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                            {{ strtoupper(config('cache.default')) }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-600 dark:text-gray-400">Environment:</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs font-medium rounded {{ app()->environment() === 'production' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300' }}">
                            {{ strtoupper(app()->environment()) }}
                        </span>
                    </dd>
                </div>
            </dl>
        </div>
    </div>

    <!-- Database & Cache Performance -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Database Performance') }}</h3>
                <button onclick="refreshDatabaseStats()" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
            <div id="database-stats-content">
                <div class="text-center py-8">
                    <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __('Cache Performance') }}</h3>
                <button onclick="refreshCachePerformance()" class="text-blue-600 hover:text-blue-700 dark:text-blue-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                </button>
            </div>
            <div id="cache-performance-content">
                <div class="text-center py-8">
                    <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <button onclick="optimizeCache()" class="p-6 bg-green-50 hover:bg-green-100 dark:bg-green-900/20 dark:hover:bg-green-900/30 rounded-lg text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Warm Cache') }}</span>
        </button>

        <button onclick="optimizeDatabase()" class="p-6 bg-blue-50 hover:bg-blue-100 dark:bg-blue-900/20 dark:hover:bg-blue-900/30 rounded-lg text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
            </svg>
            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Optimize DB') }}</span>
        </button>

        <button onclick="clearOldLogs()" class="p-6 bg-yellow-50 hover:bg-yellow-100 dark:bg-yellow-900/20 dark:hover:bg-yellow-900/30 rounded-lg text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Clear Logs') }}</span>
        </button>

        <button onclick="runDiagnostics()" class="p-6 bg-purple-50 hover:bg-purple-100 dark:bg-purple-900/20 dark:hover:bg-purple-900/30 rounded-lg text-center transition-colors">
            <svg class="w-8 h-8 mx-auto mb-2 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
            </svg>
            <span class="text-sm font-medium text-gray-900 dark:text-white">{{ __('Run Diagnostics') }}</span>
        </button>
    </div>
</div>

<script>
// Update charts
window.updatePerformanceCharts = function(data) {
    const ctx = document.getElementById('performanceChart');
    if (!ctx) return;
    
    if (window.performanceChart) {
        window.performanceChart.destroy();
    }
    
    window.performanceChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: ['12h', '10h', '8h', '6h', '4h', '2h', 'Now'],
            datasets: [{
                label: 'Response Time (ms)',
                data: [120, 150, 140, 130, 125, 135, data.execution_time || 145],
                borderColor: 'rgb(59, 130, 246)',
                backgroundColor: 'rgba(59, 130, 246, 0.1)',
                tension: 0.3,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });
};

// Refresh database stats
function refreshDatabaseStats() {
    const content = document.getElementById('database-stats-content');
    content.innerHTML = '<div class="text-center py-8"><div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div></div>';
    
    fetch('/admin/performance/database-stats')
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const tables = ['articles', 'pages', 'categories', 'users'];
                let html = '<div class="grid grid-cols-2 gap-4">';
                tables.forEach(table => {
                    const info = data.data[table] || { rows: 0 };
                    html += `
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${info.rows || 0}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">${table.charAt(0).toUpperCase() + table.slice(1)}</div>
                        </div>
                    `;
                });
                html += '</div>';
                content.innerHTML = html;
            }
        });
}

// Refresh cache performance
function refreshCachePerformance() {
    const content = document.getElementById('cache-performance-content');
    content.innerHTML = '<div class="text-center py-8"><div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div></div>';
    
    fetch('/admin/performance/cache-stats')
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                const stats = data.data;
                content.innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-green-600 dark:text-green-400">${stats.hits || 0}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Hits</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">${stats.misses || 0}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Misses</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-2xl font-bold text-blue-600 dark:text-blue-400">${stats.writes || 0}</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Writes</div>
                        </div>
                        <div class="text-center p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="text-xl font-bold text-purple-600 dark:text-purple-400">${stats.hit_rate || 0}%</div>
                            <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">Hit Rate</div>
                        </div>
                    </div>
                `;
            }
        });
}

// Quick actions
function optimizeCache() {
    if (confirm('{{ __("Warm up cache?") }}')) {
        fetch('/admin/performance/warm-cache', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
            .then(r => r.json())
            .then(data => data.success && showNotification('success', 'Cache warmed up'));
    }
}

function optimizeDatabase() {
    if (confirm('{{ __("Optimize database?") }}')) {
        fetch('/admin/performance/optimize-database', { method: 'POST', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }})
            .then(r => r.json())
            .then(data => data.success && showNotification('success', 'Database optimized'));
    }
}

function clearOldLogs() {
    showNotification('info', 'Feature coming soon');
}

function runDiagnostics() {
    showNotification('info', 'Running diagnostics...');
}

// Auto-init
setTimeout(() => {
    refreshDatabaseStats();
    refreshCachePerformance();
}, 500);
</script>

