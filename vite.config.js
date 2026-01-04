import { defineConfig } from 'vite';
import tailwindcss from '@tailwindcss/vite';

export default defineConfig({
  plugins: [tailwindcss()],
  build: {
    manifest: 'manifest.json',
    outDir: 'public/build',
    emptyOutDir: true,
    rollupOptions: {
      input: './resources/js/app.js'
    }
  },
  publicDir: false,
  server: {
    port: 5173,
    proxy: {
      '/api': 'http://localhost'
    },
    hmr: {
      host: 'localhost',
    },
  }
});