<div class="
    max-w-full md:max-w-[1000px] mx-auto
    md:grid md:grid-cols-12
">
    <div class="md:col-span-3">
        <div class="
            md:sticky md:top-0 md:pt-4 md:px-6 md:text-right
            px-2
        ">
            <h1 class="text-[#4f95d1] font-bold text-2xl">Tempest</h1>

            <div class="
                flex justify-start
                gap-1
                flex-wrap
                text-white
                mt-4

                md:mt-4
                md:grid
                md:gap-1
                md:border-r-2
                md:border-[#4f95d1]
                md:px-4
                md:py-2
                md:justify-end
            ">
                <?php foreach ($this->chapters as $chapter) { ?>
                    <a href="<?= $chapter->getUri() ?>" class="
                        bg-[#4f95d1]
                        px-4 py-2
                        text-sm
                        inline-block
                        rounded

                        md:bg-transparent
                        md:px-0
                        md:py-1
                        md:inline
                        md:text-base
                        <?= $this->isCurrent($chapter) ? 'font-bold text-white md:text-[#4f95d1]' : 'md:text-black' ?>"
                    >
                        <?= $chapter->title ?>
                    </a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="px-2 md:px-6 pt-4 md:col-span-9">
        <?php if ($this->currentChapter) { ?>
            <div class="prose">
                <h1>
                    <?= $this->currentChapter->title ?>
                </h1>
                <?= $this->currentChapter->body ?>
            </div>
        <?php } ?>

        <div class="bg-[#4f95d1] text-white font-bold rounded-md p-4 flex justify-between gap-2 my-6">
            <div>
                Tempest on
                <a href="https://github.com/tempestphp/tempest-framework" class="underline hover:no-underline">GitHub</a>
            </div>
            <?php if ($next = $this->nextChapter()) { ?>
                <a href="<?= $next->getUri() ?>" class="underline hover:no-underline">Next: <?= $next->title ?></a>
            <?php } ?>
        </div>
    </div>
</div>