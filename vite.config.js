import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/activity-feed.js'
            ],
            refresh: true,
        }),
        tailwindcss()
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate CKEditor into its own chunk for better loading
                    'ckeditor': ['ckeditor5'],
                    // Separate Alpine.js into its own chunk
                    'alpine': ['alpinejs'],
                }
            }
        }
    }
});
