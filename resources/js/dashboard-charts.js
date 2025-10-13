/**
 * Chart.js Configuration for JA-CMS Dashboard
 * Use the auto bundle to auto-register controllers/elements/scales
 */
import Chart from 'chart.js/auto';

// Global chart registry
window.DASHBOARD_CHARTS = window.DASHBOARD_CHARTS || {};

function isDark() {
    return document.documentElement.classList.contains('dark');
}

function getThemeColors() {
    if (isDark()) {
        return {
            textPrimary: '#E5E7EB',
            textSecondary: '#9CA3AF',
            surface: '#111827',
            grid: '#1F2937',
            border: '#374151',
            tooltipBg: 'rgba(17, 24, 39, 0.95)',
            title: '#F3F4F6',
        };
    }
    return {
        textPrimary: '#111827',
        textSecondary: '#6B7280',
        surface: '#FFFFFF',
        grid: '#F3F4F6',
        border: '#E5E7EB',
        tooltipBg: 'rgba(17, 24, 39, 0.95)',
        title: '#111827',
    };
}

/**
 * Chart.js default configuration with modern styling
 */
function buildDefaultConfig() {
    const c = getThemeColors();
    return {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 20,
                    color: c.textSecondary,
                    font: { size: 12, family: 'Inter, system-ui, sans-serif' }
                }
            },
            tooltip: {
                backgroundColor: c.tooltipBg,
                titleColor: '#F9FAFB',
                bodyColor: '#F9FAFB',
                borderColor: c.border,
                borderWidth: 1,
                cornerRadius: 8,
                displayColors: true,
                intersect: false,
                mode: 'index'
            }
        },
        scales: {
            x: {
                grid: { display: false },
                ticks: { font: { size: 11, family: 'Inter, system-ui, sans-serif' }, color: c.textSecondary }
            },
            y: {
                grid: { color: c.grid, drawBorder: false },
                ticks: { font: { size: 11, family: 'Inter, system-ui, sans-serif' }, color: c.textSecondary, padding: 10 }
            }
        },
        animation: { duration: 1000, easing: 'easeInOutQuart' }
    };
}

/**
 * Create gradient for charts
 */
function createGradient(ctx, color1, color2) {
    const gradient = ctx.createLinearGradient(0, 0, 0, 300);
    gradient.addColorStop(0, color1);
    gradient.addColorStop(1, color2);
    return gradient;
}

/**
 * Articles Growth Line Chart
 */
export function createArticlesChart(ctx, data) {
    const gradient = createGradient(ctx, 'rgba(59, 130, 246, 0.8)', 'rgba(59, 130, 246, 0.1)');
    const c = getThemeColors();
    
    return new Chart(ctx, {
        type: 'line',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'Published Articles',
                data: data.articles,
                borderColor: '#3B82F6',
                backgroundColor: gradient,
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointBackgroundColor: '#3B82F6',
                pointBorderColor: '#FFFFFF',
                pointBorderWidth: 2,
                pointRadius: 5,
                pointHoverRadius: 8
            }]
        },
        options: {
            ...buildDefaultConfig(),
            plugins: {
                ...buildDefaultConfig().plugins,
                title: {
                    display: true,
                    text: 'Articles Published (Last 6 Months)',
                    font: {
                        size: 16,
                        weight: 'bold',
                        family: 'Inter, system-ui, sans-serif'
                    },
                    color: c.title,
                    padding: 20
                }
            }
        }
    });
}

/**
 * Users Registration Bar Chart
 */
export function createUsersChart(ctx, data) {
    const gradient = createGradient(ctx, 'rgba(16, 185, 129, 0.8)', 'rgba(16, 185, 129, 0.1)');
    const c = getThemeColors();
    
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: data.labels,
            datasets: [{
                label: 'New Users',
                data: data.users,
                backgroundColor: gradient,
                borderColor: '#10B981',
                borderWidth: 2,
                borderRadius: 6,
                borderSkipped: false
            }]
        },
        options: {
            ...buildDefaultConfig(),
            plugins: {
                ...buildDefaultConfig().plugins,
                title: {
                    display: true,
                    text: 'New User Registrations',
                    font: {
                        size: 16,
                        weight: 'bold',
                        family: 'Inter, system-ui, sans-serif'
                    },
                    color: c.title,
                    padding: 20
                }
            }
        }
    });
}

/**
 * Content Types Pie Chart
 */
export function createContentChart(ctx, data) {
    const c = getThemeColors();
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Articles', 'Pages', 'Categories', 'Media'],
            datasets: [{
                data: [data.articles, data.pages, data.categories, data.media],
                backgroundColor: [
                    '#3B82F6', // Blue
                    '#8B5CF6', // Purple
                    '#10B981', // Green
                    '#F59E0B'  // Yellow
                ],
                borderColor: [
                    '#2563EB',
                    '#7C3AED', 
                    '#059669',
                    '#D97706'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        color: c.textSecondary,
                        font: {
                            size: 12,
                            family: 'Inter, system-ui, sans-serif'
                        }
                    }
                },
                title: {
                    display: true,
                    text: 'Content Distribution',
                    font: {
                        size: 16,
                        weight: 'bold',
                        family: 'Inter, system-ui, sans-serif'
                    },
                    color: c.title,
                    padding: 20
                },
                tooltip: {
                    backgroundColor: c.tooltipBg,
                    titleColor: '#F9FAFB',
                    bodyColor: '#F9FAFB',
                    borderColor: c.border,
                    borderWidth: 1,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                            return `${context.label}: ${context.parsed} (${percentage}%)`;
                        }
                    }
                }
            },
            cutout: '60%',
            animation: {
                animateRotate: true,
                duration: 1200
            }
        }
    });
}

/**
 * Initialize all dashboard charts
 */
export function initDashboardCharts(chartData) {
    // Normalize content object if caller passes full dataset
    const contentData = chartData.content || chartData;
    const doc = document;
    const reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const fadeIn = (el) => {
        if (!el) return;
        if (reduceMotion) {
            el.classList.remove('opacity-0');
            return;
        }
        requestAnimationFrame(() => {
            el.classList.remove('opacity-0');
        });
    };
    const toggleEmpty = (id, show) => {
        const el = doc.getElementById(id);
        if (!el) return;
        if (show) {
            el.classList.remove('hidden');
            el.classList.add('flex');
            // allow transition
            requestAnimationFrame(() => el.classList.remove('opacity-0'));
        } else {
            if (!reduceMotion) el.classList.add('opacity-0');
            // hide after transition
            setTimeout(() => el.classList.add('hidden'), reduceMotion ? 0 : 200);
            el.classList.remove('flex');
        }
    };
    const hideSkeleton = (id) => {
        const el = doc.getElementById(id);
        if (!el) return;
        if (reduceMotion) {
            el.classList.add('hidden');
            return;
        }
        el.classList.add('opacity-0');
        setTimeout(() => el.classList.add('hidden'), 150);
    };
    // Articles Chart
    const articlesCtx = doc.getElementById('articlesChart');
    if (articlesCtx) {
        window.DASHBOARD_CHARTS.articles = createArticlesChart(articlesCtx.getContext('2d'), chartData);
        // Hide skeleton and toggle empty state based on data
        hideSkeleton('articlesChartSkeleton');
        const hasArticles = Array.isArray(chartData.articles) && chartData.articles.some(v => (v || 0) > 0);
        toggleEmpty('articlesChartEmpty', !hasArticles);
        fadeIn(articlesCtx);
    }

    // Users Chart
    const usersCtx = doc.getElementById('usersChart');
    if (usersCtx) {
        window.DASHBOARD_CHARTS.users = createUsersChart(usersCtx.getContext('2d'), chartData);
        hideSkeleton('usersChartSkeleton');
        const hasUsers = Array.isArray(chartData.users) && chartData.users.some(v => (v || 0) > 0);
        toggleEmpty('usersChartEmpty', !hasUsers);
        fadeIn(usersCtx);
    }

    // Content Chart
    const contentCtx = doc.getElementById('contentChart');
    if (contentCtx) {
        const safeContent = contentData || { articles: 0, pages: 0, categories: 0, media: 0 };
        window.DASHBOARD_CHARTS.content = createContentChart(contentCtx.getContext('2d'), safeContent);
        hideSkeleton('contentChartSkeleton');
        const totalContent = (safeContent.articles || 0) + (safeContent.pages || 0) + (safeContent.categories || 0) + (safeContent.media || 0);
        toggleEmpty('contentChartEmpty', totalContent === 0);
        fadeIn(contentCtx);
    }
}

export function applyThemeToCharts() {
    const charts = window.DASHBOARD_CHARTS;
    if (!charts) return;
    const c = getThemeColors();
    const updateCommon = (chart) => {
        if (!chart) return;
        const o = chart.options;
        if (o.plugins?.legend?.labels) {
            o.plugins.legend.labels.color = c.textSecondary;
        }
        if (o.plugins?.title) {
            o.plugins.title.color = c.title;
        }
        if (o.plugins?.tooltip) {
            o.plugins.tooltip.backgroundColor = c.tooltipBg;
            o.plugins.tooltip.borderColor = c.border;
        }
        if (o.scales?.x) {
            if (o.scales.x.ticks) o.scales.x.ticks.color = c.textSecondary;
            if (o.scales.x.grid) o.scales.x.grid.color = false; // keep hidden
        }
        if (o.scales?.y) {
            if (o.scales.y.ticks) o.scales.y.ticks.color = c.textSecondary;
            if (o.scales.y.grid) o.scales.y.grid.color = c.grid;
        }
        chart.update();
    };
    updateCommon(charts.articles);
    updateCommon(charts.users);
    updateCommon(charts.content);
}

// React to theme changes
window.addEventListener('themechange', () => {
    applyThemeToCharts();
});

// Fallback: observe class changes for 'dark' toggle
try {
    const mo = new MutationObserver(() => applyThemeToCharts());
    mo.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });
} catch (_) {}

// Make functions globally available
window.initDashboardCharts = initDashboardCharts;
window.createArticlesChart = createArticlesChart;
window.createUsersChart = createUsersChart;
window.createContentChart = createContentChart;