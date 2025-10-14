/**
 * Dashboard Alpine.js Components
 * Optimized dashboard components using Alpine.js only
 */

/**
 * Dashboard Stats Component
 * Handles stats cards with animations
 */
export function dashboardStats() {
    return {
        stats: {},
        loading: true,
        
        init() {
            this.loadStats();
        },
        
        async loadStats() {
            try {
                // Simulate loading
                await new Promise(resolve => setTimeout(resolve, 500));
                this.loading = false;
            } catch (error) {
                console.error('Failed to load stats:', error);
            }
        },
        
        formatNumber(num) {
            return new Intl.NumberFormat().format(num);
        }
    };
}

/**
 * Dashboard Charts Component
 * Handles chart initialization and data
 */
export function dashboardCharts() {
    return {
        charts: {},
        chartData: {},
        
        init() {
            this.$nextTick(() => {
                this.initializeCharts();
            });
        },
        
        async initializeCharts() {
            // Wait for chart data to be available
            if (typeof window.initDashboardCharts === 'function') {
                try {
                    await window.initDashboardCharts(this.chartData);
                } catch (error) {
                    console.error('Failed to initialize charts:', error);
                }
            }
        },
        
        setChartData(data) {
            this.chartData = data;
            this.initializeCharts();
        }
    };
}

/**
 * Activity Feed Component
 * Handles real-time activity updates
 */
export function activityFeed() {
    return {
        activities: [],
        loading: false,
        page: 1,
        hasMore: true,
        
        init() {
            this.loadActivities();
        },
        
        async loadActivities() {
            if (this.loading) return;
            
            this.loading = true;
            try {
                // Simulate API call
                await new Promise(resolve => setTimeout(resolve, 300));
                this.loading = false;
            } catch (error) {
                console.error('Failed to load activities:', error);
                this.loading = false;
            }
        },
        
        loadMore() {
            if (this.hasMore && !this.loading) {
                this.page++;
                this.loadActivities();
            }
        }
    };
}

/**
 * Quick Actions Component
 * Handles quick action buttons
 */
export function quickActions() {
    return {
        actions: [
            {
                title: 'New Article',
                description: 'Create a new blog post',
                icon: 'document-text',
                color: 'blue',
                url: '/admin/articles/create'
            },
            {
                title: 'New Page',
                description: 'Create a static page',
                icon: 'document',
                color: 'purple',
                url: '/admin/pages/create'
            },
            {
                title: 'Add User',
                description: 'Invite new team member',
                icon: 'user-plus',
                color: 'green',
                url: '/admin/users/create'
            },
            {
                title: 'Upload Media',
                description: 'Add images & files',
                icon: 'photograph',
                color: 'yellow',
                url: '/admin/media'
            }
        ],
        
        getIconClass(icon) {
            const icons = {
                'document-text': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'document': 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                'user-plus': 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z',
                'photograph': 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'
            };
            return icons[icon] || icons['document'];
        },
        
        getColorClass(color) {
            const colors = {
                'blue': 'bg-blue-500 group-hover:bg-blue-600',
                'purple': 'bg-purple-500 group-hover:bg-purple-600',
                'green': 'bg-green-500 group-hover:bg-green-600',
                'yellow': 'bg-yellow-500 group-hover:bg-yellow-600'
            };
            return colors[color] || colors['blue'];
        }
    };
}

/**
 * Theme Toggle Component
 * Handles dark/light mode switching
 */
export function themeToggle() {
    return {
        darkMode: false,
        
        init() {
            // Check for saved theme preference
            this.darkMode = localStorage.getItem('theme') === 'dark' || 
                           document.documentElement.classList.contains('dark');
            this.applyTheme();
        },
        
        toggle() {
            this.darkMode = !this.darkMode;
            this.applyTheme();
        },
        
        applyTheme() {
            if (this.darkMode) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('theme', 'light');
            }
        }
    };
}

/**
 * Mobile Menu Component
 * Handles mobile sidebar toggle
 */
export function mobileMenu() {
    return {
        open: false,
        
        toggle() {
            this.open = !this.open;
        },
        
        close() {
            this.open = false;
        }
    };
}

/**
 * Notifications Component
 * Handles notification dropdown
 */
export function notifications() {
    return {
        open: false,
        notifications: [],
        unreadCount: 0,
        
        init() {
            this.loadNotifications();
        },
        
        toggle() {
            this.open = !this.open;
        },
        
        close() {
            this.open = false;
        },
        
        async loadNotifications() {
            // Simulate loading notifications
            this.notifications = [
                {
                    id: 1,
                    title: 'New article published',
                    message: 'John Doe published "Getting Started with Laravel"',
                    time: '2 minutes ago',
                    type: 'info',
                    read: false
                },
                {
                    id: 2,
                    title: 'New user registered',
                    message: 'Jane Smith created an account',
                    time: '1 hour ago',
                    type: 'success',
                    read: false
                }
            ];
            this.unreadCount = this.notifications.filter(n => !n.read).length;
        },
        
        markAsRead(notification) {
            notification.read = true;
            this.unreadCount = this.notifications.filter(n => !n.read).length;
        }
    };
}
