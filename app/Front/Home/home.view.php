<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;

/** @var object{
 *     commit: object{
 *          url: string,
 *          message: string,
 *      },
 *      html_url: string,
 *      author: object{
 *          login: string
 *      }
 *   } $commit
 */
?>

<x-base>
    <div class="@HeroBlock">
        <x-header />

        <div class="flex flex-col gap-4 min-h-[512px] px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
            <div class=" flex-1 flex flex-col items-center justify-center gap-8">
                <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold">The PHP framework that gets out of your way.</h1>

                <div class="flex sm:flex-row flex-col gap-2">
                    <x-button uri="<?= uri(DocsController::class, category: 'framework', slug: '01-getting-started') ?>">Read the docs</x-button>
                    <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
                </div>

                <div :if="$commit" class="text-center">
                    🌊 <span class="font-bold">Latest commit: </span><a target="_blank" rel="noopener noreferrer" href="<?= $commit->html_url ?>" class="underline hover:no-underline"><?= $commit->commit->message ?></a> by <?= $commit->author->login ?>
                </div>
            </div>

            <div class="flex w-full gap-4 items-center text-[13px] justify-end text-white/60 mt-auto mb-0">
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
                        Zero-config controllers and routing
                    </x-feature-header>
                    <p class="font-light">
                        Tempest features a unique concept called <a class="underline hover:no-underline" href="/docs/framework/01-getting-started#content-project-structure">discovery</a>. Tempest will scan your code and find out what to do with it: from controller routes to event handlers, from console commands to dependency initializers; Tempest will detect everything without you having to write a single line of configuration or bootstrap code.
                    </p>
                </div>

                <x-codeblock-home>
                    <?= $this->code1 ?>
                </x-codeblock-home>

            </div>
        </div>


        <div class="w-full bg-zinc-50 slope-2 py-24">

            <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">

                <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                    <x-feature-header>
                        A refreshing new templating engine
                    </x-feature-header>
                    <p class="font-light">
                        Tempest dares to reimagine templating in PHP with a clean and modern front-end engine, inspired by clean and modern front-end frameworks. Do you prefer something tried and tested? Tempest has support for Blade as well!
                    </p>
                </div>

                <x-codeblock-home>
                    <?= $this->code5 ?>
                </x-codeblock-home>
            </div>
        </div>

        <div class="w-full py-24">
            <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">
                <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                    <x-feature-header>
                        An ORM that embraces PHP
                    </x-feature-header>
                    <p class="font-light">
                        Define models with simple, clean code. Complete with built-in validation, relations, migrations, and more. We don't try to reinvent database querying from scratch, so whenever you need a complex query, you can write SQL directly.
                    </p>
                </div>
                <x-codeblock-home>
                    <?= $this->code4 ?>
                </x-codeblock-home>
            </div>
        </div>

        <div class="w-full bg-tempest-blue-500 py-24 slope-2 px-4">
            <blockquote class="max-w-2xl w-full mx-auto text-[1.25rem] text-white text-center">
                <p>Tempest has already managed to become something more than an exercise, and you seem to have the experience, mentality and passion to lead its future to much greater heights.</p>
                <cite class="mt-3"><a class="text-[0.9375rem] mt-4 underline hover:no-underline not-italic" href="https://www.reddit.com/r/PHP/comments/1fi2dny/introducing_tempest_the_framework_that_gets_out/lngag06/">– Reddit</a></cite>
            </blockquote>
        </div>



        <div class="w-full py-24">

            <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">

                <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                    <x-feature-header>
                        Frictionless console commands
                    </x-feature-header>
                    <p class="font-light">
                        Just define your command, handle input dynamically, and deliver instant feedback. Tempest doesn't bother you with complex command definitions, just write your PHP code, and Tempest will figure out the rest. Interactive console components <a class="underline hover:no-underline" href="/docs/console/04-components">included</a>!
                    </p>
                </div>
                <x-codeblock-home>
                    <?= $this->code2 ?>
                </x-codeblock-home>
            </div>
        </div>


        <div class="w-full bg-zinc-50 slope-2 py-24">

            <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">
                <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                    <x-feature-header>
                        Static pages out of the box
                    </x-feature-header>
                    <p class="font-light">
                        Get static pages up and running right out of the box with zero hassle. Simply define your routes, and Tempest takes care of the rest — fetching the content and rendering it dynamically.
                    </p>
                </div>
                <x-codeblock-home>
                    <?= $this->code3 ?>
                </x-codeblock-home>

            </div>
        </div>

        <div class="w-full slope-4 bg-tempest-blue-500 text-white py-24 -mt-6 text-center px-4">
            <blockquote class="max-w-2xl w-full mx-auto text-[1.25rem]">
                <p> Tempest is a work of art 👌</p>
                <cite class="mt-3"><a class="text-[0.9375rem] mt-4 underline hover:no-underline not-italic" href="https://x.com/LukeDowning19/status/1836083961174397420">– Twitter</a></cite>
            </blockquote>
        </div>


        <div class="w-full py-24">

            <div class="w-full max-w-screen-xl mx-auto px-4 grid grid-cols-5 gap-6 md:gap-16">

                <div class="col-span-5 md:col-span-2 flex flex-col justify-center gap-4">
                    <x-feature-header>
                        And much more!
                    </x-feature-header>

                    <p class="font-light">
                        Configuration objects instead of arrays for easy autocompletion and injection, caching, a powerful dependency container at its heart, autowiring everywhere, there's so much to show. <a class="underline hover:no-underline" href="/docs/framework/01-getting-started">Take a look</a>!
                    </p>
                </div>
                <x-codeblock-home>
                    <?= $this->code6 ?>
                </x-codeblock-home>
            </div>
        </div>

        <x-footer />
</x-base>