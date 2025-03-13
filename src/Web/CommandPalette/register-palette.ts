import { useMagicKeys, whenever } from '@vueuse/core'
import type { Ref } from 'vue'

interface Options {
	value: Ref<boolean>
}

/**
 * Registers `/` and `Cmd+K` hotkeys, as well as a `toggleCommandPalette` function.
 */
export function registerPalette(options: Options) {
	const { Meta_K, Slash } = useMagicKeys({
		target: document.body,
		passive: false,
		onEventFired(e) {
			if (e.key === '/' && e.type === 'keydown') {
				e.preventDefault()
			}
			if (e.key === 'k' && e.type === 'keydown' && e.metaKey) {
				e.preventDefault()
			}
		},
	})

	function toggleCommandPalette() {
		options.value.value = !options.value.value
	}

	// @ts-expect-error window is not typed
	window.toggleCommandPalette = toggleCommandPalette

	window.document.querySelectorAll('[toggle-palette]').forEach((element) => {
		element.addEventListener('click', toggleCommandPalette)
	})

	whenever(Meta_K, () => options.value.value = !options.value.value)
	whenever(Slash, () => options.value.value = true)
}
