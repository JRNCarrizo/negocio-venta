import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/js/app.js', 'resources/css/app.css'],
            refresh: true,
        }),
    ],
    server: {
        proxy: {
            '/app': 'http://localhost:8000', // Asegúrate de que este puerto sea correcto
        },
    },
});