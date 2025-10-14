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
        tailwindcss(),
        viteStaticCopy({
            targets: [
                {
                    src: 'node_modules/tinymce/skins',
                    dest: 'tinymce'
                },
                {
                    src: 'node_modules/tinymce/icons',
                    dest: 'tinymce'
                },
                {
                    src: 'node_modules/tinymce/themes',
                    dest: 'tinymce'
                }
            ]
        })
    ],
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate TinyMCE into its own chunk
                    'tinymce': ['tinymce'],
                    // Separate Alpine.js into its own chunk
                    'alpine': ['alpinejs'],
                }
            }
        }
    }
});
