import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
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
    resolve: {
        alias: {
            vue: 'vue/dist/vue.esm-bundler.js',
        },
    },
    build: {
        rollupOptions: {
            output: {
                manualChunks: {
                    // Separate TinyMCE into its own chunk
                    'tinymce': ['tinymce'],
                    // Separate Vue into its own chunk
                    'vue': ['vue'],
                }
            }
        },
        chunkSizeWarningLimit: 600, // Increase warning limit to 600KB
    }
});
