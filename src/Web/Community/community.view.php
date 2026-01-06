<x-base title="Community">
  <main class="isolate flex flex-col mx-auto px-4 xl:px-8 container grow">
    <!-- Main content -->
    <div class="flex flex-col lg:mt-10 px-2 lg:pl-12 w-full min-w-0 grow">
      <!-- Header -->
      <div class="flex flex-col pb-8 max-w-2xl">
        <h1 class="font-bold text-gray-900 dark:text-white text-3xl sm:text-4xl lg:text-5xl tracking-tight">Community</h1>
        <p class="mt-4 text-gray-500 dark:text-gray-400 text-lg">
            Find out what's happening in the Tempest community: packages, videos, blog posts, and more. Do you want to feature your own content on this page? Let us know on <a href="/discord" class="underline">Discord</a>!
        </p>
      </div>
      <!-- Articles -->
      <ul class="gap-4 lg:gap-6 grid md:grid-cols-2 lg:grid-cols-3 mt-0 lg:mt-4 2xl:mt-8 mb-8">
        <li :foreach="$communityPosts as $post" class="p-0.5 relative border border-(--ui-border) bg-(--ui-bg-elevated)/30 hover:bg-(--ui-bg-elevated)/75 rounded-lg transition">
          <div class="h-full flex flex-col justify-between border border-dashed border-(--ui-border) p-4 rounded-md">
            <a class="absolute inset-0" :href="$post->uri"></a>
            <div>
              <span class="font-medium">{{ $post->title }}</span>
              <p class="text-(--ui-text-dimmed) mt-1 line-clamp-2">{{ $post->description }}</p>
            </div>
            <div class="flex items-center gap-x-2 mt-4">
              <span
                :if="$post->type"
                :class="$post->type->getStyle()"
                class="inline-flex items-center gap-1 px-2 py-1 rounded font-medium text-xs uppercase"
              >
                {{ $post->type->value }}
              </span>
              <span
                :if="$post->wip"
                class="inline-flex items-center gap-1 bg-gray-300/20 dark:bg-gray-400/10 px-2 py-1 rounded font-medium text-gray-700 dark:text-gray-400 text-xs uppercase"
              >
                Work in progress
              </span>
            </div>
          </div>
        </li>
      </ul>
    </div>
  </main>
</x-base>
