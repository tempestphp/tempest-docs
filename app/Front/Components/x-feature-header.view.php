<x-component name="feature-header">
    <div class="flex gap-4">
        <!-- <div class="flex items-center h-[calc(22px*1.25)]">
            <div class="flex items-center justify-center rounded-[9px] w-[2.5rem] h-[2rem] bg-gradient-to-b from-tempest-blue-500 to-tempest-blue-600 text-white flex-shrink-0">
                <x-slot name="icon" />
            </div>
        </div> -->
        <h2 class="font-bold text-[22px] font-display leading-tight">
            <x-slot />
        </h2>
    </div>
</x-component>
