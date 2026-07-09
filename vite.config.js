import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import os from 'os'; // Import modul OS bawaan Node.js

// Fungsi untuk mendapatkan IP Local secara otomatis
function getLocalIP() {
    const interfaces = os.networkInterfaces();
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            // Ambil IPv4 dan abaikan localhost (127.0.0.1)
            if (iface.family === 'IPv4' && !iface.internal) {
                return iface.address;
            }
        }
    }
    return '127.0.0.1';
}

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
        host: '0.0.0.0', // Listen di semua network
        port: 5173,
        strictPort: true,
        cors: true,
        hmr: {
            host: getLocalIP() // Otomatis inject IP WiFi yang sedang aktif!
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
