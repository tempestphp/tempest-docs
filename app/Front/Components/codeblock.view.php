<x-component name="x-codeblock">
    <div class="grid justify-center gap-4 place-items-center">
        <div class="
            border-2
            rounded border-[#1b1429]
            shadow-lg p-6 bg-[#fafafa]
            mx-2
            overflow-x-scroll
            md:overflow-x-auto
            md:mx-0
            max-w-[98vw]
        ">
            <x-slot name="code" />
        </div>

        <p class="text-center md:max-w-[30vw]">
            <x-slot name="text" />
        </p>
    </div>
</x-component>