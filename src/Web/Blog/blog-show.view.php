<?php
/** @var \App\Front\Blog\BlogPost $post */
use App\Front\Meta\MetaImageController;
use function Tempest\uri;
?>

<x-base meta-image-uri="<?= uri([MetaImageController::class, 'blog'], slug: $post->slug) ?>" :title="$post->title">
  <div class="@HeroBlock">
    <x-header />

    <div class="flex flex-col gap-4 px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
      <div class=" flex-1 flex flex-col items-center justify-center gap-8">
        <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold"><?= $post->title ?></h1>
      </div>
    </div>
  </div>

  <div class="blog prose max-w-screen-md mx-auto py-8 md:py-16 md:px-24 px-2">
    <p class="text-sm">
      Written by <?= $post->author ?> on <?= $post->createdAt->format('F d, Y') ?> — <a href="/blog">Back</a>
    </p>
    <?= $post->content ?>
  </div>

  <x-footer />
</x-base>
