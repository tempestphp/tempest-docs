const config = {
	canvas: 'lightrays',
	rayCount: 8,
	rayWidth: 0.09,
	intensity: 1.2,
	angle: -25,
	speed: 0.7,
	softness: 2.5,
	overlap: 0.8, // overlaps the most towards 1.0
	movement: 0.5,
	verticalFade: -0.1, // the lower, the more it fades at the bottom
	horizontalFade: -0.2, // the lower, the more it fades towards the left
	widthDisparity: 0.4, // the higher, the more width variation between rays
	cssColorVariables: [
		'--color-ray-1',
		'--color-ray-2',
		'--color-ray-3',
		'--color-ray-4',
		'--color-ray-5',
	],
}

function fetchColorFromCssVariable(variableName: string): [number, number, number] {
	const temp = document.createElement('div')
	temp.style.display = 'none'
	temp.style.color = `var(${variableName})`
	document.body.appendChild(temp)
	const cssColor = getComputedStyle(temp).color
	document.body.removeChild(temp)

	const canvas2d = document.createElement('canvas')
	canvas2d.width = 1
	canvas2d.height = 1

	const ctx = canvas2d.getContext('2d')!
	ctx.fillStyle = cssColor
	ctx.fillRect(0, 0, 1, 1)

	const [r, g, b] = ctx.getImageData(0, 0, 1, 1).data
	if (r === undefined) {
		return [1.0, 0.0, 1.0]
	}

	return [r / 255, g / 255, b / 255]
}

function initializeLightRays(canvas: HTMLCanvasElement) {
	const gl = canvas.getContext('webgl') ?? canvas.getContext('experimental-webgl') as WebGLRenderingContext
	if (!gl) {
		console.error('WebGL not supported')
		return
	}

	const vertexShaderSource = `
    attribute vec2 a_position;
    void main() {
      gl_Position = vec4(a_position, 0.0, 1.0);
    }
  `

	const fragmentShaderSource = `
    precision mediump float;
    
    uniform vec2 u_resolution;
    uniform float u_time;
    uniform float u_rayCount;
    uniform float u_rayWidth;
    uniform float u_intensity;
    uniform float u_angle;
    uniform float u_speed;
    uniform float u_softness;
    uniform float u_overlap;
    uniform float u_movement;
    uniform float u_verticalFade;
    uniform float u_horizontalFade;
    uniform float u_widthDisparity;
    uniform vec3 u_colors[5];
    
    float hash(float n) {
      return fract(sin(n) * 43758.5453123);
    }
    
    float noise(float t, float seed) {
      float i = floor(t);
      float f = fract(t);
      float u = f * f * (3.0 - 2.0 * f);
      return mix(hash(i + seed), hash(i + 1.0 + seed), u);
    }
    
    float singleRay(vec2 uv, float angle, float offset, float width, float softness) {
      float angleRad = radians(angle);
      vec2 direction = vec2(cos(angleRad), sin(angleRad));
      float projected = dot(uv, direction);
      
      float rayPos = projected + offset;
      float dist = abs(fract(rayPos) - 0.5) * 2.0;
      
      return smoothstep(width + softness * 0.05, width - softness * 0.02, dist);
    }
    
    void main() {
      vec2 uv = gl_FragCoord.xy / u_resolution;
      
      float aspectRatio = u_resolution.x / u_resolution.y;
      vec2 adjustedUV = uv;
      adjustedUV.x *= aspectRatio;
      
      float verticalMask = pow(uv.y, 1.0 / (1.0 + u_verticalFade * 2.0));
      float horizontalMask = pow(uv.x, 1.0 / (1.0 + u_horizontalFade * 2.0));
      float fadeMask = verticalMask * horizontalMask;
      vec3 finalColor = vec3(0.98, 0.99, 1.0);
      
      for (float i = 0.0; i < 15.0; i++) {
        if (i >= u_rayCount) break;
        
        int colorIdx = int(mod(i, 5.0));
        vec3 rayColor = u_colors[0];
        
        if (colorIdx == 0) rayColor = u_colors[0];
        else if (colorIdx == 1) rayColor = u_colors[1];
        else if (colorIdx == 2) rayColor = u_colors[2];
        else if (colorIdx == 3) rayColor = u_colors[3];
        else rayColor = u_colors[4];
        
        float randomOffset = hash(i * 12.345) * (1.0 + u_overlap);
        
        float noiseSeed = i * 45.678;
        float noiseFreq = 0.3 + hash(i * 78.901) * 0.2;
        float lateralMovement = (noise(u_time * u_speed * noiseFreq, noiseSeed) - 0.5) * 2.0;
        
        float timeOffset = lateralMovement * u_movement * 0.3;
        float totalOffset = randomOffset + timeOffset;
        float wRand = hash(i * 91.123);   
        float widthFactor = mix(1.0, wRand * 2.0, u_widthDisparity);
        float rayIntensity = singleRay(adjustedUV, u_angle, totalOffset, u_rayWidth * widthFactor, u_softness);
        rayIntensity *= fadeMask;
        
        vec3 rayContribution = (rayColor - finalColor) * rayIntensity * u_intensity;
        finalColor += rayContribution;
      }
      
      gl_FragColor = vec4(finalColor, 1.0);
    }
  `

	function createShader(gl: WebGLRenderingContext, type: number, source: string): WebGLShader | null {
		const shader = gl.createShader(type)
		if (!shader) return null

		gl.shaderSource(shader, source)
		gl.compileShader(shader)

		if (!gl.getShaderParameter(shader, gl.COMPILE_STATUS)) {
			console.error('Shader compile error:', gl.getShaderInfoLog(shader))
			gl.deleteShader(shader)
			return null
		}

		return shader
	}

	function createProgram(
		gl: WebGLRenderingContext,
		vertexShader: WebGLShader,
		fragmentShader: WebGLShader,
	): WebGLProgram {
		const program = gl.createProgram()
		if (!program) {
			throw new Error('Unable to create WebGL program')
		}

		gl.attachShader(program, vertexShader)
		gl.attachShader(program, fragmentShader)
		gl.linkProgram(program)

		if (!gl.getProgramParameter(program, gl.LINK_STATUS)) {
			console.error(gl.getProgramInfoLog(program))
			gl.deleteProgram(program)
			throw new Error('Failed to link WebGL program')
		}

		return program
	}

	const vertexShader = createShader(gl, gl.VERTEX_SHADER, vertexShaderSource)
	const fragmentShader = createShader(gl, gl.FRAGMENT_SHADER, fragmentShaderSource)

	if (!vertexShader || !fragmentShader) {
		console.error('Failed to create shaders')
		return
	}

	const program = createProgram(gl, vertexShader, fragmentShader)

	if (!program) {
		console.error('Failed to create program')
		return
	}

	const positionAttributeLocation = gl.getAttribLocation(program, 'a_position')
	const resolutionUniformLocation = gl.getUniformLocation(program, 'u_resolution')
	const timeUniformLocation = gl.getUniformLocation(program, 'u_time')
	const rayCountUniformLocation = gl.getUniformLocation(program, 'u_rayCount')
	const rayWidthUniformLocation = gl.getUniformLocation(program, 'u_rayWidth')
	const intensityUniformLocation = gl.getUniformLocation(program, 'u_intensity')
	const angleUniformLocation = gl.getUniformLocation(program, 'u_angle')
	const speedUniformLocation = gl.getUniformLocation(program, 'u_speed')
	const softnessUniformLocation = gl.getUniformLocation(program, 'u_softness')
	const overlapUniformLocation = gl.getUniformLocation(program, 'u_overlap')
	const movementUniformLocation = gl.getUniformLocation(program, 'u_movement')
	const verticalFadeUniformLocation = gl.getUniformLocation(program, 'u_verticalFade')
	const horizontalFadeUniformLocation = gl.getUniformLocation(program, 'u_horizontalFade')
	const widthDisparityUniformLocation = gl.getUniformLocation(program, 'u_widthDisparity')
	const colorsUniformLocation = gl.getUniformLocation(program, 'u_colors')

	const positionBuffer = gl.createBuffer()
	gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer)
	gl.bufferData(gl.ARRAY_BUFFER, new Float32Array([-1, -1, 1, -1, -1, 1, -1, 1, 1, -1, 1, 1]), gl.STATIC_DRAW)

	function resizeCanvas() {
		canvas.width = window.innerWidth
		canvas.height = window.innerHeight
		gl.viewport(0, 0, canvas.width, canvas.height)
	}

	window.addEventListener('resize', resizeCanvas)
	resizeCanvas()

	const colors = config.cssColorVariables.map(varName => fetchColorFromCssVariable(varName))

	function render(time: number) {
		time *= 0.001

		gl.clearColor(0.98, 0.99, 1.0, 1)
		gl.clear(gl.COLOR_BUFFER_BIT)

		gl.useProgram(program)

		gl.enableVertexAttribArray(positionAttributeLocation)
		gl.bindBuffer(gl.ARRAY_BUFFER, positionBuffer)
		gl.vertexAttribPointer(positionAttributeLocation, 2, gl.FLOAT, false, 0, 0)

		gl.uniform2f(resolutionUniformLocation, canvas.width, canvas.height)
		gl.uniform1f(timeUniformLocation, time)
		gl.uniform1f(rayCountUniformLocation, config.rayCount)
		gl.uniform1f(rayWidthUniformLocation, config.rayWidth)
		gl.uniform1f(intensityUniformLocation, config.intensity)
		gl.uniform1f(angleUniformLocation, config.angle)
		gl.uniform1f(speedUniformLocation, config.speed)
		gl.uniform1f(softnessUniformLocation, config.softness)
		gl.uniform1f(overlapUniformLocation, config.overlap)
		gl.uniform1f(movementUniformLocation, config.movement)
		gl.uniform1f(verticalFadeUniformLocation, config.verticalFade)
		gl.uniform1f(horizontalFadeUniformLocation, config.horizontalFade)
		gl.uniform1f(widthDisparityUniformLocation, config.widthDisparity)

		const flatColors = colors.flat()
		gl.uniform3fv(colorsUniformLocation, flatColors)

		gl.drawArrays(gl.TRIANGLES, 0, 6)

		requestAnimationFrame(render)
	}

	requestAnimationFrame(render)
}

function setup() {
	document.getElementById(config.canvas)?.remove()
	const container = document.getElementById('background')
	if (!container) {
		console.log('No background container found')
		return
	}

	const canvas = document.createElement('canvas')
	canvas.style.width = '100%'
	canvas.style.height = '100%'
	canvas.style.position = 'absolute'
	canvas.style.top = '0'
	canvas.style.left = '0'
	canvas.style.zIndex = '-1'
	canvas.style.overflow = 'hidden'
	canvas.style.pointerEvents = 'none'
	canvas.id = config.canvas
	container.appendChild(canvas)

	// @ts-expect-error
	if (!window.isDark?.()) {
		initializeLightRays(canvas)
	}
}

document.addEventListener('theme-changed', setup)

if (document.readyState === 'loading') {
	document.addEventListener('DOMContentLoaded', setup)
} else {
	setup()
}
