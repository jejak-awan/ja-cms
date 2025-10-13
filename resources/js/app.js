import './bootstrap';
import { createApp } from 'vue';

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

// Import all Vue components
const app = createApp({});

// Auto-register all components in the components directory
const components = import.meta.glob('./components/**/*.vue', { eager: true });
for (const path in components) {
    const componentName = path.split('/').pop().replace(/\.\w+$/, '');
    app.component(componentName, components[path].default);
}

// Mount the app
app.mount('#app');
