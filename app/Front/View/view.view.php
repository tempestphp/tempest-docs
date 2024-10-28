<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;

?>

<x-base title="View" :meta="\App\Front\Meta\MetaType::VIEW">
    <div class="@HeroBlock">
        <x-header/>

        <div class="flex flex-col gap-4 min-h-[512px] px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
            <div class=" flex-1 flex flex-col items-center justify-center gap-8">
                <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold">The PHP templating engine that speaks your language</h1>

                <div class="flex gap-3 my-8 flex-col text-center items-center ">
                    <div class="bg-tempest-blue-950/[90%] px-4 py-2 rounded-[9px] text-white border border-tempest-blue-500 font-medium font-mono text-[16px]">
                        <span class="text-tempest-blue-100 font-normal">composer</span> require tempest/view:v1.0.0-alpha.2
                    </div>
                </div>

                <div class="flex sm:flex-row flex-col gap-2">
                    <x-button uri="<?= uri(DocsController::class, category: 'framework', slug: 'views') ?>">Read the docs</x-button>
                    <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
                </div>

            </div>
        </div>
    </div>


    <div class="w-full flex flex-col ">
        <div class="w-full -mt-20 z-10 pb-24">
            <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">
                <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4 pt-24">
                    <x-feature-header>
                        <x-slot name="icon">!</x-slot>
                        Designed to feel familiar
                    </x-feature-header>
                    <p class="font-light">
                        Tempest View is an extension on the most popular templating engine of all time: HTML. Template inheritance and inclusion are modelled via HTML elements; data binding and control structures are handled with attributes.
                    </p>
                </div>

                <x-codeblock-home>
                    <?= $this->code1 ?>
                </x-codeblock-home>
            </div>
        </div>
    </div>

    <div class="w-full bg-zinc-50 slope-2 py-24">
        <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">
            <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                <x-feature-header>
                    The Tempest Touch
                </x-feature-header>
                <p class="font-light">
                    Tempest's discovery is built-in — that's a given. Just create a
                    <code>.view.php</code> file, and Tempest takes care of the rest. No config needed, your components are available everywhere.
                </p>
            </div>

            <x-codeblock-home>
                <?= $this->code2 ?>
            </x-codeblock-home>
        </div>
    </div>

    <div class="w-full  py-24">
        <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">
            <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                <x-feature-header>
                    The perfect blend
                </x-feature-header>
                <p class="font-light">
                    Just in case you need that little extra, PHP is there to help you out.
                </p>
            </div>

            <x-codeblock-home>
                <?= $this->code3 ?>
            </x-codeblock-home>
        </div>
    </div>

    <div class="w-full bg-tempest-blue-500 py-24 slope-2 px-4">
        <blockquote class="max-w-2xl w-full mx-auto text-[1.25rem] text-white text-center">
            <h2 class="text-2xl font-bold font-display leading-tight">We need help!</h2>

            <p class="mt-4">We would like to have proper IDE support for Tempest View before tagging version 1.0. If you're familiar with implementing LSPs or building IntelliJ plugins, feel free to contact us via <a class="underline hover:no-underline" href="https://tempestphp.com/discord">Discord</a> or <a class="underline hover:no-underline" href="mailto:brendt@stitcher.io">email</a> to discuss the details.</p>
        </blockquote>
    </div>

    <div class="w-full slope-3 py-24">
        <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">
            <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                <x-feature-header>
                    Robust backend components
                </x-feature-header>
                <p class="font-light">
                    When anonymous components aren't enough, you can fall back to full-blown PHP implementations. Coming in the future: these components will be the entry point for reactive components similar to Livewire or HTMX.
                </p>
            </div>

            <x-codeblock-home>
                <?= $this->code4 ?>
            </x-codeblock-home>
        </div>
    </div>

    <x-footer/>
</x-base>