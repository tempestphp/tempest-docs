<?php
/** @var \App\Front\Blog\BlogPost[] $posts */
?>

<x-base title="Blog">
  <!-- Main container -->
  <main class="container px-4 mx-auto xl:px-8 flex flex-col grow isolate">
    <!-- Main content -->
    <div class="grow px-2 w-full lg:pl-12 flex flex-col min-w-0 lg:mt-10">
			<!-- Header -->
			<div class="flex flex-col pb-8 max-w-xl">
				<h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl lg:text-5xl">Blog</h1>
				<p class="mt-4 text-lg text-gray-500 dark:text-gray-400">
					Read the latest news and announcements about Tempest, from framework updates to real-world applications and expert insights.
				</p>
				<div class="mt-2">
					<a href="/rss" class="rounded font-semibold inline-flex items-center focus:outline-hidden disabled:cursor-not-allowed aria-disabled:cursor-not-allowed disabled:opacity-75 aria-disabled:opacity-75 transition-colors px-2.5 py-1 text-sm gap-1.5 ring ring-inset ring-(--ui-border-accented) text-(--ui-text) bg-(--ui-bg) hover:bg-(--ui-bg-elevated) disabled:bg-(--ui-bg) aria-disabled:bg-(--ui-bg) focus-visible:ring-2 focus-visible:ring-(--ui-border-inverted)" rel="noopener noreferrer" target="_blank">
						<x-icon name="tabler:rss" class="shrink-0 size-4" />
						RSS
					</a>
				</div>
			</div>
			<!-- Articles -->
			<ul class="grid md:grid-cols-2 lg:grid-cols-3 gap-4 lg:gap-8 mt-0 mb-8 lg:mt-4 2xl:mt-8">
				<li :foreach="$posts as $post" class="flex flex-col justify-between relative border-dashed border border-(--ui-border-accented) hover:bg-(--ui-bg-elevated) rounded-lg p-4 transition">
					<a class="absolute inset-0" :href="$post->uri"></a>
					<div>
						<span class="font-medium">{{ $post->title }}</span>
						<p class="text-[15px] text-(--ui-text-muted) mt-1 line-clamp-2">{{ $post->description }}</p>
					</div>
					<div class="flex items-center mt-6 gap-x-4 justify-between">
						<span
							:if="$post->tag"
							:style="match ($post->tag) {
								'Release' => '--badge: var(--ui-primary)',
								'Thoughts' => '--badge: var(--ui-secondary)',
								'Tutorial' => '--badge: var(--ui-info)',
								default => '--badge: var(--ui-secondary)',
							}"
							class="font-medium inline-flex items-center text-xs px-2 py-1 gap-1 rounded ring ring-inset ring-(--badge)/50 text-(--badge)"
						>
							{{ $post->tag }}
						</span>
						<span :if="$post->author" class="text-(--ui-text-muted) text-sm">
							by <span class="font-medium">{{ $post->author }}</span> on <span class="font-medium">{{ $post->createdAt->format('F d, Y') }}</span>
						</span>
					</div>
				</li>
			</ul>
		</div>
  </main>
</x-base>
