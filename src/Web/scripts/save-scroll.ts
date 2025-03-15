document.addEventListener('DOMContentLoaded', () => {
	const elements = document.querySelectorAll<HTMLElement>('[data-save-scroll]')

	elements.forEach((element) => {
		const key = `scroll-pos-${element.getAttribute('data-save-scroll')}`

		const savedScroll = localStorage.getItem(key)
		if (savedScroll !== null) {
			element.scrollTop = Number.parseInt(savedScroll, 10)
		}

		element.addEventListener('scroll', () => {
			localStorage.setItem(key, element.scrollTop.toString())
		})
	})
})
