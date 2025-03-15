<?php
/** @var \Tempest\Web\Documentation\ChapterView $this */
?>

<x-base :title="$this->currentChapter->title" :copy-code-blocks="true">
  <!-- Main container -->
  <main class="container grow px-4 mx-auto xl:px-8 flex isolate">
    <!-- Sidebar -->
		<div class="hidden lg:block xl:px-6 sticky xl:w-[20rem] max-h-[calc(100dvh-var(--ui-header-height))] overflow-auto top-28 pt-4 shrink-0">
			<!-- Menu -->
			<nav class="flex flex-col gap-y-6 pb-8">
				<div :foreach="$this->categories() as $category" class="flex flex-col">
					<!-- Category title -->
					<span class="font-semibold text-(--ui-text) mb-2">
						<?= ucfirst($category) ?>
					</span>
					<!-- Chapter list -->
					<ul class="flex flex-col border-s border-(--ui-border)">
						<li :foreach="$this->chaptersForCategory($category) as $chapter" class="-ms-px ps-1.5">
							<a :href="$chapter->getUri()" class="group relative w-full px-2.5 py-1.5 flex items-center gap-1.5 text-sm focus:outline-none focus-visible:outline-none hover:text-(--ui-text-highlighted) data-[state=open]:text-(--ui-text-highlighted) transition-colors <?= $this->isCurrent($chapter) ? 'text-(--ui-primary) after:absolute after:-left-1.5 after:inset-y-0.5 after:block after:w-px after:rounded-full after:transition-colors after:bg-(--ui-primary)' : 'text-(--ui-text-muted)' ?>">
								{{ $chapter->title }}
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
    <!-- Main content -->
    <div class="grow px-2 w-full lg:pl-12 flex min-w-0">
			<!-- Documentation page -->
			<article class="grow w-full flex flex-col min-w-0">
				<!-- Header -->
				<x-template :if="$this->currentChapter">
					<div class="relative border-b border-(--ui-border) pb-8">
						<a :href="$this->currentChapter->getUri()" class="text-(--ui-info) font-semibold">
							{{ \Tempest\Support\Str\to_title_case($this->currentChapter->category) }}
						</a>
						<h1 id="top" class="mt-2 font-bold text-4xl text-(--ui-text-highlighted) lg:scroll-mt-[calc(1.5*var(--ui-header-height))]">
							{{ $this->currentChapter->title }}
						</h1>
						<div :if="$this->currentChapter->description" class="text-lg text-(--ui-text-muted) mt-4">
							{!! $this->currentChapter->description !!}
						</div>
					</div>
					<div :if="$this->currentChapter" class="prose prose-large dark:prose-invert mt-8 pb-24 space-y-12" highlights-titles>
						{!! $this->currentChapter->body !!}
					</div>
				</x-template>
				<!-- Docs footer -->
				<nav class="bg-[--card] text-[--card-foreground] font-bold rounded-md p-4 flex justify-between gap-2 my-6">
					<a :if="$this->nextChapter()" :href="$this->nextChapter()?->getUri()" class="underline hover:no-underline">
						Next: {{ $this->nextChapter()?->title }}
					</a>
				</nav>
			</article>
			<!-- On this page -->
			<nav :if="($subChapters = $this->getSubChapters()) !== []" class="w-2xs shrink-0 hidden xl:block sticky max-h-[calc(100dvh-var(--ui-header-height))] overflow-auto top-28 pt-4 pl-12 pr-4">
				<div class="text-sm">
					<span class="inline-block font-bold text-[--primary] mb-3">On this page</span>
					<ul class="flex flex-col">
						<li :foreach="['#top' => $this->currentChapter->title, ...$subChapters] as $url => $title">
							<a :href="$url" :data-on-this-page="$title" class="group relative text-sm flex items-center focus-visible:outline-(--ui-primary) py-1 text-(--ui-text-muted) hover:text-(--ui-text) data-[active]:text-(--ui-primary) transition-colors">
								{{ \Tempest\Support\Str\strip_tags($title) }}
							</a>
						</li>
					</ul>
				</div>
			</nav>
    </div>
  </main>
</x-base>
