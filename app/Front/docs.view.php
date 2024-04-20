<?php
/** @var \App\Front\DocsView $this */
?>

<div class="
    max-w-full md:max-w-[1000px] mx-auto
    md:grid md:grid-cols-12
">
    <div class="md:col-span-3">
        <div class="
            md:sticky md:top-0 md:pt-4 md:px-6 md:text-right
            px-2
        ">
            <div class="
                flex
                gap-4
                flex-wrap
                mt-4

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

                <?php foreach (['intro', 'console', 'web', 'highlight'] as $category) { ?>
                    <div class="flex flex-col">
                        <?php if($category !== 'intro') { ?>
                            <h2 class="font-bold text-lg">
                                <?= ucfirst($category) ?>
                            </h2>
                        <?php } ?>

                        <?php foreach ($this->chaptersForCategory($category) as $chapter) { ?>
                            <a href="<?= $chapter->getUri() ?>" class="
                                bg-[#4f95d1]
                                px-4 py-2
                                text-sm
                                font-normal
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
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="px-2 md:px-6 pt-8 md:col-span-9">
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