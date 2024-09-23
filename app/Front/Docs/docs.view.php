<?php /** @var \App\Front\Docs\DocsView $this */ ?>

<x-base :title="$this->currentChapter->title">
  <!-- Banner -->
  <div class="px-4 text-center py-4 bg-[--card] font-bold text-[--card-foreground] w-full z-[99] mb-4 flex items-center gap-2 justify-center">
    <img src="/favicon/favicon-32x32.png" alt="favicon" class="h-[20px] hidden md:inline-block">
    <span>
      Tempest is still a <span class="hl-attribute">work in progress</span>. Visit our <a href="https://github.com/tempestphp/tempest-framework/issues" class="underline hover:no-underline">GitHub</a> or
      <a href="https://discord.gg/pPhpTGUMPQ" class="underline hover:no-underline">Discord</a>
    </span>
  </div>
  <!-- Toggling menu -->
  <script>
    function toggleMenu() {
      const menu = document.getElementById('menu');

      if (menu.classList.contains('hidden')) {
        menu.classList.remove('hidden');
        menu.classList.add('block');
      } else {
        menu.classList.remove('block');
        menu.classList.add('hidden');
      }
    }
  </script>
  <!-- Main container -->
  <main class="docs max-w-full md:max-w-[1500px] mx-auto md:grid md:grid-cols-12">
    <div class="md:col-span-3">
      <div class="
        md:sticky md:h-screen overflow-auto
        md:top-0 md:pt-4 md:px-6 md:text-right
        px-2
      ">
        <!-- Menu toggle button -->
        <div class="md:hidden">
          <button onclick="toggleMenu()" class="flex gap-2 bg-[--background] text-[var(--foreground)]">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
            </svg>
            <span>Menu</span>
          </button>
        </div>
        <!-- Menu -->
        <aside id="menu" class="
          hidden
          gap-4
          flex-wrap
          mt-4
          p-4
          md:bg-transparent
          md:mt-4
          md:grid
          md:gap-4
          md:border-r-2
          md:border-[--border]
          md:px-4
          md:py-2
          md:justify-end
          justify-start
        ">
          <h1 class="text-[--primary] font-bold text-2xl">Tempest</h1>

          <?php foreach ($this->categories() as $category) { ?>
            <div class="flex flex-col">
              <?php if ($category !== 'intro') { ?>
                <h2 class="font-bold text-lg text-[--foreground]">
                  <?= ucfirst($category) ?>
                </h2>
              <?php } ?>

              <?php foreach ($this->chaptersForCategory($category) as $chapter) { ?>
                <a
                  href="<?= $chapter->getUri() ?>"
                  class="
                    menu-link
                    px-4 py-2
                    text-md
                    inline-block
                    rounded
                    hover:text-[--primary] hover:underline
                    text-[--foreground]

                    md:bg-transparent
                    md:px-0
                    md:py-1
                    md:inline
                    md:text-base
                    <?= $this->isCurrent($chapter) ? 'font-bold text-[--primary]' : '' ?>
                    "
                >
                  <?= $chapter->title ?>
                </a>
              <?php } ?>
            </div>
          <?php } ?>
        </aside>
      </div>
    </div>

    <!-- Docs content -->
    <div class="px-4 md:px-6 pt-8 md:col-span-6">
      <?php if ($this->currentChapter) { ?>
        <div class="prose dark:prose-invert">
          <h1>
            <?= $this->currentChapter->title ?>
          </h1>
          <?= $this->currentChapter->body ?>
        </div>
      <?php } ?>

      <!-- Docs footer -->
      <div class="bg-[--card] text-[--card-foreground] font-bold rounded-md p-4 flex justify-between gap-2 my-6">
        <!-- Socials -->
        <div class="flex gap-1">
          <a href="https://github.com/tempestphp/tempest-framework" class="underline hover:no-underline">GitHub</a>
          •
          <a href="https://discord.gg/pPhpTGUMPQ" class="underline hover:no-underline">Discord</a>
        </div>
        <!-- Next chapter link -->
        <a
          :if="$this->nextChapter()"
          href="<?= $this->nextChapter()?->getUri() ?>"
          class="underline hover:no-underline"
        >
          Next: <?= $this->nextChapter()?->title ?>
        </a>
      </div>
    </div>

    <?php if (($subChapters = $this->getSubChapters()) !== []): ?>
    <aside class="col-span-2">
      <div class="
          md:sticky md:h-screen overflow-auto
          md:top-0 md:pt-9 md:pl-12
          pl-4
        ">
        <div class="
            hidden
            md:grid
            md:pl-2
            text-sm
            mr-2 mt-2 mb-4
        ">
            <h2 class="font-bold text-[--primary] mb-3">On this page</h2>
            <?php foreach ($subChapters as $url => $title): ?>
            <a href="<?= $url ?>" class="hover:text-[--primary] hover:underline text--[--foreground] transition mb-1.5">
                <?= $title ?>
            </a>
            <?php endforeach; ?>
        </div>
      </div>
    </aside>
    <?php endif ?>
  </main>
</x-base>
