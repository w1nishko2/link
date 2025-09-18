import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/admin-banners.css',
                'resources/css/admin-services.css',
                'resources/js/app.js',
                'resources/js/admin-loading.js',
                'resources/js/admin-toggles.js',
                'resources/js/admin-images.js',
                'resources/js/admin-forms.js',
                'resources/js/admin-services.js',
                'resources/js/admin-gallery.js',
                'resources/js/admin-banners.js',
                'resources/js/admin-articles.js'
            ],
            refresh: true,
        }),
    ],
});