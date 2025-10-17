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

// Make CKEditor functions available globally (lazy loaded)
// CKEditor will be loaded on-demand when initCKEditor is called
window.initCKEditor = async function(element = '#content', customConfig = {}) {
    const { initCKEditor } = await import('./ckeditor-config');
    return initCKEditor(element, customConfig);
};

window.destroyCKEditor = async function(element = '#content') {
    const { destroyCKEditor } = await import('./ckeditor-config');
    return destroyCKEditor(element);
};

window.getCKEditorContent = async function(element = '#content') {
    const { getCKEditorContent } = await import('./ckeditor-config');
    return getCKEditorContent(element);
};

window.setCKEditorContent = async function(element = '#content', content = '') {
    const { setCKEditorContent } = await import('./ckeditor-config');
    return setCKEditorContent(element, content);
};

// Backward compatibility aliases (TinyMCE → CKEditor)
window.initTinyMCE = window.initCKEditor;
window.removeTinyMCE = window.destroyCKEditor;
window.getTinyMCEContent = window.getCKEditorContent;
window.setTinyMCEContent = window.setCKEditorContent;

// Vue components removed - using Alpine.js only for admin panel
// Vue is only used for public frontend if needed
