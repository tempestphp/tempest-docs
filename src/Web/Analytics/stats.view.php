<x-base>
    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot>

    <div class="@HeroBlock">
        <div class="flex flex-col gap-4 px-4 items-center justify-center py-8 max-w-screen-xl mx-auto w-full text-white">
            <div class=" flex-1 flex flex-col items-center justify-center gap-8">
                <h1 class="text-[1.5rem] md:text-[2.5rem] max-w-[640px] leading-[1.1] text-center font-display font-extrabold">Tempest Stats</h1>
            </div>
        </div>
    </div>

    <div class="w-full flex flex-col ">
        <div class="w-full z-10 md:py-24 pt-12">
            <div class="w-full mx-auto grid md:grid-cols-2 xl:grid-cols-3 gap-12 md:px-24 px-8">
                <x-chart :chart="$visitsPerHour" label="Visits per hour" title="Website visits last 24 hours"></x-chart>
                <x-chart :chart="$visitsPerDay" label="Visits per day" title="Website visits last 30 days"></x-chart>
            </div>
        </div>
    </div>
</x-base>
