<x-base>
    <x-aurora class="dark:hidden" />

    <x-slot name="head">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    </x-slot>

    <div class="w-full flex flex-col pb-8">
        <div class="w-full z-10 md:py-24 pt-12">
            <div class="w-full mx-auto grid md:grid-cols-2 xl:grid-cols-3 gap-12 md:px-24 px-8">
                <x-chart :chart="$visitsPerDay" label="Visits" chart-title="Website visits last 30 days"></x-chart>
                <x-chart :chart="$packageDownloadsPerDay" label="Downloads" chart-title="Package downloads over time"></x-chart>
            </div>
        </div>
    </div>
</x-base>
