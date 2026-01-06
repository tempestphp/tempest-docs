<?php

/** @var \App\Web\Documentation\ChapterView $this */
?>

<x-base
  :copy-code-blocks="true"
  :description="$this->currentChapter->description ?? null"
>
  <x-slot name="head">
    <link :if="$this->currentChapter->version->isCurrent()" rel="canonical" :href="$this->currentChapter->getCanonicalUri()" />
    <meta :if="! $this->currentChapter->version->isCurrent()" name="robots" content="noindex">
  </x-slot>
  <!-- Main container -->
  <main class="flex mx-auto px-4 xl:px-2 container grow">
    <!-- Sidebar -->
    <div id="sidebar" :data-save-scroll="$this->currentChapter->slug . '_sidebar'" class="hidden lg:block top-28 sticky xl:px-6 pt-4 xl:w-[20rem] max-h-[calc(100dvh-var(--ui-header-height))] overflow-auto shrink-0">
      <!-- Menu -->
      <nav class="flex flex-col gap-y-6 pb-8">
        <div :foreach="$this->categories() as $category" class="flex flex-col">
          <!-- Category title -->
          <span class="text-(--ui-text) mb-2">
            {{ \Tempest\Support\Str\to_sentence_case($category) }}
          </span>
          <!-- Chapter list -->
          <ul class="flex flex-col border-s border-(--ui-border)">
            <li :foreach="$this->chaptersForCategory($category) as $chapter" class="-ms-px ps-1.5">
              <a
                :href="$chapter->getUri()"
                <?= $this->isCurrent($chapter) ? 'data-scroll-into-view="sidebar"' : '' ?>
                class="
                  group relative w-full px-2.5 py-1.5 flex items-center gap-1.5 text-sm focus:outline-none focus-visible:outline-none hover:text-(--ui-text-highlighted) data-[state=open]:text-(--ui-text-highlighted) transition-colors
                 <?= $this->isCurrent($chapter)
    ? 'text-(--ui-primary) after:absolute after:-left-1.5 after:inset-y-0.5 after:block after:w-px after:rounded-full after:transition-colors after:bg-(--ui-primary)'
    : 'text-(--ui-text-muted)' ?>
                ">
                {{ $chapter->title }}
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
    <!-- Mobile sidebar button -->
    <button onclick="toggleSideBar(this)" data-sidebar-visible="false" class="group fixed xl:hidden bottom-5 right-5 z-[10] shadow-lg border rounded-xl p-3 border-(--ui-border) bg-(--ui-bg-elevated) text-(--ui-text-muted) hover:text-(--ui-text) focus:text-(--ui-text)! transition flex items-center justify-center">
      <x-icon name="tabler:list-tree" class="group-data-[sidebar-visible=true]:hidden size-6 -translate-x-px" />
      <x-icon name="tabler:x" class="group-data-[sidebar-visible=false]:hidden size-6 -translate-x-px" />
    </button>
    <!-- Mobile sidebar -->
    <div data-sidebar data-visible="false" class="xl:hidden data-[visible=false]:opacity-0 data-[visible=false]:scale-80 data-[visible=false]:pointer-events-none fixed inset-3 lg:max-h-[50vh] lg:right-3 lg:inset-auto lg:bottom-24 border border-(--ui-border) rounded-xl overflow-auto z-[9] bg-(--ui-bg) text-(--ui-text) p-8 starting:opacity-0 starting:scale-90 transition origin-bottom-right">
      <div class="gap-x-2 grid grid-cols-1 min-[375px]:grid-cols-2 lg:grid-cols-1 text-sm sm:text-base">
        <!-- Menu -->
        <nav class="lg:hidden flex flex-col gap-y-8 overflow-hidden">
          <div :foreach="$this->categories() as $category" class="flex flex-col">
            <!-- Category title -->
            <span class="text-(--ui-text) mb-2">
              <?= ucfirst($category) ?>
            </span>
            <!-- Chapter list -->
            <ul class="flex flex-col">
              <li :foreach="$this->chaptersForCategory($category) as $chapter" class="-ms-px ps-1.5">
                <a :href="$chapter->getUri()" class="inline-flex py-1 <?= $this->isCurrent($chapter) ? 'text-(--ui-primary)' : 'text-(--ui-text-muted)' ?>">
                  {{ $chapter->title }}
                </a>
              </li>
            </ul>
          </div>
        </nav>
        <!-- On this page -->
        <div :if="($subChapters = $this->getSubChapters()) !== []" class="hidden min-[375px]:flex flex-col">
          <span class="text-(--ui-text) mb-2 text-right">On this page</span>
          <!-- Sub-chapter list -->
          <ul class="flex flex-col">
            <x-template :foreach="$subChapters as $url => $chapter">
              <li data-heading class="has-[+_[data-item]]:mt-6">
                <a :href="$url" :data-on-this-page="$chapter['title']" onclick="toggleSideBar()" class="text-right flex justify-end group relative focus-visible:outline-(--ui-primary) py-1 text-(--ui-text-muted) hover:text-(--ui-text) data-[active]:text-(--ui-primary) transition-colors">
                  {{ $chapter['title'] }}
                </a>
              </li>
              <li data-item :foreach="$chapter['children'] as $url => $title">
                <a :href="$url" :data-on-this-page="$title" onclick="toggleSideBar()" class="text-right flex justify-end group relative focus-visible:outline-(--ui-primary) py-1 text-(--ui-text-dimmed) hover:text-(--ui-text) data-[active]:text-(--ui-primary) transition-colors">
                  <span class="text-right" style="direction:rtl">{{ $title }}</span>
                </a>
              </li>
            </x-template>
          </ul>
        </div>
      </div>
    </div>
    <!-- Main content -->
    <div class="flex px-2 lg:pl-12 w-full min-w-0 grow">
      <!-- Documentation page -->
      <article class="flex flex-col w-full min-w-0 grow">
        <x-template :if="$this->currentChapter">
          <!-- Header -->
          <div class="relative border-b border-(--ui-border) pb-8">
            <a :href="$this->currentChapter->getUri()" class="text-(--ui-info)">
              {{ \Tempest\Support\Str\to_sentence_case($this->currentChapter->category) }}
            </a>
            <h1 id="top" class="mt-2 font-bold text-4xl text-(--ui-text-highlighted) lg:scroll-mt-[calc(1.5*var(--ui-header-height))]">
              {{ $this->currentChapter->title }}
            </h1>
            <div :if="$this->currentChapter->description" class="text-lg text-(--ui-text-muted) mt-4">
              {!! $this->currentChapter->description !!}
            </div>
          </div>
          <!-- Docs content -->
          <div :if="$this->currentChapter" class="space-y-12 dark:prose-invert mt-8 prose prose-large grow" highlights-titles>
            {!! $this->currentChapter->body !!}
          </div>
          <!-- Docs footer -->
          <nav class="justify-between gap-4 grid grid-cols-2 my-10 not-prose">
            <div>
              <a :if="$this->previousChapter()" :href="$this->previousChapter()?->getUri()" class="p-4 flex items-center gap-x-3 size-full hover:border-(--ui-border-accented) hover:text-(--ui-text) transition rounded-md text-(--ui-text-muted) border border-(--ui-border) bg-(--ui-bg-elevated)">
                <x-icon name="tabler:arrow-left" class="size-5" />
                {{ $this->previousChapter()?->title }}
              </a>
            </div>
            <div>
              <a :if="$this->nextChapter()" :href="$this->nextChapter()?->getUri()" class="p-4 flex items-center gap-x-3 size-full justify-end hover:border-(--ui-border-accented) hover:text-(--ui-text) transition rounded-md text-(--ui-text-muted) border border-(--ui-border) bg-(--ui-bg-elevated)">
                {{ $this->nextChapter()?->title }}
                <x-icon name="tabler:arrow-right" class="size-5" />
              </a>
            </div>
          </nav>
        </x-template>
      </article>
      <!-- On this page -->
      <nav class="hidden top-28 sticky xl:flex flex-col pt-4 pr-4 pl-12 w-xs max-h-[calc(100dvh-var(--ui-header-height))] overflow-auto shrink-0">
        <div class="flex flex-col text-sm grow">
          <x-template :if="($subChapters = $this->getSubChapters()) !== []">
            <span class="inline-block mb-3">On this page</span>
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
          <div class="flex flex-col justify-end gap-y-6 mt-4 grow">
            <!-- Version warning -->
            <div :if="$this->currentChapter->version->isNext()" class="mt-4">
              <div class="text-sm text-(--ui-warning) inline-flex items-baseline gap-x-1.5">
                <x-icon name="tabler:info-circle" class="size-4 translate-y-0.5 shrink-0" />
                <span>This documentation is for an upcoming version of Tempest and is subject to change.</span>
              </div>
            </div>
            <div :elseif="$this->currentChapter->version->isPrevious()" class="mt-4">
              <div class="text-sm text-(--ui-warning) flex flex-col gap-y-2">
                <span class="inline-flex items-baseline gap-x-1.5">
                  <x-icon name="tabler:info-circle" class="size-4 translate-y-0.5 shrink-0" />
                  <div class="flex flex-col gap-y-1.5">
                    <span>This documentation is for a previous version of Tempest.</span>
                    <span>Visit the <a class="decoration-dotted underline underline-offset-4 hover:text-(--ui-text) transition" :href="$this->currentChapter->getCanonicalUri()">{{ $this->currentChapter->version::default()->value }}</a> documentation.</span>
                  </div>
                </span>
              </div>
            </div>
            <!-- Suggest changes -->
            <a class="text-sm text-(--ui-text-dimmed) hover:text-(--ui-text) flex flex-col gap-y-2" :href="$this->currentChapter->getEditPageUri()" target="_blank">
              <span class="inline-flex items-baseline gap-x-1.5">
                <x-icon name="tabler:pencil" class="size-4 translate-y-0.5 shrink-0" />
                <div class="flex flex-col gap-y-1.5">
                  <span>Suggest changes to this page</span>
                </div>
              </span>
            </a>
          </div>
          <div class="flex my-10">
            <a href="#top" class="border border-(--ui-border) bg-(--ui-bg-elevated) text-(--ui-text-muted) hover:text-(--ui-text) transition rounded-lg p-2">
              <x-icon name="tabler:arrow-up" class="size-5" />
            </a>
          </div>
        </div>
      </nav>
    </div>
    <script>
      function toggleSideBar(element) {
        const sidebar = document.querySelector('[data-sidebar]')
        if (sidebar.dataset.visible === 'false') {
          sidebar.dataset.visible = true
          if (element) {
            element.dataset.sidebarVisible = true
          }
        } else {
          sidebar.dataset.visible = false
          if (element) {
            element.dataset.sidebarVisible = false
          }
        }
      }
    </script>
  </main>
</x-base>
