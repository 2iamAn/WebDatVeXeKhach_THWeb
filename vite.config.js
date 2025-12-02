import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
    },
    build: {
        // Tối ưu production build
        rollupOptions: {
            output: {
                manualChunks: {
                    vendor: ['axios'],
                },
            },
        },
        // Tối ưu kích thước bundle
        chunkSizeWarningLimit: 1000,
        // Minify CSS và JS
        minify: 'terser',
        terserOptions: {
            compress: {
                drop_console: true, // Loại bỏ console.log trong production
            },
        },
    },
});
