<?php
/** @var \App\Front\Blog\BlogPost $post */
?>

<x-meta-image>
    <div class="flex-1 flex flex-col items-center justify-center gap-8">
        <h1 class="leading-[1.2] text-center font-display font-extrabold"><?= $post->title ?></h1>
    </div>

    <div class="absolute bottom-0 left-0 w-full">
        <div class="flex items-center justify-center pb-16">
            <img class="w-[1.33em] shadow-md rounded-full" src="/tempest-logo.png" alt="Tempest" />
        </div>
    </div>
</x-meta-image>