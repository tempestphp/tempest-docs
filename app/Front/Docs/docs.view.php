<?php /** @var \App\Front\Docs\DocsView $this */ ?>

<x-base :title="$this->currentChapter->title">

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
    
    <div class="
    max-w-full md:max-w-[1000px] mx-auto
    md:grid md:grid-cols-12
">
        <div class="md:col-span-3">
            <div class="
            md:top-0 md:pt-4 md:px-6 md:text-right
            px-2
        ">
                <div class="md:hidden">
                    <button onclick="toggleMenu()" class="flex gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 5.25h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5m-16.5 4.5h16.5" />
                        </svg>
                        <span>Menu</span>
                    </button>
                </div>

                <div id="menu" class="
                hidden
                gap-4
                flex-wrap
                mt-4
                bg-blue-50
                p-4

                md:bg-transparent
                md:mt-4
                md:grid
                md:gap-4
                md:border-r-2
                md:border-[#4f95d1]
                md:px-4
                md:py-2
                md:justify-end
                justify-start
            ">
                    <h1 class="text-[#4f95d1] font-bold text-2xl">Tempest</h1>

                    <?php foreach ($this->categories() as $category) { ?>
                        <div class="flex flex-col">
                            <?php if ($category !== 'intro') { ?>
                                <h2 class="font-bold text-lg">
                                    <?= ucfirst($category) ?>
                                </h2>
                            <?php } ?>

                            <?php foreach ($this->chaptersForCategory($category) as $chapter) { ?>
                                <a href="<?= $chapter->getUri() ?>" class="
                                menu-link
                                px-4 py-2
                                text-md
                                inline-block
                                rounded
                                hover:text-[#4f95d1] hover:underline

                                md:bg-transparent
                                md:px-0
                                md:py-1
                                md:inline
                                md:text-base
                                <?= $this->isCurrent($chapter) ? 'font-bold text-[#4f95d1]' : '' ?>
                            "
                                >
                                    <?= $chapter->title ?>
                                </a>

                                <?php
                                    if($this->isCurrent($chapter) && ($subChapters = $this->getSubChapters()) !== []):
                                ?>
                                <div class="
                                    hidden
                                    md:grid
                                    md:border-r
                                    md:border-[#4f95d1]
                                    mr-4
                                    md:pr-2
                                    text-sm
                                    gap-2
                                    mt-2
                                    mb-4
                                    pt-2
                                    pb-2
                                ">
                                    <?php foreach ($subChapters as $url => $title): ?>
                                        <a href="<?= $url ?>" class="hover:text-[#4f95d1] hover:underline">
                                            <?= $title ?>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif ?>
                            <?php } ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>

        <div class="px-4 md:px-6 pt-8 md:col-span-9">
            <?php if ($this->currentChapter) { ?>
                <div class="prose">
                    <h1>
                        <?= $this->currentChapter->title ?>
                    </h1>
                    <?= $this->currentChapter->body ?>
                </div>
            <?php } ?>

            <div class="bg-[#4f95d1] text-white font-bold rounded-md p-4 flex justify-between gap-2 my-6">
                <div class="flex gap-1">
                    <a href="https://github.com/tempestphp/tempest-framework" class="underline hover:no-underline">GitHub</a>
                    •
                    <a href="https://discord.gg/pPhpTGUMPQ" class="underline hover:no-underline">Discord</a>
                </div>

                <a
                        :if="$this->nextChapter()"
                        href="<?= $this->nextChapter()?->getUri() ?>"
                        class="underline hover:no-underline"
                >
                    Next: <?= $this->nextChapter()->title ?>
                </a>
            </div>
        </div>
    </div>
</x-base>