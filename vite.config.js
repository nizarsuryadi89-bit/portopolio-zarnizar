import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/admin-mobile.css',
                'resources/sass/app.scss',
                'resources/js/app.js',
                'resources/css/gtk2026.css',
                'resources/js/gtk2026.js',
                'resources/css/pembelajaran.css',
                'resources/js/pembelajaran.js',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
});
