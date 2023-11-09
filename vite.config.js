import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import VueMacros from 'unplugin-vue-macros/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: 'resources/js/app.js',
            refresh: true,
        }),
        VueMacros({
            plugins: {
                vue: vue({
                    template: {
                        transformAssetUrls: {
                            base: null,
                            includeAbsolute: false,
                        },
                    },
                    script: {
                        defineModel: true,
                    },
                }),
            },
        }),
    ],
    server: {
        host: '0.0.0.0',
        port: 3001,
        open: false,
        cors: {
            origin: '*'
        },
        hmr: {
            host: 'localhost',
        },
    }
});
