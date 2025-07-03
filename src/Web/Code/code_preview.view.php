<x-base>
    <x-aurora class="dark:hidden"/>
    <x-moonlight />
    <x-rain />

    <div class="bg-red p-4 fixed z-[99] right-0 top-0 flex gap-2">
        <a
                :href="$editUrl"
                class="
                cursor-pointer
            bg-white
            p-2
            rounded-full
            shadow-xl
            border-2 border-transparent
            hover:bg-tempest-blue-500
            hover:border-white
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
            </svg>
        </a>
    </div>

    <div class="flex items-center justify-center h-full absolute top-0 left-0 w-full">
        <div class="bg-(--ui-bg) border border-(--ui-border) rounded-md p-8 max-h-[75%] overflow-scroll" :class="$center ? 'text-center' : ''" id="screenshot">
            <pre data-lang="<?= $language ?>"><?= $code ?></pre>
        </div>
    </div>

</x-base>
