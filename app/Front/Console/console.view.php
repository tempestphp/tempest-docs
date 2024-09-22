<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;
?>

<x-base title="Console" :meta="\App\Front\Meta\MetaType::CONSOLE">
    <x-slot name="styles">
        <style>
            body {
                background-color: #f6ffff;
            }
        </style>
    </x-slot>

    <div class="flex items-center justify-center bg-[#4f95d1] text-white slope header-gradient">
        <div class="grid gap-4 content-center place-items-center mt-[25vh] mb-[35vh] relative px-4">
            <h1 class="text-2xl md:text-4xl font-extrabold text-center font-argon md:max-w-[60%]">A revolutionary way of building console applications in PHP.</h1>

            <h2 class="text-xl font-body px-4 md:px-0">
                <span class="tempest">tempest/console</span> will blow your mind
            </h2>

            <nav class="flex flex-wrap gap-2 mt-8">
                <x-button uri="<?= uri(DocsController::class, category: 'console', slug: '01-getting-started') ?>">Read the docs</x-button>
                <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
            </nav>
        </div>
    </div>

    <div class="mt-[-5vh] md:mt-[-15vh] grid gap-12 relative font-open-sans">
        <x-codeblock>
            <x-slot name="code">
                <?= $this->code1 ?>
            </x-slot>

            <x-slot name="text">
                Start with a simple composer require
            </x-slot>
        </x-codeblock>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code2 ?>
            </x-slot>

            <x-slot name="text">
                Make a console command class — anywhere you want
            </x-slot>
        </x-codeblock>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code3 ?>
            </x-slot>

            <x-slot name="text">
                Run the command
            </x-slot>
        </x-codeblock>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code4 ?>
            </x-slot>

            <x-slot name="text">
                And that's it — like, for real. That's it.
            </x-slot>
        </x-codeblock>

        <x-codeblock>
            <x-slot name="code">
                <?= $this->code5 ?>
            </x-slot>

            <x-slot name="text">
                Command input definitions are defined by the method's signature, without any additional configuration.
            </x-slot>
        </x-codeblock>

        <div class="grid justify-center gap-4 place-items-center p-2 bg-white">
            <video autoplay muted controls loop class="shadow-lg md:max-w-[60%] p-4 rounded">
                <source src="/img/ask-c.mp4" type="video/mp4"/>
            </video>

            <p>
                Built-in interactive components, <a class="underline hover:no-underline" href="<?= uri(DocsController::class, category: 'console', slug: '01-getting-started') ?>">and much more</a>.
            </p>
        </div>
    </div>

    <div class="slope-3 md:pt-32 py-16 px-4 md:px-16 flex justify-center mt-8 bg-[#4f95d1] text-white font-bold header-gradient">
        <div class="grid gap-4 place-items-center">
            <h2 class="text-2xl">
                Get started with <span class="tempest">Tempest</span> today, now in alpha!
            </h2>

            <nav class="flex flex-wrap gap-2 mt-6 md:mt-0">
                <x-button uri="<?= uri(DocsController::class, category: 'framework', slug: '01-getting-started') ?>">Read the docs</x-button>
                <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
            </nav>
        </div>
    </div>
</x-base>