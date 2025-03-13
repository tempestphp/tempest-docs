import defineEslintConfig from '@innocenzi/eslint-config'

export default defineEslintConfig({
	tailwindcss: false, // https://github.com/francoismassart/eslint-plugin-tailwindcss/issues/384
	ignores: ['.github', 'public', '*.json', '**/composer.json'],
})
