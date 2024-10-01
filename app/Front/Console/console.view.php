<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;
?>

<x-base title="Console" :meta="\App\Front\Meta\MetaType::CONSOLE">
    <div class="@HeroBlock">
        <x-header />


        <div class="flex flex-col gap-4 min-h-[512px] px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
            <div class=" flex-1 flex flex-col items-center justify-center gap-8">
                <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold">A revolutionary way of building console applications in PHP.</h1>


                <div class="flex gap-3 my-8 flex-col text-center items-center ">
                    <span> Start with a simple composer require </span>
                    <div class="bg-tempest-blue-950/[90%] px-4 py-2 rounded-[9px] text-white border border-tempest-blue-500 font-medium font-mono text-[16px]">
                        <span class="text-tempest-blue-100 font-normal">composer</span> require tempest/console
                    </div>
                </div>

                <div class="flex sm:flex-row flex-col gap-2">
                    <x-button uri="<?= uri(DocsController::class, category: 'framework', slug: '01-getting-started') ?>">Read the docs</x-button>
                    <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
                </div>





            </div>

            <div class=" flex w-full gap-4 items-center text-[13px] justify-end text-white/60 mt-auto mb-0">
                <span class="flex items-center gap-1">
                    <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-camera">
                        <path d="M14.5 4h-5L7 7H4a2 2 0 0 0-2 2v9a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V9a2 2 0 0 0-2-2h-3l-2.5-3z" />
                        <circle cx="12" cy="13" r="3" />
                    </svg>
                    <span>Photo by <a href="https://unsplash.com/@lc_photography" class="underline" target="_blank" rel="noreferrer noopener">Leon Contreras</a>
                    </span>
            </div>
        </div>
    </div>


    <div class="w-full flex flex-col ">
        <div class="w-full -mt-20 z-10 pb-24">
            <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">
                <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4 pt-24">
                    <x-feature-header>
                        <x-slot name="icon">!</x-slot>
                        Make a console command class — anywhere you want
                    </x-feature-header>
                    <!-- <p class="font-light">
                        Tempest features a unique concept called <a class="underline hover:no-underline" href="/docs/framework/01-getting-started#content-project-structure">discovery</a>. Tempest will scan your code and find out what to do with it: from controller routes to event handlers, from console commands to dependency initializers; Tempest will detect everything without you having to write a single line of configuration or bootstrap code.
                    </p> -->
                </div>

                <x-codeblock-home>
                    <?= $this->code2 ?>
                </x-codeblock-home>

            </div>
        </div>
    </div>

    <div class="w-full bg-zinc-50 slope-2 py-24">

        <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">

            <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                <x-feature-header>
                    Run the command

                </x-feature-header>
                <!-- <p class="font-light">
                        Tempest dares to reimagine templating in PHP with a clean and modern front-end engine, inspired by clean and modern front-end frameworks. Do you prefer something tried and tested? Tempest has support for Blade as well!
                    </p> -->
            </div>

            <x-codeblock-home>
                <?= $this->code3 ?>
            </x-codeblock-home>
        </div>
    </div>

    <div class="w-full  py-24">

        <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">

            <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                <x-feature-header>
                    And that's it — like, for real. That's it.

                </x-feature-header>
                <!-- <p class="font-light">
                Tempest dares to reimagine templating in PHP with a clean and modern front-end engine, inspired by clean and modern front-end frameworks. Do you prefer something tried and tested? Tempest has support for Blade as well!
            </p> -->
            </div>

            <x-codeblock-home>
                <?= $this->code4 ?>
            </x-codeblock-home>
        </div>
    </div>


    <div class="w-full bg-zinc-50 slope-2 py-24">

        <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">

            <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                <x-feature-header>
                    Command input definitions are defined by the method's signature, without any additional configuration.

                </x-feature-header>
                <!-- <p class="font-light">
                        Tempest dares to reimagine templating in PHP with a clean and modern front-end engine, inspired by clean and modern front-end frameworks. Do you prefer something tried and tested? Tempest has support for Blade as well!
                    </p> -->
            </div>

            <x-codeblock-home>
                <?= $this->code5 ?>
            </x-codeblock-home>
        </div>
    </div>



    <div class="w-full  py-24">
        <div class="w-full max-w-screen-xl mx-auto px-4  flex flex-col items-center gap-8">

            <video autoplay muted controls loop class="shadow-lg md:max-w-[60%] p-4 rounded">
                <source src="/img/ask-c.mp4" type="video/mp4" />
            </video>

            <p class>
                Built-in interactive components, <a class="underline hover:no-underline" href="<?= uri(DocsController::class, category: 'console', slug: '01-getting-started') ?>">and much more</a>.
            </p>
        </div>
    </div>

    <x-footer />

</x-base>