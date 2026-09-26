import tailwindcss from '@tailwindcss/vite';
import daisyui from 'daisyui';
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        daisyui({ theme: ['corporate'], darkTheme: 'dracula' }),
    ],
    css: {
        postcss: {
            plugins: [tailwindcss],
        },
        preflight: false,
    },
});