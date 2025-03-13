<?php
use Tempest\Web\RedirectsController;
use function Tempest\uri;
?>

<x-component name="x-footer">
  <footer class="flex items-center justify-center mb-8 gap-4 pr-(--scrollbar-width)">
    <a :href="uri([RedirectsController::class, 'github'])" class="flex items-center gap-1 text-lg font-medium transition hover:text-(--ui-primary)">
      <x-icon name="tabler:brand-github" class="size-6" />
    </a>
    <a :href="uri([RedirectsController::class, 'bluesky'])" class="flex items-center gap-1 text-lg font-medium transition hover:text-(--ui-primary)">
      <x-icon name="tabler:brand-bluesky" class="size-6" />
    </a>
    <a :href="uri([RedirectsController::class, 'twitter'])" class="flex items-center gap-1 text-lg font-medium transition hover:text-(--ui-primary)">
      <x-icon name="tabler:brand-x" class="size-6" />
    </a>
		<button id="toggle-theme" class="relative size-6 cursor-pointer overflow-hidden transition hover:text-(--ui-primary)">
			<x-icon name="tabler:moon" class="absolute inset-0 size-full dark:opacity-0 dark:translate-y-full duration-200" />
			<x-icon name="tabler:sun" class="absolute inset-0 size-full -translate-y-full opacity-0 dark:opacity-100 dark:translate-y-0 duration-200" />
		</button>
  </footer>

	<script>
	document.getElementById('toggle-theme').addEventListener('click', () => {
		applyTheme(localStorage.theme === 'dark' ? 'light' : 'dark')
	})
	</script>
</x-component>
