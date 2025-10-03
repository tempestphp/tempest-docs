<x-base-code>
    <div class="top-0 right-0 z-[99] fixed flex gap-2 bg-red p-4" :if="!$clean">
        <a
                :href="$cleanUrl"
                class="bg-white hover:bg-tempest-blue-500 shadow-xl p-2 border-2 hover:border-white border-transparent rounded-full cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M9 17.25v1.007a3 3 0 0 1-.879 2.122L7.5 21h9l-.621-.621A3 3 0 0 1 15 18.257V17.25m6-12V15a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 15V5.25m18 0A2.25 2.25 0 0 0 18.75 3H5.25A2.25 2.25 0 0 0 3 5.25m18 0V12a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 12V5.25" />
            </svg>
        </a>
        <a
                :href="$editUrl"
                class="bg-white hover:bg-tempest-blue-500 shadow-xl p-2 border-2 hover:border-white border-transparent rounded-full cursor-pointer">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
            </svg>
        </a>
    </div>

    <div class="top-0 left-0 absolute flex justify-center items-center w-full h-full">
        <div
                class="bg-(--ui-bg) border-(--ui-border) rounded-md p-8 max-h-[75%] overflow-auto"
                :class="implode([
                    $center ? 'text-center' : '',
                    $clean ?: 'border'
                ])"
                id="screenshot"
        >
            <pre data-lang="<?= $language ?>"><?= $code ?></pre>
        </div>
    </div>

</x-base-code>
