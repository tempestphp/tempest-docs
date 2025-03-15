<?php
use Tempest\Web\RedirectsController;
use Tempest\Web\Documentation\ChapterController;
use Tempest\Web\Blog\BlogController;
use function Tempest\uri;
?>

<x-base full-title="Tempest, the PHP framework that gets out of your way" :stargazers="$stargazers" body-class="bg-[#f1f7ff]">
  <div class="flex flex-col grow font-display">
    <!-- Falling leaves -->
    <x-falling-leaves class="dark:hidden" />
    <!-- Aurora -->
    <x-aurora class="dark:hidden" />
    <!-- Rain -->
    <x-rain />
    <!-- Moonlight -->
    <x-moonlight />
    <!-- Content -->
    <main class="container mx-auto relative flex flex-col grow -mt-(--ui-header-height)">
      <!-- Hero -->
      <section class="h-screen flex justify-center flex-col tracking-tighter px-6">
        <h1 class="text-4xl md:text-5xl xl:text-6xl leading-none flex flex-col">
          <span>Forget about the framework,</span>
          <span class="text-(--ui-primary)">focus on your business code.</span>
        </h1>
        <p class="mt-4 md:mt-6 xl:mt-8 max-w-xl text-xl xl:text-2xl text-(--ui-text-toned)">
          Tempest embraces modern PHP syntax and covers a wide range of features, giving you all the tools you need to build solid web applications.
        </p>
        <div class="mt-6 md:mt-8 xl:mt-10 flex gap-4 items-center font-medium text-lg">
          <a :href="uri([ChapterController::class, 'index'])" class="bg-(--ui-bg-inverted) text-(--ui-bg) hover:bg-(--ui-bg-inverted)/90 rounded-xl px-6 py-2.5 gap-1.5 transition">
            Get started
          </a>
          <a :href="uri([RedirectsController::class, 'github'])" class="text(--ui-text) hover:bg-(--ui-info)/10 group rounded-xl px-6 py-2.5 flex items-center gap-x-1.5 transition">
            <x-icon name="tabler:brand-github" class="size-6" />
            Source code
            <svg class="group-hover:translate-x-0 size-5 scale-85 group-hover:scale-100 transition opacity-0 group-hover:opacity-100 -translate-x-full" xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 24 24">
              <path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m7 7l5 5l-5 5m6-10l5 5l-5 5" />
						</svg>
          </a>
        </div>
      </section>
    </main>
  </div>
</x-base>
