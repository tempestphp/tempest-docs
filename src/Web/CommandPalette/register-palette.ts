import { useActiveElement, useMagicKeys, whenever } from '@vueuse/core'
import { logicAnd, logicOr } from '@vueuse/math'
import { computed, type Ref } from 'vue'

interface Options {
	value: Ref<boolean>
}

/**
 * Registers `/` and `Cmd+K`/`Ctrl+K` hotkeys, as well as a `toggleCommandPalette` function.
 */
export function registerPalette(options: Options) {
	const { Meta_K, Ctrl_K, Slash } = useMagicKeys({
		target: document.body,
		passive: false,
		onEventFired(e) {
			if (['INPUT', 'TEXTAREA'].includes((e.target as Element).tagName ?? '')) {
				return
			}

			if (e.key === '/' && e.type === 'keydown') {
        e.preventDefault()
			}

			if (e.key === 'k' && e.type === 'keydown' && e.metaKey /* && OS is MacOS */) {
				e.preventDefault()
      }

      if (e.key === 'k' && e.type === 'keydown' && e.ctrlKey /* && OS is not MacOS */) {
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

	const activeElement = useActiveElement({ triggerOnRemoval: true })
  const notUsingInput = computed(() => !['INPUT', 'TEXTAREA'].includes(activeElement.value?.tagName ?? ''))
  // https://developer.mozilla.org/en-US/docs/Web/API/Navigator/platform#determining_the_modifier_key_for_the_users_platform
  const isMacKbdLayout = navigator.platform.toLowerCase().startsWith('mac')

  whenever(
    logicOr(
      logicAnd(Meta_K, notUsingInput, isMacKbdLayout),
      logicAnd(Ctrl_K, notUsingInput, !isMacKbdLayout)
    ),
    () => options.value.value = !options.value.value
  )
	whenever(logicAnd(Slash, notUsingInput), () => options.value.value = true)
}
