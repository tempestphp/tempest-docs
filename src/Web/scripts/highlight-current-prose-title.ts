function findPreviousH2(element: Element | null): HTMLHeadingElement | null {
	while (element && element.previousElementSibling) {
		element = element.previousElementSibling
		if (element.tagName === 'H2') {
			return element as HTMLHeadingElement
		}
	}

	return null
}

function updateActiveChapters(): void {
	const visibleH2s = new Set<string>()
	const elements = document.querySelectorAll<HTMLElement>('.prose *')
	const topMargin = 100

	for (const el of elements) {
		const rect = el.getBoundingClientRect()
		if (rect.top - topMargin >= 0 && rect.bottom <= window.innerHeight) {
			if (el.tagName === 'H2' || el.tagName === 'H1') {
				if (el.textContent) {
					visibleH2s.add(el.textContent.trim())
				}
			} else {
				const previousH2 = findPreviousH2(el)
				if (previousH2 && previousH2.textContent) {
					visibleH2s.add(previousH2.textContent.trim())
				}
			}
		}
	}

	document.querySelectorAll<HTMLElement>('[data-on-this-page]').forEach((link) => {
		const section = link.getAttribute('data-on-this-page')
		if (section && visibleH2s.has(section)) {
			link.setAttribute('data-active', 'true')
		} else {
			link.removeAttribute('data-active')
		}
	})
}

document.addEventListener('DOMContentLoaded', () => {
	if (!document.querySelector('.prose[highlights-titles]')) {
		return
	}

	window.addEventListener('scroll', () => requestAnimationFrame(updateActiveChapters))
	updateActiveChapters()
})
