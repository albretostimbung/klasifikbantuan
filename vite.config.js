import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    server: {
        host: 'localhost',
        port: 5173,
        hmr: {
            host: 'localhost',
            port: 5173
        },
        watch: {
            usePolling: true,
            interval: 1000
        }
    },
    build: {
        assetsDir: 'css',
        rollupOptions: {
            input: {
                main: 'resources/js/app.js',
                css: 'resources/css/app.css'
            }
        }
    }
});
