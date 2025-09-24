import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin.css',
                'resources/css/admin-banners.css',
                'resources/css/admin-content.css',
                'resources/css/admin-services.css',
                'resources/css/article-page.css',
                'resources/css/gallery-swiper.css',
                'resources/css/gallery-type-3-override.css',
                'resources/css/home-page.css',
                'resources/css/interactive-editing.css',
                'resources/css/mobile-navigation.css',
                'resources/css/owner-defaults.css',
                'resources/css/photo-editor.css',
                'resources/css/services-reels.css',
                'resources/css/themes.css',
                'resources/css/variables.css',
                
                'resources/js/app.js',
                  'resources/js/upload-progress.js',
                'resources/js/admin-articles.js',
                'resources/js/admin-banners.js',
                'resources/js/admin-forms.js',
                'resources/js/admin-gallery.js',
                'resources/js/admin-images.js',
                'resources/js/admin-loading.js',
                'resources/js/modal-scroll-lock.js',
                'resources/js/admin-toggles.js',
                'resources/js/bootstrap.js',
                'resources/js/interactive-editing.js',
                'resources/js/long-press-editor.js',
                'resources/js/mobile-navigation.js',
                'resources/js/photo-editor.js',
                'resources/js/theme-manager.js',
                  'resources/js/pwa-installer.js'
            ],
            refresh: true,
        }),
    ],
});