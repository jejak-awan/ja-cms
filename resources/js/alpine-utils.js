/**
 * Alpine.js Utilities and Common Patterns
 * Provides reusable Alpine.js components and utilities for the CMS
 */

/**
 * Language Switcher Alpine Component
 * Used for dropdown language selection
 */
export function languageSwitcher() {
    return {
        open: false,
        
        init() {
            // Initialize any required setup
            this.$nextTick(() => {
                console.log('Language switcher initialized');
            });
        },
        
        toggle() {
            this.open = !this.open;
        },
        
        close() {
            this.open = false;
        },
        
        switchLanguage(locale) {
            // Submit form to switch language
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '/locale/switch';
            
            const csrf = document.createElement('input');
            csrf.type = 'hidden';
            csrf.name = '_token';
            csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            const localeInput = document.createElement('input');
            localeInput.type = 'hidden';
            localeInput.name = 'locale';
            localeInput.value = locale;
            
            form.appendChild(csrf);
            form.appendChild(localeInput);
            document.body.appendChild(form);
            form.submit();
        }
    };
}

/**
 * Dropdown Component
 * Generic dropdown for various UI elements
 */
export function dropdown() {
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
 * Modal Component
 * For modal dialogs and popups
 */
export function modal() {
    return {
        open: false,
        
        show() {
            this.open = true;
            document.body.style.overflow = 'hidden';
        },
        
        hide() {
            this.open = false;
            document.body.style.overflow = 'auto';
        },
        
        init() {
            // Close modal on escape key
            this.$watch('open', value => {
                if (value) {
                    document.addEventListener('keydown', this.handleEscape);
                } else {
                    document.removeEventListener('keydown', this.handleEscape);
                }
            });
        },
        
        handleEscape(e) {
            if (e.key === 'Escape') {
                this.hide();
            }
        }
    };
}

/**
 * Form Validation Component
 * Basic form validation patterns
 */
export function formValidation() {
    return {
        errors: {},
        
        validateField(field, value, rules) {
            this.errors[field] = [];
            
            if (rules.required && (!value || value.trim() === '')) {
                this.errors[field].push('This field is required');
            }
            
            if (rules.email && value && !this.isValidEmail(value)) {
                this.errors[field].push('Please enter a valid email address');
            }
            
            if (rules.minLength && value && value.length < rules.minLength) {
                this.errors[field].push(`Minimum length is ${rules.minLength} characters`);
            }
            
            if (rules.maxLength && value && value.length > rules.maxLength) {
                this.errors[field].push(`Maximum length is ${rules.maxLength} characters`);
            }
        },
        
        isValidEmail(email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailRegex.test(email);
        },
        
        hasError(field) {
            return this.errors[field] && this.errors[field].length > 0;
        },
        
        getErrors(field) {
            return this.errors[field] || [];
        }
    };
}

/**
 * Notification Component
 * For toast notifications and alerts
 */
export function notification() {
    return {
        notifications: [],
        
        show(message, type = 'info', duration = 3000) {
            const id = Date.now();
            const notification = {
                id,
                message,
                type,
                visible: true
            };
            
            this.notifications.push(notification);
            
            if (duration > 0) {
                setTimeout(() => {
                    this.hide(id);
                }, duration);
            }
        },
        
        hide(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications[index].visible = false;
                setTimeout(() => {
                    this.notifications.splice(index, 1);
                }, 300); // Allow time for fade out animation
            }
        },
        
        success(message, duration = 3000) {
            this.show(message, 'success', duration);
        },
        
        error(message, duration = 5000) {
            this.show(message, 'error', duration);
        },
        
        warning(message, duration = 4000) {
            this.show(message, 'warning', duration);
        },
        
        info(message, duration = 3000) {
            this.show(message, 'info', duration);
        }
    };
}

/**
 * Loading State Component
 * For managing loading states
 */
export function loadingState() {
    return {
        loading: false,
        
        startLoading() {
            this.loading = true;
        },
        
        stopLoading() {
            this.loading = false;
        },
        
        async withLoading(asyncFunction) {
            this.startLoading();
            try {
                const result = await asyncFunction();
                return result;
            } finally {
                this.stopLoading();
            }
        }
    };
}

/**
 * Tab Component
 * For tabbed interfaces
 */
export function tabs(defaultTab = 0) {
    return {
        activeTab: defaultTab,
        
        setActiveTab(index) {
            this.activeTab = index;
        },
        
        isActive(index) {
            return this.activeTab === index;
        }
    };
}

/**
 * Accordion Component
 * For collapsible content sections
 */
export function accordion(allowMultiple = false) {
    return {
        openItems: allowMultiple ? [] : null,
        
        toggle(index) {
            if (allowMultiple) {
                const itemIndex = this.openItems.indexOf(index);
                if (itemIndex > -1) {
                    this.openItems.splice(itemIndex, 1);
                } else {
                    this.openItems.push(index);
                }
            } else {
                this.openItems = this.openItems === index ? null : index;
            }
        },
        
        isOpen(index) {
            if (allowMultiple) {
                return this.openItems.includes(index);
            }
            return this.openItems === index;
        }
    };
}

/**
 * Search Component
 * For search functionality with debouncing
 */
export function search(searchCallback, debounceMs = 300) {
    return {
        query: '',
        results: [],
        loading: false,
        debounceTimer: null,
        
        search() {
            clearTimeout(this.debounceTimer);
            
            if (!this.query.trim()) {
                this.results = [];
                return;
            }
            
            this.debounceTimer = setTimeout(async () => {
                this.loading = true;
                try {
                    this.results = await searchCallback(this.query);
                } catch (error) {
                    console.error('Search error:', error);
                    this.results = [];
                } finally {
                    this.loading = false;
                }
            }, debounceMs);
        },
        
        clear() {
            this.query = '';
            this.results = [];
            clearTimeout(this.debounceTimer);
        }
    };
}

/**
 * Register all Alpine components globally
 */
import { 
    dashboardStats, 
    dashboardCharts, 
    activityFeed, 
    quickActions, 
    themeToggle, 
    mobileMenu, 
    notifications 
} from './alpine-components/dashboard.js';

export function registerAlpineComponents() {
    // Register components when Alpine is available
    document.addEventListener('alpine:init', () => {
        if (window.Alpine) {
            // Core components
            window.Alpine.data('languageSwitcher', languageSwitcher);
            window.Alpine.data('dropdown', dropdown);
            window.Alpine.data('modal', modal);
            window.Alpine.data('formValidation', formValidation);
            window.Alpine.data('notification', notification);
            window.Alpine.data('loadingState', loadingState);
            window.Alpine.data('tabs', tabs);
            window.Alpine.data('accordion', accordion);
            window.Alpine.data('search', search);
            
            // Dashboard components
            window.Alpine.data('dashboardStats', dashboardStats);
            window.Alpine.data('dashboardCharts', dashboardCharts);
            window.Alpine.data('activityFeed', activityFeed);
            window.Alpine.data('quickActions', quickActions);
            window.Alpine.data('themeToggle', themeToggle);
            window.Alpine.data('mobileMenu', mobileMenu);
            window.Alpine.data('notifications', notifications);
        }
    });
}