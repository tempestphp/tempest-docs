<?php

/** @var \Tempest\View\GenericView $this */

use App\Front\Code\CodeController;

use function Tempest\uri;

?>

<x-base>
    <form action="<?= uri([CodeController::class, 'submit']) ?>" method="POST">
        <div class="flex justify-center items-center flex-col w-3/5 mx-auto gap-4 font-mono">
            <label for="code" class="text-xl">Paste your code</label>
            <textarea name="code" id="code" class="border-2 border-[--primary] w-full p-4 rounded bg-[var(--code-background)] text-[var(--foreground)]" rows="20" autofocus></textarea>
            <input type="submit" name="Generate" class="bg-[var(--link-color)] text-white font-bold p-4 py-3 hover:bg-[var(--link-hover-color)] rounded cursor-pointer"/>
        </div>
    </form>
</x-base>
