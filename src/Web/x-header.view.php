<?php
use Tempest\Web\RedirectsController;
use Tempest\Web\Documentation\ChapterController;
use Tempest\Web\Blog\BlogController;
use function Tempest\uri;
?>

<x-component name="x-header">
  <div class="absolute inset-x-0 top-0 pointer-events-none flex items-center justify-center z-[1] pr-(--scrollbar-width)">
    <header
			class="group fixed container rounded-xl -translate-y-2 ring-[transparent] bg-[transparent] pointer-events-auto top-4 ring-1 py-4 px-8 flex items-center justify-between duration-200 data-[scrolling]:translate-y-2 data-[scrolling]:ring-(--ui-border)/90 data-[scrolling]:backdrop-blur data-[scrolling]:bg-(--ui-bg)/75"
			id="header"
		>
      <!-- Left side -->
      <a href="/" class="flex items-center gap-4">
        <!-- Logo -->
        <div class="size-8">
          <img src="/img/logo-transparent.svg" alt="Tempest logo" class="size-full" />
        </div>
        <span class="font-medium">Tempest</span>
      </a>
      <!-- Center -->
      <div class="flex items-center gap-4">
        <x-search />
      </div>
      <!-- Right side -->
      <div class="flex items-center gap-4 font-medium">
        <a
					:href="uri([ChapterController::class, 'index'])"
					:class="'transition hover:text-(--ui-text-highlighted)' . (is_uri([ChapterController::class, '__invoke']) ? 'text-(--ui-primary)' : '')"
				>Documentation</a>
        <a
					:href="uri([BlogController::class, 'index'])"
					:class="'transition hover:text-(--ui-text-highlighted)' . (is_uri([BlogController::class, 'show']) ? 'text-(--ui-primary)' : '')"
				>Blog</a>
				<a href="https://github.com/tempestphp/tempest-framework" class="transition hover:text-(--ui-text-highlighted) flex items-center gap-x-1.5 ml-4">
					<x-icon name="tabler:brand-github" class="size-6" />
					<span class="font-semibold">{{ $stargazers_count }}</span>
				</a>
      </div>
    </header>
  </div>
	<script>
	const header = document.getElementById('header')
	const classesWhenScrolling = 'translate-y-4 ring-header-border backdrop-blur-sm bg-header'
	const classesOnTop = '-translate-y-2 ring-[transparent] bg-[transparent]'

	window.addEventListener('scroll', () => {
		if (window.scrollY > 0) {
			header.dataset.scrolling = true
		} else {
			delete header.dataset.scrolling
		}
	})
	</script>
</x-component>
