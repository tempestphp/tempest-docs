<?php
/** @var \App\Web\Blog\BlogPost $post */
?>

<x-base
  :meta-image-uri="$post->metaImageUri"
  :title="$post->title"
  :description="$post->description"
  :copy-code-blocks="true"
  :meta="$post->meta"
>
  <!-- Main container -->
  <main class="isolate relative flex flex-col items-center mx-auto px-4 xl:px-8 font-mono container grow">
    <!-- Main content -->
    <article class="flex flex-col lg:mt-10 px-2 w-full md:w-auto min-w-0 max-w-3xl grow">
			<!-- Breadcrumbs -->
			<nav class="text-(--ui-text-dimmed) font-medium flex items-center mb-6 text-sm gap-x-1.5">
				<x-icon name="tabler:news" class="mr-1 size-5" />
				<a :href="uri([BlogController::class, 'index'])" class="hover:text-(--ui-text) transition">Blog</a>
				<span>/</span>
				<span class="text-(--ui-primary)">{{ $post->title }}</span>
			</nav>
			<!-- Header -->
			<div class="flex flex-col pb-8 border-b border-(--ui-border) w-full">
				<h1 class="max-w-[65ch] font-bold text-3xl sm:text-4xl lg:text-5xl tracking-tight">
				{{ $post->title }}
				</h1>
				<p class="mt-4 text-lg text-(--ui-text-muted)">
					{{ $post->description }}
				</p>
				<span :if="$post->author" class="text-(--ui-text-dimmed) text-sm mt-4">
					by <span class="font-medium">{{ $post->author->getName() }}</span> on <span class="font-medium">{{ $post->createdAt->format('F d, Y') }}</span>
				</span>
			</div>
			<!-- Content -->
			<div class="space-y-12 dark:prose-invert mt-8 pb-24 w-full prose">
				{!! $post->content !!}
			</div>
		</article>
	</main>
</x-base>
