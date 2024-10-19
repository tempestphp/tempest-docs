<?php

/** @var \App\Front\Blog\BlogPost[] $posts */

?>
<x-base>
    <div class="@HeroBlock">
        <x-header/>

        <div class="flex flex-col gap-4 px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
            <div class=" flex-1 flex flex-col items-center justify-center gap-8">
                <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold">Tempest Blog</h1>
            </div>
        </div>
    </div>


    <div class="w-full flex flex-col ">
        <div class="w-full z-10 py-24">
            <div class="w-full max-w-screen-md mx-auto grid gap-4 px-24">
                <?php foreach ($posts as $post): ?>
                    <a href="<?= $post->getUri() ?>" class="grid gap-2">
                        <span class="text-2xl font-bold">
                            <?= $post->title ?>
                        </span>
                        <?php if ($post->description ?? null): ?>
                            <span>
                                <?= $post->description ?>
                            </span>
                        <?php endif ?>
                        <span class="flex">
                            <span class="bg-tempest-blue-500 px-2 py-1 text-sm font-bold text-white rounded">
                                <?= $post->createdAt->format('Y-m-d') ?>
                            </span>
                        </span>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <x-footer />
</x-base>
