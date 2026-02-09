import { defineConfig } from 'vite';
import { resolve } from 'path';

export default defineConfig({
  // Pas de root défini = racine du projet (là où est vite.config.js)
  base: './',
  
  build: {
    // Build directement dans le thème
    outDir: 'wordpress/wp-content/themes/atelierdesign/dist',
    emptyOutDir: true,
    manifest: "manifest.json",
    
    rollupOptions: {
      input: {
        // ✅ Chemin depuis la racine du projet
        app: resolve(__dirname, 'src/app.js'),
      },
      output: {
        entryFileNames: '[name].[hash].js',
        chunkFileNames: '[name].[hash].js',
        assetFileNames: '[name].[hash].[ext]',
      },
    },
  },
  
  server: {
    host: true,
    port: 5173,
    strictPort: true,
    cors: true,
  },
  
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src'),
    },
  },
});