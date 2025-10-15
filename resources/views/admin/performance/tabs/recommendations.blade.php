{{-- Recommendations Tab - Tailwind Version --}}
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-6">
        <!-- Performance Score -->
        <div class="bg-gradient-to-br from-blue-600 to-purple-600 rounded-lg shadow-lg p-6 text-white text-center">
            <p class="text-sm opacity-75 mb-2">{{ __('Performance Score') }}</p>
            <div id="performance-score" class="text-6xl font-bold mb-2">--</div>
            <div class="h-2 bg-white/20 rounded-full overflow-hidden">
                <div id="score-progress" class="h-full bg-white transition-all duration-500" style="width: 0%"></div>
            </div>
            <p id="score-description" class="text-sm mt-3 opacity-75">{{ __('Calculating...') }}</p>
        </div>

        <!-- Quick Stats -->
        <div class="lg:col-span-3 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                {{ __('Issue Summary') }}
            </h3>
            <div class="grid grid-cols-3 gap-4">
                <div class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg">
                    <div id="critical-count" class="text-3xl font-bold text-red-600 dark:text-red-400">0</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('Critical Issues') }}</div>
                </div>
                <div class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg">
                    <div id="warning-count" class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">0</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('Warnings') }}</div>
                </div>
                <div class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                    <div id="suggestion-count" class="text-3xl font-bold text-blue-600 dark:text-blue-400">0</div>
                    <div class="text-xs text-gray-600 dark:text-gray-400 mt-1">{{ __('Suggestions') }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommendations List -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                {{ __('Actionable Recommendations') }}
            </h3>
            <div class="flex space-x-2">
                <button onclick="filterRecommendations('all')" class="filter-btn active px-3 py-1 text-xs font-medium rounded-lg bg-blue-600 text-white">
                    {{ __('All') }}
                </button>
                <button onclick="filterRecommendations('high')" class="filter-btn px-3 py-1 text-xs font-medium rounded-lg bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    {{ __('Critical') }}
                </button>
                <button onclick="filterRecommendations('medium')" class="filter-btn px-3 py-1 text-xs font-medium rounded-lg bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    {{ __('Warning') }}
                </button>
                <button onclick="filterRecommendations('low')" class="filter-btn px-3 py-1 text-xs font-medium rounded-lg bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300">
                    {{ __('Info') }}
                </button>
            </div>
        </div>
        <div id="recommendations-list">
            <div class="text-center py-8">
                <div class="inline-block w-8 h-8 border-4 border-blue-600 border-t-transparent rounded-full animate-spin"></div>
                <p class="text-gray-600 dark:text-gray-400 mt-2">{{ __('Loading recommendations...') }}</p>
            </div>
        </div>
    </div>

    <!-- Best Practices -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-blue-100 dark:bg-blue-900 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">{{ __('Caching') }}</h4>
            </div>
            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Use Redis for production') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Set appropriate TTL values') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Monitor cache hit rates') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Use cache tags') }}
                </li>
            </ul>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-green-100 dark:bg-green-900 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">{{ __('Database') }}</h4>
            </div>
            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Add indexes to foreign keys') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Use eager loading') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Paginate large results') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Optimize slow queries') }}
                </li>
            </ul>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
            <div class="flex items-center mb-4">
                <div class="p-2 bg-purple-100 dark:bg-purple-900 rounded-lg mr-3">
                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"></path>
                    </svg>
                </div>
                <h4 class="font-semibold text-gray-900 dark:text-white">{{ __('Code') }}</h4>
            </div>
            <ul class="space-y-2 text-sm text-gray-600 dark:text-gray-400">
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Minimize middleware') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Use queues for heavy tasks') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Optimize asset loading') }}
                </li>
                <li class="flex items-start">
                    <svg class="w-4 h-4 text-green-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    {{ __('Enable compression') }}
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
let currentFilter = 'all';
let allRecommendations = [];

// Display recommendations
window.displayRecommendations = function(data) {
    allRecommendations = data || [];
    
    let score = 100;
    let critical = 0, warning = 0, suggestion = 0;
    
    allRecommendations.forEach(rec => {
        if (rec.priority === 'high') {
            critical++;
            score -= 20;
        } else if (rec.priority === 'medium') {
            warning++;
            score -= 10;
        } else {
            suggestion++;
            score -= 5;
        }
    });
    
    score = Math.max(0, Math.min(100, score));
    
    document.getElementById('performance-score').textContent = score;
    document.getElementById('score-progress').style.width = score + '%';
    document.getElementById('critical-count').textContent = critical;
    document.getElementById('warning-count').textContent = warning;
    document.getElementById('suggestion-count').textContent = suggestion;
    
    const desc = score >= 90 ? '{{ __("Excellent!") }}' : 
                 score >= 70 ? '{{ __("Good") }}' : 
                 score >= 50 ? '{{ __("Needs Work") }}' : 
                 '{{ __("Critical") }}';
    document.getElementById('score-description').textContent = desc;
    
    renderRecommendations();
};

// Render recommendations
function renderRecommendations() {
    const container = document.getElementById('recommendations-list');
    const filtered = currentFilter === 'all' ? allRecommendations : 
                     allRecommendations.filter(r => r.priority === currentFilter);
    
    if (filtered.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-16 h-16 mx-auto text-green-500 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h5 class="text-lg font-semibold text-gray-900 dark:text-white">{{ __("All Clear!") }}</h5>
                <p class="text-gray-600 dark:text-gray-400">{{ __("No recommendations found") }}</p>
            </div>
        `;
        return;
    }
    
    let html = '<div class="space-y-4">';
    filtered.forEach(rec => {
        const colors = {
            high: { bg: 'bg-red-50 dark:bg-red-900/20', border: 'border-red-500', icon: 'text-red-600 dark:text-red-400' },
            medium: { bg: 'bg-yellow-50 dark:bg-yellow-900/20', border: 'border-yellow-500', icon: 'text-yellow-600 dark:text-yellow-400' },
            low: { bg: 'bg-blue-50 dark:bg-blue-900/20', border: 'border-blue-500', icon: 'text-blue-600 dark:text-blue-400' }
        };
        const c = colors[rec.priority] || colors.low;
        
        html += `
            <div class="${c.bg} border-l-4 ${c.border} p-4 rounded-r-lg">
                <div class="flex items-start">
                    <svg class="w-5 h-5 ${c.icon} mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                    <div class="flex-1">
                        <h5 class="font-semibold text-gray-900 dark:text-white">${rec.message || rec.type}</h5>
                        ${rec.action ? `<p class="text-sm text-gray-600 dark:text-gray-400 mt-1">${rec.action}</p>` : ''}
                    </div>
                </div>
            </div>
        `;
    });
    html += '</div>';
    container.innerHTML = html;
}

// Filter recommendations
function filterRecommendations(filter) {
    currentFilter = filter;
    
    document.querySelectorAll('.filter-btn').forEach(btn => {
        btn.className = 'filter-btn px-3 py-1 text-xs font-medium rounded-lg bg-gray-200 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
    });
    event.target.classList.add('active');
    event.target.className = 'filter-btn active px-3 py-1 text-xs font-medium rounded-lg bg-blue-600 text-white';
    
    renderRecommendations();
}
</script>

