import { defineConfig } from 'vite'
import tempest from 'vite-plugin-tempest'
import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'

export default defineConfig({
	plugins: [
		tailwindcss(),
		tempest(),
		vue(),
	],
})
