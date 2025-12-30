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
    <main class="container mx-auto relative flex flex-col gap-4 grow -mt-(--ui-header-height)">
      <!-- Hero -->
      <section class="relative flex flex-col justify-center md:mt-0 px-6 h-screen">
        <h1 class="flex flex-col text-3xl md:text-4xl lg:text-5xl xl:text-6xl leading-none">
          <span>The framework that</span>
          <span class="text-(--ui-primary)">gets out of your way</span>
        </h1>
        <p class="mt-4 md:mt-6 lg:mt-8 xl:mt-8 max-w-xl xl:max-w-2xl text-xl lg:text-2xl xl:text-3xl text-(--ui-text-toned) leading-snug">
            With zero configuration and zero boilerplate, Tempest gives you the architectural freedom to focus entirely on your business logic.
        </p>
        <div class="flex items-center gap-x-4 mt-6 md:mt-8 lg:mt-10 xl:mt-16 font-medium text-lg">
          <a :href="uri([DocumentationController::class, 'index'])" class="bg-(--ui-bg-inverted) text-(--ui-bg) hover:bg-(--ui-bg-inverted)/90 rounded-xl px-6 py-2.5 gap-1.5 transition">
            Get started
          </a>
          <button onclick="scrollToFeatures()" class="cursor-pointer hidden min-[401px]:flex text(--ui-text) hover:bg-(--ui-info)/10 group rounded-xl px-4 py-2.5 items-center gap-x-2 transition">
            <x-icon name="tabler:info" class="size-6" />
            <span class="transition translate-x-4 group-hover:translate-x-0">Learn more</span>
            <x-icon name="tabler:arrow-down" class="opacity-0 group-hover:opacity-100 size-5 scale-90 group-hover:scale-100 transition -translate-y-full group-hover:translate-y-0" />
          </button>
        </div>
        <button data-copy="#install-tempest-snippet" class="group relative flex justify-start items-center gap-x-2 mt-6 md:mt-8 font-mono text-base cursor-pointer">
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
            onclick="scrollToFeatures()"
          >
            <span class="animate-pulse">Learn more</span>
            <x-icon name="tabler:arrow-down" class="size-5" />
          </button>
        </div>
      </section>
      <!-- Discovery -->
			<x-home-section
				heading="Zero-configuration with code discovery"
        icon="tabler:search"
				:paragraphs="[
					'Tempest scans your code and instantly registers routes, view components, console commands, middleware and more. It doesn’t need hand-holding; it just works.',
				]"
				link-label="Discovery"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'internals', slug: 'discovery')"
				:snippets="['controller', 'view-component', 'event-handler']"
			></x-home-section>
      <!-- Template engine -->
			<x-home-section
				heading="A refreshing new template engine"
        icon="tabler:file-description"
				:paragraphs="[
					'Tempest reimagines templating in PHP with a clean front-end engine, inspired by modern front-end frameworks.',
					'Whether you love our modern syntax or prefer the battle-tested reliability of Blade and Twig, Tempest has you covered.',
				]"
				link-label="Templating"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'essentials', slug: 'views')"
				:snippets="['templating-component', 'templating-view']"
			></x-home-section>
      <!-- ORM-->
			<x-home-section
				heading="A truly decoupled ORM"
        icon="tabler:database"
				:paragraphs="[
					'Models in Tempest embrace modern PHP and are designed to be decoupled from the database; they don’t even have to persist to the database and can be mapped to any kind of data source.',
				]"
				link-label="Database"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'essentials', slug: 'database')"
				:snippets="['model', 'orm']"
			></x-home-section>
      <!-- Console-->
			<x-home-section
				heading="Console applications reimagined"
        icon="tabler:terminal"
				:paragraphs="[
					'Console commands are automatically discovered and use PHP’s type system to define arguments and flags.',
          'No need to search the documentation to remember the syntax, just write PHP.'
				]"
				link-label="Console"
				:link-uri="uri(DocumentationController::class, version: \App\Web\Documentation\Version::default(), category: 'essentials', slug: 'console-commands')"
				:snippets="['console']"
			></x-home-section>
      <!-- Much more-->
			<x-home-section
				heading="And much, much more."
        icon="streamline:tidal-wave"
				:paragraphs="[
					'Configuration objects for easy autocompletion and injection, data mapping, a powerful dependency container with autowiring. Tempest is designed to be frictionless.',
				]"
				link-label="Get started"
				:link-uri="uri([DocumentationController::class, 'index'])"
				:snippets="['config', 'initializer', 'static-pages']"
			></x-home-section>
    </main>
  </div>
  <script>
    function scrollToFeatures() {
      window.scrollBy({ top: window.innerHeight * 0.8, left: 0, behavior: 'smooth' });
    }

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
