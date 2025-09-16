import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/mobile-navigation.css',
                'resources/css/owner-defaults.css',
                'resources/css/services-reels.css',
                'resources/css/image-editor.css',
                'resources/js/app.js',
                'resources/js/mobile-navigation.js',
                'resources/js/image-editor.js',
                'resources/js/long-press-editor.js',
            ],
            refresh: true,
        }),
    ],
});
