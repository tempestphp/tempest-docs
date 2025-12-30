function restoreScrollContainers() {
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
}

function scrollIntoView() {
	const elements = document.querySelectorAll<HTMLElement>('[data-scroll-into-view]')

	elements.forEach((element) => {
		const id = element.getAttribute('data-scroll-into-view')
		if (!id) {
			return
		}

		const target = document.getElementById(id)
		const outsideOfContainer = element.getBoundingClientRect().top < 0
			|| element.getBoundingClientRect().bottom > window.innerHeight

		if (target && outsideOfContainer) {
			target.scrollTo({ behavior: 'smooth', top: element.offsetTop })
		}
	})
}

document.addEventListener('DOMContentLoaded', () => {
	restoreScrollContainers()
	scrollIntoView()
})
