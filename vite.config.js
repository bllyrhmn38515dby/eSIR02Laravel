import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/sass/app.scss',
                'resources/js/app.js',
            ],
            refresh: true,
        }),
    ],
    server: {
        host: true, // Mendengarkan di semua interface jaringan
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: '192.168.1.9' // Host diset eksplisit agar CSS/JS bisa diload dari LAN
        }
    },
    css: {
        preprocessorOptions: {
            scss: {
                generateScopedName: "[name]__[local]___[hash:base64:5]",
                quietDeps: true,
                silenceDeprecations: ['import', 'color-functions', 'global-builtin', 'if-function', 'legacy-js-api'],
            },
        },
    },
});
