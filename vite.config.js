import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
  plugins: [laravel({ input: ["resources/css/publication.scss", "resources/css/studio.scss", "resources/css/login.scss", "resources/js/site.js", "resources/js/studio.js", "resources/js/studio-ui.js", "resources/js/composer.js", "resources/js/calculator.js", "resources/js/login.js"], refresh: true })],
  css: { preprocessorOptions: { scss: { loadPaths: ['node_modules'], silenceDeprecations: ['import', 'global-builtin', 'color-functions', 'if-function'] } } },
});
