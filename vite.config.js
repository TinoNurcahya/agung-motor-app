import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/landing.css',
                'resources/js/ui.js',
                'resources/js/animations.js',
                'resources/css/pages.css',
                'resources/js/pages.js',
                'resources/css/about.css',
                'resources/js/about.js',
                'resources/css/contact.css',
                'resources/js/contact.js'
            ],
            refresh: true,
        }),
    ],
});
