<section class="flex flex-col justify-center mb-20 lg:mb-[7vh] px-6 tracking-tighter">
  <div class="items-center grid grid-cols-1 lg:grid-cols-2">
    <!-- Left -->
    <div class="mr-6 p-2 lg:p-0">
      <div class="max-w-xl text-sans">
      <span class="flex flex-col font-semibold text-display text-xl md:text-4xl xl:text-4xl leading-none">
        {{ $heading }}
      </span>
      <p :foreach="$paragraphs as $paragraph" class="mt-3 md:mt-2 xl:mt-4 text-lg xl:text-xl text-(--ui-text-muted)">
        {{ $paragraph }}
      </p>
      </div>
      <div class="mt-6">
          <a :href="$linkUri" class="no-primary rounded-lg font-medium inline-flex items-center focus:outline-hidden disabled:cursor-not-allowed aria-disabled:cursor-not-allowed disabled:opacity-75 aria-disabled:opacity-75 transition-colors px-4 py-2 gap-2 ring ring-inset ring-(--ui-border) text-(--ui-text) bg-(--ui-bg) hover:bg-(--ui-bg-elevated) disabled:bg-(--ui-bg) aria-disabled:bg-(--ui-bg) focus-visible:ring-2 focus-visible:ring-(--ui-border-inverted)">
              <span>{{ $linkLabel }}</span>
              <x-icon name="tabler:arrow-right" class="size-4"/>
          </a>
      </div>
    </div>
    <!-- Right -->
    <div class="flex flex-col gap-2 p-3 rounded-xl text-sm tracking-normal home">
      <div :foreach="$snippets as $snippet">
        {!! $this->codeBlocks[$snippet] !!}
      </div>
    </div>
  </div>
</section>
