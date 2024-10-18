<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;
?>

<x-base title="View" :meta="\App\Front\Meta\MetaType::VIEW">
    <div class="@HeroBlock">
        <x-header />


        <div class="flex flex-col gap-4 min-h-[512px] px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
            <div class=" flex-1 flex flex-col items-center justify-center gap-8">
                <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold">The PHP templating engine that speaks your language</h1>


                <div class="flex gap-3 my-8 flex-col text-center items-center ">
                    <div class="bg-tempest-blue-950/[90%] px-4 py-2 rounded-[9px] text-white border border-tempest-blue-500 font-medium font-mono text-[16px]">
                        <span class="text-tempest-blue-100 font-normal">composer</span> require tempest/view
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
                        Tempest View embraces HTML as its syntax: template inheritance and inclusion is modelled via HTML elements; data binding and control structures are handled with attributes.
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
                    Tempest's discovery is built-in — that's a given. Just create a <code>.view.php</code> file, and Tempest will take care of the rest. No config needed.
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
                    PHP at your fingertips
                </x-feature-header>
                <p class="font-light">
                    Just in case you need that little extra, PHP's there to do whatever you need.
                </p>
            </div>

            <x-codeblock-home>
                <?= $this->code3 ?>
            </x-codeblock-home>
        </div>
    </div>

    <x-footer />
</x-base>