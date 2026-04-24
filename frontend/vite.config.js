import { fileURLToPath, URL } from 'node:url'
import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import vueDevTools from 'vite-plugin-vue-devtools'
import tailwindcss from '@tailwindcss/vite'
import { viteStaticCopy } from 'vite-plugin-static-copy'

export default defineConfig({
  preview: {
    allowedHosts: 'all'
  },
  plugins: [
    vue(),
    vueDevTools(),
    tailwindcss(),
    viteStaticCopy({
      targets: [
        { src: 'public/_redirects', dest: '' } 
      ]
    })
  ],
  resolve: {
    alias: {
      '@': fileURLToPath(new URL('./src', import.meta.url))
    },
  },
  server: {
    proxy: {
      '/api': {
        target: 'https://vet-backend-mzyt.onrender.com',
        changeOrigin: true,
        secure: false,
      },
    },
  },
})