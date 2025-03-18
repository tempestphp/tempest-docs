<?php
/** @var \App\Web\Blog\BlogPost $post */
use App\Web\Meta\MetaImageController;

use function Tempest\uri;

?>

<x-base :meta-image-uri="uri([MetaImageController::class, 'blog'], slug: $post->slug)" :title="$post->title" :copy-code-blocks="true">
  <!-- Main container -->
  <main class="container px-4 mx-auto xl:px-8 flex flex-col grow isolate items-center relative">
    <!-- Main content -->
    <article class="grow px-2 flex flex-col w-full md:w-auto min-w-0 lg:mt-10 max-w-3xl">
			<!-- Breadcrumbs -->
			<nav class="text-(--ui-text-dimmed) font-medium flex items-center mb-4 text-sm gap-x-1.5">
				<x-icon name="tabler:news" class="size-5 mr-1" />
				<a :href="uri([BlogController::class, 'index'])" class="hover:text-(--ui-text) transition">Blog</a>
				<span>/</span>
				<span class="text-(--ui-primary)">{{ $post->title }}</span>
			</nav>
			<!-- Header -->
			<div class="flex flex-col pb-8 border-b border-(--ui-border) max-w-[65ch]">
				<h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl lg:text-5xl">
				{{ $post->title }}
				</h1>
				<p class="mt-4 text-lg text-gray-500 dark:text-gray-400">
					{{ $post->description }}
				</p>
				<span :if="$post->author" class="text-(--ui-text-muted) text-sm mt-8">
					by <span class="font-medium">{{ $post->author->getName() }}</span> on <span class="font-medium">{{ $post->createdAt->format('F d, Y') }}</span>
				</span>
			</div>
			<!-- Content -->
			<div class="prose dark:prose-invert mt-8 pb-24 space-y-12 w-full">
				{!! $post->content !!}
			</div>
		</article>
	</main>
</x-base>
