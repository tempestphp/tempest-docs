// The server currently adds trailing slashes, which means all links using anchors will
// lead to full-page loads. This script replaces anchor-only URLs with absolute ones.

document.addEventListener('DOMContentLoaded', () => {
	const links = document.querySelectorAll<HTMLLinkElement>('a[href^="#"]')

	links.forEach((link) => {
		if (link.getAttribute('href')?.startsWith('#')) {
			link.href = `${document.location.pathname}${link.getAttribute('href')}`
		}
	})
})
