document.addEventListener('DOMContentLoaded', () => {
	let spawnTimeoutId: ReturnType<typeof setTimeout> | undefined
	const container = document.getElementById('leaves-container') as HTMLDivElement
	const templates = [
		document.getElementById('leaf-template-1') as HTMLTemplateElement,
		document.getElementById('leaf-template-2') as HTMLTemplateElement,
		document.getElementById('leaf-template-3') as HTMLTemplateElement,
	]

	if (!container) {
		console.warn('Container for falling leaves not found.')
		return
	}

	const colors = [
		'--color-leaf-1',
		'--color-leaf-2',
		'--color-leaf-3',
		'--color-leaf-4',
		'--color-leaf-5',
		'--color-leaf-6',
		'--color-leaf-7',
		'--color-leaf-8',
		'--color-leaf-9',
	]

	const BASE_FALL_SPEED = 70 // px/s
	const SPEED_VARIATION = 20 // +- px/s
	const MIN_SPAWN_DELAY = 5_00 // sec
	const SPAWN_DELAY_VARIATION = 15_00 // +- sec
	const MAX_LEAVES = 10
	let activeLeaves = 0
	let shouldRemoveLeaves = false

	function createLeaf() {
		if (activeLeaves >= MAX_LEAVES || shouldRemoveLeaves) {
			return
		}

		const initialBlur = 4
		const color = colors[Math.floor(Math.random() * colors.length)]
		const scale = 0.4 + Math.random() * 0.4 // Scale between 0.6 and 1
		const startPos = Math.random() * 100 // Random horizontal position (0-100%)
		const rotation = 180 + Math.random() * 180 // Random initial rotation
		const swayAmount = -40 + Math.random() * -140 // How much the leaf sways (30-60px)

		// Calculate a consistent fall speed in pixels per second with slight variation
		const fallSpeed = BASE_FALL_SPEED + (Math.random() * SPEED_VARIATION * 2 - SPEED_VARIATION)
		const totalDistance = container.clientHeight + 100
		const duration = totalDistance / fallSpeed

		const leafNode = templates[Math.floor(Math.random() * templates.length)]?.content.cloneNode(true) as DocumentFragment
		const leaf = leafNode.querySelector('.leaf') as HTMLDivElement

		leaf.style.color = `var(${color})`
		leaf.style.transform = `scale(${scale}) rotate(${rotation}deg)`
		leaf.style.left = `${startPos}%`
		leaf.style.top = '-50px'
		leaf.style.opacity = '0.8'
		leaf.style.filter = `blur(${initialBlur}px)`

		// Increment active leaves count
		activeLeaves++

		if (!shouldRemoveLeaves) {
			scheduleNextLeaf()
		}

		// Animation
		let progress = 0
		let posX = startPos
		const startTime = performance.now()

		function animateLeaf(currentTime: DOMHighResTimeStamp) {
			// Calculate progress based on elapsed time and duration
			const elapsed = currentTime - startTime
			progress = (elapsed / (duration * 1000)) * 100 // Convert duration to ms

			if (progress >= 100) {
				container.removeChild(leaf)
				activeLeaves--
				setTimeout(createLeaf, Math.random() * 2000) // Create a new leaf after a random delay
				return
			}

			// Calculate vertical position based on progress
			const posY = (progress / 100) * (container.clientHeight + 50) - 50

			// Add a gentle swaying motion with sine function
			const sway = Math.sin(progress / 10) * swayAmount
			posX = Number.parseFloat(startPos.toString()) + (sway / container.clientWidth) * 100

			// Add to rotation between 0 and 180 depending on the sway
			const rotationModifier = Math.sin(progress / 10) * 90

			// Increase scale the closer to the bottom of the screen
			const scaleModifier = (posY / container.clientHeight) * 0.2

			// Decrease blur the closer to the bottom the screen, but still blurry at the end
			const blurModifier = (posY / container.clientHeight) * 1.5

			// Update position and rotation
			leaf.style.top = `${posY}px`
			leaf.style.left = `${posX}%`
			leaf.style.transform = `scale(${scale + scaleModifier}) rotate(${rotation + rotationModifier}deg)`
			leaf.style.filter = `blur(${initialBlur - blurModifier}px)`

			requestAnimationFrame(animateLeaf)
		}

		container.appendChild(leaf)
		requestAnimationFrame(animateLeaf)
	}

	// Initialize by creating first leaf
	if (!(window as any).isDark()) {
		scheduleNextLeaf()
	}

	function scheduleNextLeaf() {
		spawnTimeoutId = setTimeout(createLeaf, MIN_SPAWN_DELAY + Math.random() * SPAWN_DELAY_VARIATION)
	}

	function cleanup() {
		if (spawnTimeoutId) {
			clearTimeout(spawnTimeoutId)
		}
	}

	document.addEventListener('theme-changed', ({ detail }: any) => {
		if (detail.isDark) {
			shouldRemoveLeaves = true
			cleanup()
		} else {
			shouldRemoveLeaves = false
			scheduleNextLeaf()
		}
	})

	window.addEventListener('unload', cleanup)
})
