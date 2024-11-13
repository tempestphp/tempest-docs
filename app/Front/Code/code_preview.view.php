<x-base>

    <x-slot name="scripts">
        <script src="/html2canvas.min.js" defer></script>
        <script src="/filesaver.min.js" defer></script>

        <script>
            const makeScreenshot = document.getElementById('make-screenshot');

            makeScreenshot.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();

                let screenshot = document.getElementById("screenshot");
                screenshot.classList.add('hero-bg-screenshot');
                html2canvas(screenshot, {
                    allowTaint: true,
                    useCORS: true,
                })
                    .then(function (canvas) {
                        screenshot.classList.remove('hero-bg-screenshot');
                        canvas.toBlob(function (blob) {
                            const date  = new Date;

                            const timestamp = date.getFullYear() + '-' + date.getMonth() + '-' + date.getDay() + '_' + date.getHours() + '-' + date.getMinutes() +  '-' + date.getSeconds();

                            saveAs(blob, "screenshot-" + timestamp + ".png");
                        });
                    })
                    .catch((e) => {
                        screenshot.classList.remove('hero-bg-screenshot');
                    });


            });
        </script>
    </x-slot>

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
            hover:text-white
            hover:border-white
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10" />
            </svg>
        </a>
        <button
                id="make-screenshot"
                class="
            bg-white
            p-2
            rounded-full
            shadow-xl
            border-2 border-transparent
            hover:bg-tempest-blue-500
            hover:text-white
            hover:border-white
        ">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
            </svg>
        </button>
    </div>

    <div class="hero-bg h-full flex items-center justify-center">
        <div class="p-24" id="screenshot">
            <x-codeblock-home class="max-h-[80%]">
                <pre><?= $code ?></pre>
            </x-codeblock-home>
        </div>
    </div>

</x-base>