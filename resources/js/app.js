import './bootstrap';
import Alpine from 'alpinejs';
import { registerAlpineComponents } from './alpine-utils';

// Register Alpine components
registerAlpineComponents();

// Initialize Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Dashboard Charts (lazy loaded)
window.initDashboardCharts = async function(chartData) {
    const module = await import('./dashboard-charts');
    return module.initDashboardCharts(chartData);
};

// Make TinyMCE functions available globally (lazy loaded)
// TinyMCE will be loaded on-demand when initTinyMCE is called
window.initTinyMCE = async function(selector = '#content', customConfig = {}) {
    const { initTinyMCE } = await import('./tinymce-config');
    return initTinyMCE(selector, customConfig);
};

window.removeTinyMCE = async function(selector = '#content') {
    const { removeTinyMCE } = await import('./tinymce-config');
    return removeTinyMCE(selector);
};

window.getTinyMCEContent = async function(selector = '#content') {
    const { getTinyMCEContent } = await import('./tinymce-config');
    return getTinyMCEContent(selector);
};

window.setTinyMCEContent = async function(selector = '#content', content = '') {
    const { setTinyMCEContent } = await import('./tinymce-config');
    return setTinyMCEContent(selector, content);
};

// Vue components removed - using Alpine.js only for admin panel
// Vue is only used for public frontend if needed
