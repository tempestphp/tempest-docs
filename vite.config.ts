import tailwindcss from '@tailwindcss/vite'
import vue from '@vitejs/plugin-vue'
import { defineConfig } from 'vite'
import tempest from 'vite-plugin-tempest'

export default defineConfig({
	plugins: [
		tailwindcss(),
		tempest(),
		vue(),
		{
			name: 'tempest:markdown:watch',
			handleHotUpdate({ file, server }) {
				if (file.endsWith('.md')) {
					server.ws.send({ type: 'full-reload' })
				}
			},
		},
	],
})
