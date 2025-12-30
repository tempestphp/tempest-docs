<?php

use App\Web\Blog\BlogController;
use App\Web\Documentation\DocumentationController;
use App\Web\RedirectsController;

use function Tempest\Router\uri;

?>

<x-base full-title="Tempest, the PHP framework that gets out of your way" :stargazers="$this->stargazers" body-class="bg-[#f1f7ff] dark:bg-[black]">
  <div class="flex flex-col grow">
    <div id="background" class="z-[-1] absolute inset-0 w-full h-full overflow-hidden pointer-events-none" />
    <!-- Content -->
    <main class="container mx-auto relative flex flex-col gap-4 grow -mt-(--ui-header-height)" style="tab-size: 2">
      <!-- Hero -->
      <section class="relative flex flex-col justify-center md:mt-0 px-6 h-screen tracking-tighter">
        <h1 class="flex flex-col text-4xl md:text-5xl xl:text-6xl leading-none">
          <span>The framework that</span>
          <span class="text-(--ui-primary)">gets out of your way.</span>
        </h1>
        <p class="mt-4 md:mt-6 xl:mt-8 max-w-xl text-xl xl:text-2xl text-(--ui-text-toned)">
            Tempest embraces modern PHP and covers a wide range of features, giving you all the tools you need to focus on your code.
        </p>
        <div class="flex items-center gap-x-4 mt-6 md:mt-8 xl:mt-10 font-medium text-lg">
          <a :href="uri([DocumentationController::class, 'index'])" class="bg-(--ui-bg-inverted) text-(--ui-bg) hover:bg-(--ui-bg-inverted)/90 rounded-xl px-6 py-2.5 gap-1.5 transition">
            Get started
          </a>
          <a :href="uri([RedirectsController::class, 'github'])" class="hidden min-[401px]:flex text(--ui-text) hover:bg-(--ui-info)/10 group rounded-xl px-6 py-2.5 items-center gap-x-2 transition">
            <x-icon name="tabler:brand-github" class="size-6" />
            Contribute
            <svg class="opacity-0 group-hover:opacity-100 size-5 scale-85 group-hover:scale-100 transition -translate-x-full group-hover:translate-x-0" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 7l5 5l-5 5m6-10l5 5l-5 5" />
            </svg>
          </a>
        </div>
        <button data-copy="#install-tempest-snippet" class="group relative flex justify-start items-center gap-x-2 mt-6 md:mt-8 xl:mt-10 font-mono text-base cursor-pointer">
          <x-icon name="tabler:terminal" class="size-5 text-(--ui-primary)" />
          <span id="install-tempest-snippet" class="text-(--ui-text-muted) hover:text-(--ui-text) transition">composer create-project tempest/app</span>
          <span class="ml-4 flex items-center justify-center opacity-0 group-hover:opacity-100 scale-80 group-hover:scale-100 transition text-(--ui-text-dimmed) bg-(--ui-bg-muted) rounded border border-(--ui-border)">
              <x-icon name="tabler:copy" class="absolute size-5" />
              <x-icon name="tabler:copy-check-filled" class="size-5 absolute opacity-0 group-[[data-copied]]:opacity-100 transition text-(--ui-success)" />
          </span>
        </button>
        <div class="bottom-0 absolute inset-x-0 flex justify-center items-center mb-12 p-8">
          <button
            id="scroll-indicator"
            class="flex flex-col items-center gap-2 text-(--ui-text-dimmed) hover:text-(--ui-text) transition cursor-pointer focus:text-(--ui-text-highlighted)!"
            onclick="window.scrollBy({ top: window.innerHeight * 0.8, left: 0, behavior: 'smooth' });"
          >
            <span class="animate-pulse">Learn more</span>
            <x-icon name="tabler:arrow-down" class="size-5" />
          </button>
        </div>
      </section>
      <!-- Discovery -->
			<x-home-section
				heading="Zero-config code discovery"
				:paragraphs="[
					'Tempest doesn\'t require hand-holding. Your code is scanned and everything is configured automatically: routes, view components, console commands, event handlers, middleware, migrations — everything.',
				]"
				link-label="Discovery"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'internals', slug: 'discovery')"
				:snippets="['controller', 'view-component', 'event-handler']"
			></x-home-section>
      <!-- Template engine -->
			<x-home-section
				heading="A refreshing new template engine"
				:paragraphs="[
					'Tempest reimagines templating in PHP with a clean front-end engine, inspired by modern front-end frameworks.',
					'Do you prefer something tried and tested? Tempest has built-in support for Blade and Twig as well.',
				]"
				link-label="Templating"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'essentials', slug: 'views')"
				:snippets="['templating-component', 'templating-view']"
			></x-home-section>
      <!-- ORM-->
			<x-home-section
				heading="A truly decoupled ORM"
				:paragraphs="[
					'Models in Tempest embrace modern PHP and are designed to be decoupled from the database; they don\'t even have to persist to the database and can be mapped to any kind of data source.',
				]"
				link-label="ORM"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'essentials', slug: 'database')"
				:snippets="['model', 'orm']"
			></x-home-section>
      <!-- Console-->
			<x-home-section
				heading="Console applications reimagined"
				:paragraphs="[
					'Thinking out of the box, Tempest\'s console component is a brand new approach to building console applications with PHP',
				]"
				link-label="Console"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'essentials', slug: 'console-commands')"
				:snippets="['console']"
			></x-home-section>
      <!-- Much more-->
			<x-home-section
				heading="And much, much more."
				:paragraphs="[
					'Configuration objects for easy autocompletion and injection, data mapping, a powerful dependency container with autowiring. Tempest is designed to be frictionless.',
				]"
				link-label="Get started"
				:link-uri="uri([DocumentationController::class, 'index'])"
				:snippets="['config', 'static-pages', 'markdown-initializer']"
			></x-home-section>
    </main>
  </div>
  <script>
    document.addEventListener('scroll', function() {
      const button = document.getElementById('scroll-indicator');
      if (!button) {
        return;
      }

      const threshold = 100;

      if (window.scrollY > threshold) {
          button.style.opacity = '0';
          button.style.pointerEvents = 'none';
      } else {
          button.style.opacity = '1';
          button.style.pointerEvents = 'auto';
      }
    });
  </script>
</x-base>
