<?php

/** @var \Tempest\View\GenericView $this */

use App\Web\Code\CodeController;

use function Tempest\uri;

?>

<x-base>
    <x-slot name="styles">
        <style>
            textarea#code {
                font-family: Source Code Pro, ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            }
        </style>
    </x-slot>

    <div class="hero-bg h-full flex justify-center">
        <x-form :action="uri([CodeController::class, 'submit'])" class="w-full">
            <div class="grid gap-4 max-full max-w-[66%] mx-auto">
                <input type="hidden" name="lang" :value="$language" />
                <label for="code" class="text-xl">Paste your code</label>
                <textarea name="code" id="code" class="border-2 border-[--primary] w-full p-4 rounded bg-[var(--code-background)] text-[var(--foreground)]" rows="20" autofocus>{{ $code }}</textarea>
                <div class="flex justify-end">
                    <input type="submit" class="no-primary rounded-md font-medium inline-flex items-center focus:outline-hidden disabled:cursor-not-allowed aria-disabled:cursor-not-allowed disabled:opacity-75 aria-disabled:opacity-75 transition-colors px-4 py-2 gap-2 ring ring-inset ring-(--ui-border-accented) text-(--ui-text) bg-(--ui-bg) hover:bg-(--ui-bg-elevated) disabled:bg-(--ui-bg) aria-disabled:bg-(--ui-bg) focus-visible:ring-2 focus-visible:ring-(--ui-border-inverted)" />
                </div>
            </div>
        </x-form>
    </div>
</x-base>
