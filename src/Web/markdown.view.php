<x-base :copy-code-blocks="true" :title="$post->title">
    <main class="container grow px-4 mx-auto xl:px-8 flex pb-16">
        <!-- Main content -->
        <div class="grow px-2 w-full lg:pl-12 flex min-w-0">
            <!-- Documentation page -->
            <article class="grow w-full flex flex-col min-w-0">
                <x-template>
                    <!-- Header -->
                    <div class="relative border-b border-(--ui-border) pb-8">
                        <h1 :if="$post->title" id="top" class="mt-2 font-bold text-4xl text-(--ui-text-highlighted) lg:scroll-mt-[calc(1.5*var(--ui-header-height))]">
                            {!! $post->title !!}
                        </h1>
                        <div :if="$post->description" class="text-lg text-(--ui-text-muted) mt-4">
                            {!! $post->description !!}
                        </div>
                    </div>
                    <!-- Docs content -->
                    <div class="prose prose-large dark:prose-invert mt-8 space-y-12" highlights-titles>
                        {!! $post->content !!}
                    </div>
                </x-template>
            </article>

            <!-- On this page -->
            <nav class="w-2xs shrink-0 hidden xl:flex flex-col sticky max-h-[calc(100dvh-var(--ui-header-height))] overflow-auto top-28 pt-4 pl-12 pr-4">
                <div class="text-sm flex flex-col grow">
                    <x-template :if="($subChapters = $post->getSubChapters()) !== []">
                        <span class="inline-block font-bold text-[--primary] mb-3">On this page</span>
                        <ul class="flex flex-col">
                            <x-template :foreach="$subChapters as $url => $chapter">
                                <li>
                                    <a :href="$url" :data-on-this-page="$chapter['title']" class="group relative text-sm flex items-center focus-visible:outline-(--ui-primary) py-1 text-(--ui-text-muted) hover:text-(--ui-text) data-[active]:text-(--ui-primary) transition-colors">
                                        {{ $chapter['title'] }}
                                    </a>
                                </li>
                                <li :foreach="$chapter['children'] as $url => $title">
                                    <a :href="$url" :data-on-this-page="$title" class="pl-4 group relative text-sm flex items-center focus-visible:outline-(--ui-primary) py-1 text-(--ui-text-dimmed) hover:text-(--ui-text) data-[active]:text-(--ui-primary) transition-colors">
                                        <span>{{ $title }}</span>
                                    </a>
                                </li>
                            </x-template>
                        </ul>
                    </x-template>
                    <div class="my-10 flex">
                        <a href="#top" class="border border-(--ui-border) bg-(--ui-bg-elevated) text-(--ui-text-muted) hover:text-(--ui-text) transition rounded-lg p-2">
                            <x-icon name="tabler:arrow-up" class="size-5" />
                        </a>
                    </div>
                </div>
            </nav>
        </div>
    </main>
</x-base>