<?php

/** @var \Tempest\View\GenericView $this */

use App\Front\Code\CodeController;

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

    <div class="hero-bg h-full flex items-center justify-center">
        <form action="<?= uri([CodeController::class, 'submit']) ?>" method="POST" class="w-full">
            <div class="grid gap-4 max-full max-w-[66%] mx-auto">
                <input type="hidden" name="lang" :value="$language">
                <label for="code" class="text-xl text-white">Paste your code</label>
                <textarea name="code" id="code" class="border-2 border-[--primary] w-full p-4 rounded bg-[var(--code-background)] text-[var(--foreground)]" rows="20" autofocus>{{ $code }}</textarea>
                <div class="flex justify-end">
                    <input type="submit" name="Generate" class="bg-[var(--link-color)] text-white font-bold p-4 py-3 hover:bg-[var(--link-hover-color)] rounded cursor-pointer"/>
                </div>
            </div>
        </form>
    </div>
</x-base>
