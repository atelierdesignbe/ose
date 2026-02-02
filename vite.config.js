import { defineConfig } from 'vite';
// import liveReload from 'vite-plugin-live-reload';
import { resolve } from 'path';

export default defineConfig({

  
  // Dossier racine
  root: '',
  base: './',
  
  // Configuration du build
  build: {
    // Dossier de sortie
    outDir: 'wordpress/wp-content/themes/atelierdesign/dist',
    emptyOutDir: true,
    
    // Générer le manifest pour WordPress
    manifest: 'manifest.json',  
    
    // Désactiver le code splitting pour simplifier
    rollupOptions: {
      input: {
        // Point d'entrée unique
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
    host: true,  // Écoute sur 0.0.0.0
    port: 5173,
    strictPort: true,
    cors: true,
  },
  
  // Alias pour faciliter les imports
  resolve: {
    alias: {
      '@': resolve(__dirname, 'src'),
    },
  },
});