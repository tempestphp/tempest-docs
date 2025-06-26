interface Particle {
	velocityX: number
	velocityY: number
	x: number
	y: number
	alpha: number
	color: string
}

interface Droplet {
	velocityX: number
	velocityY: number
	x: number
	y: number
	radius: number
	alpha: number
	color: string
}

document.addEventListener('DOMContentLoaded', () => {
	let rainIntensityTarget = 2
	let currentRainIntensity = 2

	const canvas = document.createElement('canvas')
	canvas.style.width = '100%'
	canvas.style.height = '100%'

	const ctx = canvas.getContext('2d')!
	const rainContainer = document.getElementById('rain-container')!

	if (!rainContainer) {
		return
	}

	rainContainer.appendChild(canvas)

	let width = 0
	let height = 0

	const particles: Particle[] = []
	const droplets: Droplet[] = []
	const baseCount = 5
	const baseRainCount = 2

	const rainSettings = {
		color: 200,
		opacity: 1,
		saturation: 50,
		lightness: 15,
		rainIntensity: 2,
		backgroundColor: { red: 0, green: 0, blue: 0, alpha: 1 },
	}

	function resizeCanvas() {
		width = canvas.width = window.innerWidth
		height = canvas.height = window.innerHeight
	}

	window.onresize = resizeCanvas
	resizeCanvas()

	function createRain(x: number, y: number, count: number = baseRainCount) {
		while (count--) {
			particles.push({
				velocityX: Math.random() * 0.25,
				velocityY: Math.random() * 9 + 1,
				x,
				y,
				alpha: 1,
				color:
					`hsla(${rainSettings.color}, ${rainSettings.saturation}%, ${rainSettings.lightness}%, ${rainSettings.opacity})`,
			})
		}
	}

	function createExplosion(x: number, y: number, color: string, count: number = baseCount) {
		while (count--) {
			droplets.push({
				velocityX: Math.random() * 4 - 2,
				velocityY: Math.random() * -4,
				x,
				y,
				radius: 0.65 + Math.floor(Math.random() * 1.6),
				alpha: 1,
				color,
			})
		}
	}

	function render(ctx: CanvasRenderingContext2D) {
		ctx.save()
		ctx.fillStyle =
			`rgba(${rainSettings.backgroundColor.red}, ${rainSettings.backgroundColor.green}, ${rainSettings.backgroundColor.blue}, ${rainSettings.backgroundColor.alpha})`
		ctx.fillRect(0, 0, width, height)

		const tau = Math.PI * 2

		particles.forEach((particle) => {
			ctx.globalAlpha = particle.alpha
			ctx.fillStyle = particle.color
			ctx.fillRect(particle.x, particle.y, particle.velocityY / 4, particle.velocityY)
		})

		droplets.forEach((droplet) => {
			ctx.globalAlpha = droplet.alpha
			ctx.fillStyle = droplet.color
			ctx.beginPath()
			ctx.arc(droplet.x, droplet.y, droplet.radius, 0, tau)
			ctx.fill()
		})

		ctx.restore()
	}

	function update() {
		particles.forEach((particle, index) => {
			particle.x += particle.velocityX
			particle.y += particle.velocityY + 5
			if (particle.y > height - 5) {
				particles.splice(index, 1)
				createExplosion(particle.x, particle.y, particle.color)
			}
		})

		droplets.forEach((droplet, index) => {
			droplet.x += droplet.velocityX
			droplet.y += droplet.velocityY
			droplet.radius -= 0.075
			if (droplet.alpha > 0) {
				droplet.alpha -= 0.005
			} else {
				droplet.alpha = 0
			}
			if (droplet.radius < 0) {
				droplets.splice(index, 1)
			}
		})

		for (let i = 0; i < rainSettings.rainIntensity; i++) {
			createRain(Math.floor(Math.random() * width), -15)
		}
	}

	function loop() {
		requestAnimationFrame(loop)
		updateRainIntensity()
		update()
		render(ctx)
	}

	loop()

	function updateRainIntensity() {
		const intensityChangeSpeed = 0.01

		if (currentRainIntensity < rainIntensityTarget) {
			currentRainIntensity += intensityChangeSpeed
			if (currentRainIntensity > rainIntensityTarget) {
				currentRainIntensity = rainIntensityTarget
			}
		} else if (currentRainIntensity > rainIntensityTarget) {
			currentRainIntensity -= intensityChangeSpeed
			if (currentRainIntensity < rainIntensityTarget) {
				currentRainIntensity = rainIntensityTarget
			}
		}

		rainSettings.rainIntensity = Math.floor(currentRainIntensity)
	}

	document.addEventListener('theme-changed', ({ detail }: any) => {
		if (detail.isDark) {
			rainIntensityTarget = 2
		} else {
			rainIntensityTarget = 0
		}
	})
})
