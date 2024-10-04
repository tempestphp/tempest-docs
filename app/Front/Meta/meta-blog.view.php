<?php
/** @var \App\Front\Blog\BlogPost $post */
?>

<x-meta-image>
    <div class="flex-1 flex flex-col items-center justify-center gap-8">
        <h1 class="max-w-[75%] leading-[1.2] text-center font-display font-extrabold"><?= $post->title ?></h1>
    </div>
</x-meta-image>