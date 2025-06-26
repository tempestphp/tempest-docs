function extractPlainText(pre: HTMLElement, button?: HTMLButtonElement): string {
	return Array.from(pre.childNodes)
		.filter((node) => node !== button)
		.map((node) =>
			node.nodeType === Node.TEXT_NODE
				? node.textContent || ''
				: node.nodeType === Node.ELEMENT_NODE
				? (node as HTMLElement).textContent || ''
				: ''
		)
		.join('')
		.trim()
}

document.addEventListener('DOMContentLoaded', () => {
	const template = document.getElementById('copy-template') as HTMLTemplateElement | null
	if (!template) {
		return
	}

	document.querySelectorAll<HTMLPreElement>('.prose pre').forEach((pre) => {
		const copyButton = (template.content.cloneNode(true) as DocumentFragment).querySelector('button') as
			| HTMLButtonElement
			| null
		if (!copyButton) {
			return
		}

		pre.classList.add('relative', 'group')
		pre.appendChild(copyButton)

		copyButton.addEventListener('click', () => {
			const content = extractPlainText(pre, copyButton)
			navigator.clipboard
				.writeText(content)
				.then(() => {
					copyButton.setAttribute('data-copied', 'true')
					setTimeout(() => copyButton.removeAttribute('data-copied'), 2000)
				})
				.catch((err) => console.error('Copy failed', err))
		})
	})
})

document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll<HTMLPreElement>('button[data-copy]').forEach((button) => {
		button.addEventListener('click', () => {
			const target = document.querySelector(button.dataset.copy!) as HTMLElement
			console.log(target)

			if (!target) {
				return
			}

			const content = extractPlainText(target)

			navigator.clipboard
				.writeText(content)
				.then(() => {
					button.setAttribute('data-copied', 'true')
					setTimeout(() => button.removeAttribute('data-copied'), 2000)
				})
				.catch((err) => console.error('Copy failed', err))
		})
	})
})
