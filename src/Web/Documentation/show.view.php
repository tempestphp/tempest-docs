<?php
/** @var \Tempest\Web\Documentation\ChapterView $this */
?>

<x-base :title="$this->currentChapter->title">
  <!-- Main container -->
  <main class="docs container mx-auto md:grid md:grid-cols-12 mt-28 relative">
	<div class="sticky top-28">
	owo
	</div>
		<!-- Sidebar -->
    <div class="md:col-span-3">
      <div class="md:sticky md:h-screen overflow-auto md:top-0 md:pt-4 md:px-6 px-2">
        <!-- Menu -->
        <aside id="menu" class="hidden gap-4 flex-wrap mt-4 p-4 md:bg-transparent md:mt-4 md:grid md:gap-4 md:px-4 md:py-2 justify-start">
          <div :foreach="$this->categories() as $category" class="flex flex-col">
            <h2 :if="$category !== 'intro'" class="font-bold text-lg text-[--foreground]">
              <?= ucfirst($category) ?>
            </h2>

            <x-template :foreach="$this->chaptersForCategory($category) as $chapter">
              <a :href="$chapter->getUri()" class="menu-link px-4 py-2 text-md inline-block rounded hover:text-on-background-primary hover:underline hover:underline-offset-6 hover:decoration-dashed text-on-foreground md:bg-transparent md:px-0 md:py-1 md:inline md:text-base <?= $this->isCurrent($chapter) ? 'font-bold text-on-background-primary' : '' ?>">
                {{ $chapter->title }}
              </a>
            </x-template>
          </div>
        </aside>
      </div>
    </div>

    <!-- Docs content -->
    <div class="px-4 md:px-6 pt-8 md:col-span-6">
      <div :if="$this->currentChapter" class="prose dark:prose-invert">
        <h1>
          {{ $this->currentChapter->title }}
        </h1>
        {!! $this->currentChapter->body !!}
      </div>

      <!-- Docs footer -->
      <div class="bg-[--card] text-[--card-foreground] font-bold rounded-md p-4 flex justify-between gap-2 my-6">
        <!-- Socials -->
        <div class="flex gap-1">
          <a href="https://github.com/tempestphp/tempest-framework" class="underline hover:no-underline">GitHub</a>
          •
          <a href="https://discord.gg/pPhpTGUMPQ" class="underline hover:no-underline">Discord</a>
        </div>
        <!-- Next chapter link -->
        <a :if="$this->nextChapter()" :href="$this->nextChapter()?->getUri()" class="underline hover:no-underline">
          Next: {{ $this->nextChapter()?->title }}
        </a>
      </div>
    </div>

		<!-- On this page -->
    <aside :if="($subChapters = $this->getSubChapters()) !== []" class="col-span-2">
      <div class="md:sticky md:h-screen overflow-auto md:top-0 md:pt-9 md:pl-12 pl-4">
        <div class="hidden md:grid md:pl-2 text-sm mr-2 mt-2 mb-4">
          <h2 class="font-bold text-[--primary] mb-3">On this page</h2>
          <x-template :foreach="$subChapters as $url => $title">
            <a :href="$url" class="hover:text-[--primary] hover:underline text--[--foreground] transition mb-1.5">
              {{ $title }}
            </a>
          </x-template>
        </div>
      </div>
    </aside>
  </main>
</x-base>
