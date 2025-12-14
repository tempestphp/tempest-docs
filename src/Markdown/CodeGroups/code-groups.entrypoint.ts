document.addEventListener('DOMContentLoaded', () => {
	document.querySelectorAll<HTMLDivElement>('.code-group').forEach((codeGroup) => {
		const tabs = codeGroup.querySelectorAll<HTMLButtonElement>('.code-group-tab')
		const panels = codeGroup.querySelectorAll<HTMLDivElement>('.code-group-panel')

		tabs.forEach((tab) => {
			tab.addEventListener('click', () => {
				if (!tab.dataset.panel) {
					return
				}

				tabs.forEach((tab) => {
					tab.classList.remove('active')
					tab.setAttribute('aria-selected', 'false')
				})

				panels.forEach((panel) => {
					panel.classList.remove('active')
					panel.setAttribute('hidden', 'hidden')
				})

				tab.classList.add('active')
				tab.setAttribute('aria-selected', 'true')

				const targetPanel = document.getElementById(tab.dataset.panel)
				if (targetPanel) {
					targetPanel.classList.add('active')
					targetPanel.removeAttribute('hidden')
				}
			})
		})
	})
})
