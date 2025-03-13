<?php
use Tempest\Web\RedirectsController;
use Tempest\Web\Documentation\ChapterController;
use function Tempest\uri;
?>

<x-base :stargazers="$stargazers">
  <div class="flex flex-col grow">
    <!-- Content -->
    <main class="container mx-auto relative flex flex-col grow">
      <!-- Hero -->
      <section class="h-screen flex justify-center flex-col tracking-tighter">
        <h1 class="text-6xl leading-none flex flex-col mb-8">
          <span>Forget about the framework,</span>
          <span class="text-on-background-primary">focus on your business code.</span>
        </h1>
        <p class="max-w-xl text-2xl">
          Tempest embraces modern PHP syntax and covers a wide range of features, giving you all the tools you need to build solid web applications.
        </p>
        <div class="mt-10 flex gap-4 items-center font-medium text-lg">
          <a :href="uri([ChapterController::class, 'index'])" class="border border-button-primary-border bg-button-primary text-on-button-primary rounded-xl px-6 py-2.5 gap-1.5">
            Get started
          </a>
          <a :href="uri([RedirectsController::class, 'github'])" class="group rounded-xl px-6 py-2.5 flex items-center gap-x-1.5">
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
