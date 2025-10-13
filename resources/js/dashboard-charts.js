/**
 * Chart.js Configuration for JA-CMS Dashboard
 * Modern charts with beautiful gradients and animations
 */

import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    Filler
} from 'chart.js';

// Register Chart.js components
ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    BarElement,
    Title,
    Tooltip,
    Legend,
    ArcElement,
    Filler
);

/**
 * Chart.js default configuration with modern styling
 */
const defaultChartConfig = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'top',
            labels: {
                usePointStyle: true,
                padding: 20,
                font: {
                    size: 12,
                    family: 'Inter, system-ui, sans-serif'
                }
            }
        },
        tooltip: {
            backgroundColor: 'rgba(17, 24, 39, 0.95)',
            titleColor: '#F9FAFB',
            bodyColor: '#F9FAFB',
            borderColor: '#374151',
            borderWidth: 1,
            cornerRadius: 8,
            displayColors: true,
            intersect: false,
            mode: 'index'
        }
    },
    scales: {
        x: {
            grid: {
                display: false
            },
            ticks: {
                font: {
                    size: 11,
                    family: 'Inter, system-ui, sans-serif'
                },
                color: '#6B7280'
            }
        },
        y: {
            grid: {
                color: '#F3F4F6',
                drawBorder: false
            },
            ticks: {
                font: {
                    size: 11,
                    family: 'Inter, system-ui, sans-serif'
                },
                color: '#6B7280',
                padding: 10
            }
        }   
    },
    animation: {
        duration: 1000,
        easing: 'easeInOutQuart'
    }
};

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
    
    return new ChartJS(ctx, {
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
            ...defaultChartConfig,
            plugins: {
                ...defaultChartConfig.plugins,
                title: {
                    display: true,
                    text: 'Articles Published (Last 6 Months)',
                    font: {
                        size: 16,
                        weight: 'bold',
                        family: 'Inter, system-ui, sans-serif'
                    },
                    color: '#111827',
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
    
    return new ChartJS(ctx, {
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
            ...defaultChartConfig,
            plugins: {
                ...defaultChartConfig.plugins,
                title: {
                    display: true,
                    text: 'New User Registrations',
                    font: {
                        size: 16,
                        weight: 'bold',
                        family: 'Inter, system-ui, sans-serif'
                    },
                    color: '#111827',
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
    return new ChartJS(ctx, {
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
                    color: '#111827',
                    padding: 20
                },
                tooltip: {
                    backgroundColor: 'rgba(17, 24, 39, 0.95)',
                    titleColor: '#F9FAFB',
                    bodyColor: '#F9FAFB',
                    borderColor: '#374151',
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
    // Articles Chart
    const articlesCtx = document.getElementById('articlesChart');
    if (articlesCtx) {
        createArticlesChart(articlesCtx.getContext('2d'), chartData);
    }

    // Users Chart
    const usersCtx = document.getElementById('usersChart');
    if (usersCtx) {
        createUsersChart(usersCtx.getContext('2d'), chartData);
    }

    // Content Chart
    const contentCtx = document.getElementById('contentChart');
    if (contentCtx) {
        createContentChart(contentCtx.getContext('2d'), chartData);
    }
}

// Make functions globally available
window.initDashboardCharts = initDashboardCharts;
window.createArticlesChart = createArticlesChart;
window.createUsersChart = createUsersChart;
window.createContentChart = createContentChart;