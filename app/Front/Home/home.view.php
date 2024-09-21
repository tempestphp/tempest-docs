<?php

use App\Front\Docs\DocsController;
use function Tempest\uri;

?>

<x-base>
    <div class="flex items-center justify-center bg-[#4f95d1] text-white">
        <div class="grid gap-8 content-center place-items-center mt-[30vh] mb-[32vh]">
            <h1 class="text-4xl font-bold max-w-[66%] text-center">The framework that gets out of your way, this is Tempest.</h1>

            <nav class="flex gap-2">
                <x-button uri="<?= uri(DocsController::class, category: 'framework', slug: '01-getting-started') ?>">Read the docs</x-button>
                <x-button uri="https://github.com/tempestphp/tempest-framework">Tempest on GitHub</x-button>
            </nav>
        </div>
    </div>

    <div class="mt-[-6em] grid gap-8 mb-8">
        <div class="flex justify-center">
            <div class="border-2 border-b-4 rounded border-[#1b1429] shadow-lg p-6 bg-[#fafafa]">
                <?= $this->code1 ?>
            </div>
        </div>
        <div class="flex justify-center">
            <div class="border-2 border-b-4 rounded border-[#1b1429] shadow-lg p-6 bg-[#fafafa]">
                <?= $this->code2 ?>
            </div>
        </div>
    </div>
</x-base>