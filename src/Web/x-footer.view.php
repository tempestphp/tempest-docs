<?php

use App\Web\RedirectsController;

use function Tempest\Router\uri;

?>

<footer class="flex justify-center items-center gap-4 mb-8">
    <a :href="uri([RedirectsController::class, 'github'])" class="flex items-center gap-1 text-lg font-medium transition hover:text-(--ui-primary)">
        <x-icon name="tabler:brand-github" class="size-6"/>
    </a>
    <a :href="uri([RedirectsController::class, 'blueskyBrent'])" class="flex items-center gap-1 text-lg font-medium transition hover:text-(--ui-primary)">
        <x-icon name="tabler:brand-bluesky" class="size-6"/>
    </a>
    <a :href="uri([RedirectsController::class, 'twitterBrent'])" class="flex items-center gap-1 text-lg font-medium transition hover:text-(--ui-primary)">
        <x-icon name="tabler:brand-x" class="size-6"/>
    </a>
    <a href="/rss" class="flex items-center gap-1 text-lg font-medium transition hover:text-(--ui-primary)">
        <x-icon name="tabler:rss" class="size-6"/>
    </a>
    <button id="toggle-theme" class="relative size-6 cursor-pointer overflow-hidden transition hover:text-(--ui-primary)">
        <x-icon name="tabler:moon" class="absolute inset-0 dark:opacity-0 size-full dark:translate-y-full duration-200"/>
        <x-icon name="tabler:sun" class="absolute inset-0 opacity-0 dark:opacity-100 size-full -translate-y-full dark:translate-y-0 duration-200"/>
    </button>
</footer>

<script>
    document.getElementById('toggle-theme').addEventListener('click', () => {
        toggleDarkMode()
    })
</script>
