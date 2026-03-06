<?php
/** @var \App\Web\Blog\BlogPost $post */
use App\Web\Blog\BlogController;
?>

<x-base title="Hello!">
    <!-- Main container -->
    <main class="isolate relative flex flex-col items-center mx-auto px-4 xl:px-8 container grow">
        <!-- Main content -->
        <article class="flex flex-col lg:mt-10 px-2 w-full md:w-auto min-w-0 max-w-3xl grow">
            <!-- Header -->
            <div class="flex flex-col pb-6 w-full">
<!--                <h1 class="max-w-[65ch] font-bold text-3xl sm:text-4xl lg:text-5xl tracking-tight">-->
<!--                    {{ $post->title }}-->
<!--                </h1>-->
<!--                <p class="mt-4 text-lg text-(--ui-text-muted)">-->
<!--                    {{ $post->description }}-->
<!--                </p>-->
            </div>
            <!-- Content -->
            <div class="space-y-12 dark:prose-invert mt-8 pb-24 w-full prose">
                You scanned the QR code, didn't you?
            </div>
        </article>
    </main>
</x-base>
