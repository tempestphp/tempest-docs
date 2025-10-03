<?php

/** @var \Tempest\View\GenericView $this */

use App\Web\Code\CodeController;

use function Tempest\Router\uri;

?>

<x-base>
    <x-slot name="styles">
        <style>
            textarea#code {
                font-family: Source Code Pro, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            }
        </style>
    </x-slot>

    <div class="flex justify-center h-full hero-bg">
        <div class="flex items-center w-full grow">
            <form :action="uri([CodeController::class, 'submit'])" method="POST" class="w-full">
                <x-csrf-token />
                <input type="hidden" name="_method" value="POST">
                <div class="gap-4 grid mx-auto max-w-[66%] max-full">
                    <input type="hidden" name="lang" :value="$language"/>
                    <label for="code" class="text-xl">Paste your code</label>
                    <p class="text-(--ui-text-muted) text-sm mb-4">

                    </p>
                    <textarea name="code" id="code" class="font-mono bg-(--code-background) p-4 border-(--ui-border-muted) border-2 rounded focus:outline-none w-full text-(--foreground)" rows="20" autofocus>{{ $code }}</textarea>
                    <div class="flex justify-end">
                        <button type="submit" class="no-primary cursor-pointer rounded-md font-medium inline-flex items-center focus:outline-hidden disabled:cursor-not-allowed aria-disabled:cursor-not-allowed disabled:opacity-75 aria-disabled:opacity-75 transition-colors px-4 py-2 gap-2 ring ring-inset ring-(--ui-border-accented) text-(--ui-text) bg-(--ui-bg) hover:bg-(--ui-bg-elevated) disabled:bg-(--ui-bg) aria-disabled:bg-(--ui-bg) focus-visible:ring-2 focus-visible:ring-(--ui-border-inverted)">
                          Submit
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-base>
