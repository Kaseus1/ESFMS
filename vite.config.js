import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    server: {
        host: '127.0.0.1', 
        port: 5173,        
        hmr: {
            host: 'esfms.test', 
        },
    },
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
            ],
            refresh: true,
            detectTls: 'esfms.test', 
        }),
    ],
});