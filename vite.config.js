import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/main.css',
                'resources/css/themes/light.css',
                'resources/css/themes/dark.css',

                'resources/js/main.js',
            ],
            refresh: true,
        }),
    ],
    publicDir: 'public',
    base: '/',
    build: {
        assetsDir: '',
        copyPublicDir: false,
    },
});
