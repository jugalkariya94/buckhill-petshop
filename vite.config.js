import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import vuetify from 'vite-plugin-vuetify'

export default defineConfig({
    server: {
        host: '0.0.0.0',
        hmr: {
            host: 'localhost'
        },
    },
    plugins: [
        vue(),
        vuetify({ autoImport: true }),
        laravel([
            'resources/sass/app.scss',
            'resources/js/app.ts',
        ]),
    ],
});
