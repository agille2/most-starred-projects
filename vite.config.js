import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import tailwindcss from '@tailwindcss/vite';

const devOrigin = process.env.VITE_DEV_SERVER_ORIGIN;
let hmrProtocol = 'ws';
let hmrHost;
let hmrPort;
if (devOrigin) {
    try {
        const url = new URL(devOrigin);
        hmrProtocol = url.protocol === 'https:' ? 'wss' : 'ws';
        hmrHost = url.hostname;
        hmrPort = url.port ? Number(url.port) : undefined;
    } catch (e) {
        // ignore invalid URL
    }
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        vue(),
        tailwindcss(),
    ],
    server: {
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
        host: '0.0.0.0',
        port: 5173,
        strictPort: true,
        origin: devOrigin,
        hmr: {
            protocol: hmrProtocol,
            host: hmrHost,
            port: hmrPort,
        },
    },
});
