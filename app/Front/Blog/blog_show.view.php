<?php

/** @var \App\Front\Blog\BlogPost $post */

?>
<x-base>
    <div class="@HeroBlock">
        <x-header />

        <div class="flex flex-col gap-4 min-h-[512px] px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
            <div class=" flex-1 flex flex-col items-center justify-center gap-8">
                <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold"><?= $post->title ?></h1>
            </div>

            <div class="flex w-full gap-4 items-center text-[13px] justify-end text-white/60 mt-auto mb-0">
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                        <circle cx="12" cy="13" r="3" />
                    </svg>
                    <span>Photo by <a href="https://unsplash.com/@lc_photography" class="underline" target="_blank" rel="noreferrer noopener">Leon Contreras</a>
                    </span>
            </div>
        </div>
    </div>

    <div class="prose dark:prose-invert max-w-screen-md mx-auto py-24 px-24">
        <?= $post->content ?>
    </div>
</x-base>
