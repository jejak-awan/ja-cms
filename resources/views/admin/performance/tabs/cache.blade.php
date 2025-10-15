{{-- Cache Management Tab - Tailwind Version --}}
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Cache Controls -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Cache Controls') }}
            </h3>
            
            <div class="space-y-3 mb-6">
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Status') }}:</span>
                    <span id="cache-enabled-badge" class="px-2 py-1 text-xs font-medium rounded bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                        {{ __('Loading...') }}
                    </span>
                    <button id="cache-toggle-btn" onclick="toggleCacheUi()" class="ml-3 px-3 py-1 rounded text-xs font-medium border border-gray-300 bg-gray-50 dark:bg-gray-800 dark:border-gray-600 transition" disabled>...</button>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Driver') }}:</span>
                    <select id="cache-driver-select" class="form-select text-xs px-2 py-1 rounded bg-blue-50 border border-blue-300 text-blue-700 dark:bg-blue-950 dark:text-blue-200 dark:border-blue-700 outline-none focus:ring-2 focus:ring-blue-200 transition" disabled  onchange="onChangeCacheConfig()">
                        <option>{{ __('Loading...') }}</option>
                    </select>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('TTL (seconds)') }}:</span>
                    <input id="cache-ttl-input" type="number" min="60" max="604800" step="60" class="text-xs px-2 py-1 rounded border border-gray-300 w-24 bg-gray-50 dark:bg-gray-800 dark:border-gray-600 text-right focus:ring-2 focus:ring-blue-200" value="3600" onchange="onChangeCacheConfig()" disabled />
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Debug') }}:</span>
                    <label class="inline-flex items-center cursor-pointer ml-2">
                        <input id="cache-debug-toggle" type="checkbox" class="form-checkbox h-4 w-4 text-blue-600" onchange="onChangeCacheConfig()" disabled />
                        <span class="ml-2 text-xs">{{ __('Enable') }}</span>
                    </label>
                </div>
            </div>

            <div class="space-y-2">
                <button onclick="warmUpCache()" class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 18.657A8 8 0 016.343 7.343S7 9 9 10c0-2 .5-5 2.986-7C14 5 16.09 5.777 17.656 7.343A7.975 7.975 0 0120 13a7.975 7.975 0 01-2.343 5.657z"></path>
                    </svg>
                    {{ __('Warm Up Cache') }}
                </button>
                <button onclick="clearAllCache()" class="w-full px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white rounded-lg transition-colors text-sm font-medium">
                    <svg class="w-4 h-4 inline-block mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    {{ __('Clear All Cache') }}
                </button>
                <div class="grid grid-cols-2 gap-2">
                    <button onclick="toggleCache('enable')" class="px-3 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors text-xs font-medium">
                        {{ __('Enable') }}
                    </button>
                    <button onclick="toggleCache('disable')" class="px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors text-xs font-medium">
                        {{ __('Disable') }}
                    </button>
                </div>
            </div>
        </div>

        <!-- Cache Statistics -->
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Cache Statistics') }}
            </h3>
            
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
                <div class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg">
                    <div id="cache-hits" class="text-3xl font-bold text-green-600 dark:text-green-400">0</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('Hits') }}</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div id="cache-misses" class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">0</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('Misses') }}</div>
                </div>
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div id="cache-writes" class="text-3xl font-bold text-blue-600 dark:text-blue-400">0</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('Writes') }}</div>
                </div>
                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div id="cache-deletes" class="text-3xl font-bold text-red-600 dark:text-red-400">0</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('Deletes') }}</div>
                </div>
            </div>

            <div>
                <div class="flex justify-between mb-2">
                    <span class="text-sm text-gray-600 dark:text-gray-400">{{ __('Cache Hit Rate') }}</span>
                    <span id="cache-hit-rate-text" class="text-sm font-medium text-gray-900 dark:text-white">0%</span>
                </div>
                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden">
                    <div id="cache-hit-rate-bar" class="h-full bg-green-500 transition-all duration-500" style="width: 0%"></div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                    <svg class="w-3 h-3 inline-block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    {{ __('Higher is better (Target: >70%)') }}
                </p>
            </div>
        </div>
    </div>

    <!-- Cache Chart -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
            {{ __('Cache Hit/Miss Ratio') }}
        </h3>
        <div class="chart-container" style="height: 250px;">
            <canvas id="cacheChart"></canvas>
        </div>
    </div>
</div>

<script>
// Update cache status
window.updateCacheStatus = function(data) {
    const badge = document.getElementById('cache-enabled-badge');
    const btn = document.getElementById('cache-toggle-btn');
    badge.className = data.enabled ?
        'px-2 py-1 text-xs font-medium rounded bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' :
        'px-2 py-1 text-xs font-medium rounded bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300';
    badge.textContent = data.enabled ? '{{ __("Enabled") }}' : '{{ __("Disabled") }}';
    btn.textContent = data.enabled ? 'Disable' : 'Enable';
    btn.className = 'ml-3 px-3 py-1 rounded text-xs font-medium border ' + (data.enabled ? 'bg-red-50 border-red-400 text-red-700 dark:bg-red-900/20 dark:text-red-200 dark:border-red-500' : 'bg-green-50 border-green-400 text-green-700 dark:bg-green-900/20 dark:text-green-200 dark:border-green-500');
    btn.disabled = false;
    // Dropdown
    const sel = document.getElementById('cache-driver-select');
    sel.value = data.driver;
    sel.disabled = !data.enabled;
    // TTL/Debug
    document.getElementById('cache-ttl-input').disabled = !data.enabled;
    document.getElementById('cache-debug-toggle').disabled = !data.enabled;
};

// Update cache stats
window.updateCacheStats = function(data) {
    document.getElementById('cache-hits').textContent = data.hits || 0;
    document.getElementById('cache-misses').textContent = data.misses || 0;
    document.getElementById('cache-writes').textContent = data.writes || 0;
    document.getElementById('cache-deletes').textContent = data.deletes || 0;
    
    const hitRate = data.hit_rate || 0;
    document.getElementById('cache-hit-rate-text').textContent = hitRate + '%';
    document.getElementById('cache-hit-rate-bar').style.width = hitRate + '%';
    
    // Color based on performance
    const bar = document.getElementById('cache-hit-rate-bar');
    if (hitRate >= 70) {
        bar.className = 'h-full bg-green-500 transition-all duration-500';
    } else if (hitRate >= 50) {
        bar.className = 'h-full bg-yellow-500 transition-all duration-500';
    } else {
        bar.className = 'h-full bg-red-500 transition-all duration-500';
    }
    
    // Update chart
    updateCacheChart(data);
};

// Update cache metrics
window.updateCacheMetrics = function(data) {
    if (data) {
        // Available drivers (populate dropdown if not yet)
        const sel = document.getElementById('cache-driver-select');
        if (data.available_drivers) {
            sel.innerHTML = '';
            data.available_drivers.forEach(drv => {
                const opt = document.createElement('option');
                opt.value = drv;
                opt.textContent = drv.charAt(0).toUpperCase() + drv.slice(1);
                sel.appendChild(opt);
            });
        }
        if (data.driver) sel.value = data.driver;
        if (typeof data.ttl !== 'undefined') document.getElementById('cache-ttl-input').value = data.ttl;
        if (typeof data.debug !== 'undefined') document.getElementById('cache-debug-toggle').checked = !!data.debug;
    }
};

// Update cache chart
function updateCacheChart(data) {
    const ctx = document.getElementById('cacheChart');
    if (!ctx) return;
    
    if (window.cacheChart) {
        window.cacheChart.destroy();
    }
    
    window.cacheChart = new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['{{ __("Hits") }}', '{{ __("Misses") }}'],
            datasets: [{
                data: [data.hits || 0, data.misses || 0],
                backgroundColor: ['rgb(34, 197, 94)', 'rgb(234, 179, 8)'],
                borderWidth: 0
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });
}

// Toggle cache
function toggleCache(action) {
    const endpoint = action === 'enable' ? '/admin/cache/enable' : '/admin/cache/disable';
    
    fetch(endpoint, { 
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('success', data.message);
            loadCacheData();
        }
    });
}

// Warm up cache
function warmUpCache() {
    fetch('/admin/cache/warm-up', { 
        method: 'POST',
        headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('success', '{{ __("Cache warmed up successfully") }}');
            loadCacheData();
        }
    });
}

// Clear all cache
function clearAllCache() {
    if (confirm('{{ __("Are you sure you want to clear all cache?") }}')) {
        fetch('/admin/cache/clear-all', { 
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                showNotification('success', '{{ __("Cache cleared successfully") }}');
                loadCacheData();
            }
        });
    }
}

// Toggle enable/disable from UI
function toggleCacheUi() {
    const status = document.getElementById('cache-enabled-badge').textContent;
    if (status.includes('Enabled')) {
        fetchUpdateConfig(false);
    } else {
        fetchUpdateConfig(true);
    }
}
// On change dropdown/input -> AJAX update
function onChangeCacheConfig() {
    if (document.getElementById('cache-enabled-badge').textContent !== 'Enabled') return; // Ignore if disabled
    fetchUpdateConfig(true);
}
function fetchUpdateConfig(forceEnable) {
    const driver = document.getElementById('cache-driver-select').value;
    const ttl = parseInt(document.getElementById('cache-ttl-input').value) || 3600;
    const debug = document.getElementById('cache-debug-toggle').checked;
    const enabled = !!forceEnable;
    fetch('/admin/cache/config', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({driver, ttl, debug, enabled})
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            showNotification('success', 'Updated cache config!');
            loadCacheData();
        } else {
            showNotification('error', data.message || 'Failed update cache config');
        }
    });
}

function loadConfig() {
    fetch('/admin/cache/config', { method: 'GET', headers: { 'Accept': 'application/json' } })
        .then(r => r.json())
        .then(data => {
            if(data.success && data.data) {
                window.updateCacheMetrics(data.data);
                window.updateCacheStatus(data.data);
            } else {
                showNotification('error', data.message || 'Failed to load cache config');
                setDriverDropdownError();
            }
        })
        .catch(err => {
            showNotification('error', 'Failed load cache config: ' + err);
            setDriverDropdownError();
        });
}
function setDriverDropdownError() {
    const sel = document.getElementById('cache-driver-select');
    sel.innerHTML = '';
    sel.appendChild(Object.assign(document.createElement('option'), { value: '', textContent: 'Error loading drivers' }));
    sel.disabled = true;
}
document.addEventListener('DOMContentLoaded', function() {
    loadConfig();
});
</script>

