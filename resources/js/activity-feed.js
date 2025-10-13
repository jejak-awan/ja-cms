/**
 * Activity Feed Enhancement - Pagination, Filtering, and Real-time Updates
 */

// Activity feed state management
window.ACTIVITY_FEED = {
    currentPage: 1,
    perPage: 10,
    currentType: null,
    isLoading: false,
    pollingInterval: null,
    lastUpdate: null
};

/**
 * Initialize activity feed enhancements
 */
export function initActivityFeed() {
    const container = document.getElementById('activityFeedContainer');
    if (!container) return;

    // Add filter buttons
    addFilterButtons(container);
    
    // Add pagination controls
    addPaginationControls(container);
    
    // Start real-time polling (every 30 seconds)
    startPolling();
    
    // Setup event listeners
    setupEventListeners();
}

/**
 * Add filter buttons to activity feed
 */
function addFilterButtons(container) {
    const header = container.querySelector('.activity-header');
    if (!header) return;

    const filterHtml = `
        <div class="flex items-center space-x-2 mt-2">
            <button data-filter="" class="activity-filter-btn active px-3 py-1 text-xs rounded-full bg-gray-100 hover:bg-gray-200 transition-colors">
                All
            </button>
            <button data-filter="article" class="activity-filter-btn px-3 py-1 text-xs rounded-full bg-blue-50 hover:bg-blue-100 text-blue-700 transition-colors">
                Articles
            </button>
            <button data-filter="user" class="activity-filter-btn px-3 py-1 text-xs rounded-full bg-green-50 hover:bg-green-100 text-green-700 transition-colors">
                Users
            </button>
            <button data-filter="media" class="activity-filter-btn px-3 py-1 text-xs rounded-full bg-yellow-50 hover:bg-yellow-100 text-yellow-700 transition-colors">
                Media
            </button>
        </div>
    `;
    
    header.insertAdjacentHTML('beforeend', filterHtml);
}

/**
 * Add pagination controls
 */
function addPaginationControls(container) {
    const paginationHtml = `
        <div id="activityPagination" class="flex items-center justify-between mt-4 pt-4 border-t border-gray-200">
            <button id="prevPageBtn" class="flex items-center px-3 py-1 text-sm text-gray-600 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Previous
            </button>
            <span id="pageInfo" class="text-sm text-gray-500">Page 1</span>
            <button id="nextPageBtn" class="flex items-center px-3 py-1 text-sm text-gray-600 hover:text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                Next
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>
        </div>
    `;
    
    container.insertAdjacentHTML('beforeend', paginationHtml);
}

/**
 * Setup event listeners
 */
function setupEventListeners() {
    // Filter button clicks
    document.addEventListener('click', (e) => {
        if (e.target.classList.contains('activity-filter-btn')) {
            const filter = e.target.dataset.filter;
            setActiveFilter(e.target, filter);
            loadActivityFeed(1, filter);
        }
    });
    
    // Pagination clicks
    document.getElementById('prevPageBtn')?.addEventListener('click', () => {
        if (window.ACTIVITY_FEED.currentPage > 1) {
            loadActivityFeed(window.ACTIVITY_FEED.currentPage - 1, window.ACTIVITY_FEED.currentType);
        }
    });
    
    document.getElementById('nextPageBtn')?.addEventListener('click', () => {
        loadActivityFeed(window.ACTIVITY_FEED.currentPage + 1, window.ACTIVITY_FEED.currentType);
    });
}

/**
 * Set active filter button
 */
function setActiveFilter(button, filter) {
    // Remove active class from all buttons
    document.querySelectorAll('.activity-filter-btn').forEach(btn => {
        btn.classList.remove('active', 'bg-gray-800', 'text-white');
        btn.classList.add('bg-gray-100', 'hover:bg-gray-200');
    });
    
    // Add active class to clicked button
    button.classList.add('active', 'bg-gray-800', 'text-white');
    button.classList.remove('bg-gray-100', 'hover:bg-gray-200');
    
    window.ACTIVITY_FEED.currentType = filter || null;
}

/**
 * Load activity feed data
 */
async function loadActivityFeed(page = 1, type = null) {
    if (window.ACTIVITY_FEED.isLoading) return;
    
    window.ACTIVITY_FEED.isLoading = true;
    
    // Show loading state
    showLoadingState();
    
    try {
        const params = new URLSearchParams({
            page: page,
            per_page: window.ACTIVITY_FEED.perPage
        });
        
        if (type) {
            params.append('type', type);
        }
        
        const response = await fetch(`/admin/activity-feed?${params}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}`);
        }
        
        const data = await response.json();
        
        if (data.success) {
            renderActivityFeed(data.data);
            updatePagination(data.page, data.data.length);
            window.ACTIVITY_FEED.currentPage = data.page;
            window.ACTIVITY_FEED.lastUpdate = new Date();
        }
        
    } catch (error) {
        console.error('Failed to load activity feed:', error);
        showErrorState();
    } finally {
        window.ACTIVITY_FEED.isLoading = false;
        hideLoadingState();
    }
}

/**
 * Render activity feed items
 */
function renderActivityFeed(activities) {
    const container = document.querySelector('#activityFeedContainer .activity-list');
    if (!container) return;
    
    if (activities.length === 0) {
        container.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 4V2a1 1 0 011-1h8a1 1 0 011 1v2m-9 3v10a2 2 0 002 2h6a2 2 0 002-2V7H7z"></path>
                </svg>
                <p class="text-gray-500">No activity found</p>
            </div>
        `;
        return;
    }
    
    const activitiesHtml = activities.map(renderActivityItem).join('');
    container.innerHTML = activitiesHtml;
}

/**
 * Render single activity item
 */
function renderActivityItem(activity) {
    const timeAgo = formatTimeAgo(new Date(activity.time));
    
    return `
        <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors duration-200">
            <div class="flex-shrink-0">
                <div class="w-8 h-8 bg-${activity.color}-100 rounded-full flex items-center justify-center">
                    ${getActivityIcon(activity.icon, activity.color)}
                </div>
            </div>
            
            <div class="flex-1 min-w-0">
                <div class="flex items-center justify-between">
                    <p class="text-sm font-medium text-gray-900">${activity.title}</p>
                    <span class="text-xs text-gray-500">${timeAgo}</span>
                </div>
                <p class="text-sm text-gray-600 mt-1">${truncateText(activity.description, 50)}</p>
                <div class="flex items-center mt-2 space-x-2">
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">
                        ${activity.user}
                    </span>
                    ${activity.url ? `<a href="${activity.url}" class="text-xs text-blue-600 hover:text-blue-800 font-medium">View →</a>` : ''}
                </div>
            </div>
        </div>
    `;
}

/**
 * Get activity icon SVG
 */
function getActivityIcon(icon, color) {
    const icons = {
        'document-text': `<svg class="w-4 h-4 text-${color}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>`,
        'user-plus': `<svg class="w-4 h-4 text-${color}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>`,
        'photograph': `<svg class="w-4 h-4 text-${color}-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>`
    };
    
    return icons[icon] || icons['document-text'];
}

/**
 * Update pagination controls
 */
function updatePagination(currentPage, itemCount) {
    const pageInfo = document.getElementById('pageInfo');
    const prevBtn = document.getElementById('prevPageBtn');
    const nextBtn = document.getElementById('nextPageBtn');
    
    if (pageInfo) {
        pageInfo.textContent = `Page ${currentPage}`;
    }
    
    if (prevBtn) {
        prevBtn.disabled = currentPage <= 1;
    }
    
    if (nextBtn) {
        nextBtn.disabled = itemCount < window.ACTIVITY_FEED.perPage;
    }
}

/**
 * Start real-time polling
 */
function startPolling() {
    // Poll every 30 seconds
    window.ACTIVITY_FEED.pollingInterval = setInterval(() => {
        if (!window.ACTIVITY_FEED.isLoading && window.ACTIVITY_FEED.currentPage === 1) {
            loadActivityFeed(1, window.ACTIVITY_FEED.currentType);
        }
    }, 30000);
}

/**
 * Stop polling
 */
function stopPolling() {
    if (window.ACTIVITY_FEED.pollingInterval) {
        clearInterval(window.ACTIVITY_FEED.pollingInterval);
        window.ACTIVITY_FEED.pollingInterval = null;
    }
}

/**
 * Utility functions
 */
function showLoadingState() {
    const container = document.querySelector('#activityFeedContainer .activity-list');
    if (container) {
        container.style.opacity = '0.6';
        container.style.pointerEvents = 'none';
    }
}

function hideLoadingState() {
    const container = document.querySelector('#activityFeedContainer .activity-list');
    if (container) {
        container.style.opacity = '1';
        container.style.pointerEvents = 'auto';
    }
}

function showErrorState() {
    const container = document.querySelector('#activityFeedContainer .space-y-4');
    if (container) {
        container.innerHTML = `
            <div class="text-center py-8">
                <svg class="w-12 h-12 text-red-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-500">Failed to load activity feed</p>
                <button onclick="window.location.reload()" class="mt-2 text-sm text-blue-600 hover:text-blue-800">Try again</button>
            </div>
        `;
    }
}

function formatTimeAgo(date) {
    const now = new Date();
    const diff = now - date;
    const seconds = Math.floor(diff / 1000);
    const minutes = Math.floor(seconds / 60);
    const hours = Math.floor(minutes / 60);
    const days = Math.floor(hours / 24);
    
    if (days > 0) return `${days}d ago`;
    if (hours > 0) return `${hours}h ago`;
    if (minutes > 0) return `${minutes}m ago`;
    return 'Just now';
}

function truncateText(text, length) {
    return text.length > length ? text.substring(0, length) + '...' : text;
}

// Cleanup on page unload
window.addEventListener('beforeunload', () => {
    stopPolling();
});

// Make functions globally available
window.initActivityFeed = initActivityFeed;
window.loadActivityFeed = loadActivityFeed;