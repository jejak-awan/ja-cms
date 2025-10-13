/**
 * TinyMCE Editor Configuration
 * TinyMCE 5 - Free version (no license required)
 */

import tinymce from 'tinymce/tinymce';

// Theme
import 'tinymce/themes/silver/theme';

// Icons (default)
import 'tinymce/icons/default/icons';

// Plugins
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/autolink';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/link';
import 'tinymce/plugins/image';
import 'tinymce/plugins/charmap';
import 'tinymce/plugins/preview';
import 'tinymce/plugins/anchor';
import 'tinymce/plugins/searchreplace';
import 'tinymce/plugins/visualblocks';
import 'tinymce/plugins/code';
import 'tinymce/plugins/fullscreen';
import 'tinymce/plugins/insertdatetime';
import 'tinymce/plugins/media';
import 'tinymce/plugins/table';
import 'tinymce/plugins/help';
import 'tinymce/plugins/wordcount';

/**
 * Initialize TinyMCE Editor
 * @param {string} selector - CSS selector for textarea
 * @param {object} customConfig - Additional configuration options
 */
export function initTinyMCE(selector = '#content', customConfig = {}) {
    const defaultConfig = {
        selector: selector,
        // Point to the bundled skins in public/build
        skin_url: '/build/tinymce/skins/ui/oxide',
        content_css: '/build/tinymce/skins/content/default/content.min.css',
        height: 500,
        menubar: 'file edit view insert format tools table help',
        
        plugins: [
            'advlist', 'autolink', 'lists', 'link', 'image', 'charmap',
            'preview', 'anchor', 'searchreplace', 'visualblocks',
            'code', 'fullscreen', 'insertdatetime', 'media', 'table',
            'help', 'wordcount'
        ],
        
        toolbar: 'undo redo | formatselect | bold italic forecolor backcolor | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media | removeformat code fullscreen | help',
        
        toolbar_mode: 'sliding',
        
        image_advtab: true,
        image_caption: true,
        
        link_default_target: '_blank',
        link_assume_external_targets: true,
        link_context_toolbar: true,
        
        content_style: `
            body { 
                font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Open Sans', 'Helvetica Neue', sans-serif; 
                font-size: 16px;
                line-height: 1.6;
                padding: 1rem;
            }
            img { max-width: 100%; height: auto; }
            pre { background: #f4f4f4; padding: 1rem; border-radius: 4px; }
            blockquote { border-left: 4px solid #ccc; margin: 1.5rem 0; padding-left: 1rem; color: #666; }
        `,
        
        // File & Image Upload (can be customized)
        images_upload_handler: function(blobInfo, progress) {
            return new Promise((resolve, reject) => {
                const formData = new FormData();
                formData.append('file', blobInfo.blob(), blobInfo.filename());
                
                fetch('/admin/upload-image', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                })
                .then(response => response.json())
                .then(result => {
                    if (result.location) {
                        resolve(result.location);
                    } else {
                        reject('Upload failed: ' + (result.error || 'Unknown error'));
                    }
                })
                .catch(error => {
                    reject('Upload failed: ' + error.message);
                });
            });
        },
        
        // Paste options
        paste_data_images: true,
        paste_as_text: false,
        smart_paste: true,
        
        // Mobile configuration
        mobile: {
            menubar: true,
            plugins: 'autosave lists autolink',
            toolbar: 'undo redo | bold italic | bullist numlist'
        }
    };
    
    // Merge custom config with defaults
    const config = { ...defaultConfig, ...customConfig };
    
    tinymce.init(config);
}

/**
 * Remove TinyMCE instance
 * @param {string} selector 
 */
export function removeTinyMCE(selector = '#content') {
    const editor = tinymce.get(selector.replace('#', ''));
    if (editor) {
        editor.remove();
    }
}

/**
 * Get TinyMCE content
 * @param {string} selector 
 * @returns {string}
 */
export function getTinyMCEContent(selector = '#content') {
    const editor = tinymce.get(selector.replace('#', ''));
    return editor ? editor.getContent() : '';
}

/**
 * Set TinyMCE content
 * @param {string} selector 
 * @param {string} content 
 */
export function setTinyMCEContent(selector = '#content', content = '') {
    const editor = tinymce.get(selector.replace('#', ''));
    if (editor) {
        editor.setContent(content);
    }
}

// Export tinymce for direct access if needed
export default tinymce;
