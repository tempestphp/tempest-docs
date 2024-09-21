<x-component name="x-codeblock">
    <div class="grid justify-center gap-4">
        <div class="
            border-2
            rounded border-[#1b1429]
            shadow-lg p-6 bg-[#fafafa]
            mx-2
            overflow-x-scroll
            md:overflow-x-auto
            md:mx-0
        ">
            <x-slot name="code" />
        </div>

        <p class="text-center">
            <x-slot name="text" />
        </p>
    </div>
</x-component>