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
        <div class="w-full z-10 md:py-24 pt-12">
            <div class="w-full max-w-screen-md mx-auto grid gap-12 md:px-24 px-8">
                <?php foreach ($posts as $post): ?>
                    <a href="<?= $post->getUri() ?>" class="grid gap-2 hover:bg-tempest-blue-500 hover:underline rounded-lg hover:text-white p-4">
                        <span class="text-2xl font-bold flex items-baseline gap-2">
                            <?= $post->title ?>
                        </span>
                        <?php if ($post->description ?? null): ?>
                            <span class="text-base">
                                <?= $post->description ?>
                            </span>
                        <?php endif ?>

                        
                        
                        <span class="text-sm font-light">
                            <?php if ($post->author ?? null): ?>
                                Written by <?= $post->author ?> on
                            <?php endif ?>

                            
                            
                            <?= $post->createdAt->format('F d, Y') ?>
                        </span>
                    </a>

                    <hr class="w-3/5 mx-auto">
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <x-footer/>
</x-base>
