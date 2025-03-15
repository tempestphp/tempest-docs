<?php
use Tempest\Web\RedirectsController;
use Tempest\Web\Documentation\ChapterController;
use Tempest\Web\Blog\BlogController;
use function Tempest\uri;
?>

<x-component name="x-header">
	<!-- Header -->
  <div class="flex flex-col inset-x-0 items-center lg:justify-center z-[1] pr-(--scrollbar-width) h-(--ui-header-height)">
    <header
			class="group w-full lg:max-w-5xl xl:max-w-7xl 2xl:max-w-8xl fixed lg:rounded-xl lg:-translate-y-2 ring-[transparent] bg-[transparent] ring-1 py-4 px-8 flex items-center justify-between duration-200 lg:data-[scrolling]:translate-y-2 data-[scrolling]:ring-(--ui-border)/90 data-[scrolling]:backdrop-blur data-[scrolling]:bg-(--ui-bg)/75"
			id="header"
		>
      <!-- Left side -->
      <a href="/" class="flex items-center gap-4">
        <!-- Logo -->
        <div class="size-8">
          <img src="/img/logo-transparent.svg" alt="Tempest logo" class="size-full" />
        </div>
        <span class="font-medium hidden lg:inline">Tempest</span>
      </a>
      <!-- Center -->
      <div class="flex items-center gap-4">
        <x-search />
      </div>
      <!-- Right side -->
      <div class="flex items-center gap-4 font-medium">
        <a
					:href="uri([ChapterController::class, 'index'])"
					class="transition hover:text-(--ui-text-highlighted) <?= is_uri([ChapterController::class, '__invoke']) ? 'md:text-(--ui-primary)' : '' ?>"
				>Documentation</a>
        <a
					:href="uri([BlogController::class, 'index'])"
					class="transition hover:text-(--ui-text-highlighted) <?= is_uri([BlogController::class, 'show']) || is_uri([BlogController::class, 'index']) ? 'md:text-(--ui-primary)' : '' ?>"
				>Blog</a>
				<a href="https://github.com/tempestphp/tempest-framework" class="transition hover:text-(--ui-text-highlighted) flex items-center gap-x-1.5 ml-4">
					<x-icon name="tabler:brand-github" class="size-6" />
					<span class="font-semibold hidden lg:inline">{{ $stargazers_count }}</span>
				</a>
      </div>
    </header>
  </div>
	<script>
	const header = document.getElementById('header')
	window.addEventListener('scroll', () => {
		if (window.scrollY > 0) {
			header.dataset.scrolling = true
		} else {
			delete header.dataset.scrolling
		}
	})
	</script>
</x-component>
