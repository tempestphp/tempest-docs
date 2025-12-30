<button toggle-palette class="hidden sm:block cursor-pointer">
    <label for="search" class="sr-only">Search</label>
    <div class="flex rounded-xl group-[[data-scrolling]]:bg-(--ui-bg) bg-(--ui-bg)/50 dark:bg-[transparent] text-(--ui-text) hover:bg-(--ui-bg-elevated) ring ring-(--ui-border)/80 transition">
			<span class="grow px-4 py-2 text-base text-(--ui-text-muted) focus:outline-0 sm:text-sm/6">
				Search docs, blog...
			</span>
        <div class="flex py-1.5 pr-1.5">
            <kbd class="inline-flex items-center rounded px-2 text-(--ui-text-dimmed) font-medium">
                <x-icon name="tabler:command" class="size-4"/>
                <span class="text-[.95rem]">K</span>
            </kbd>
        </div>
    </div>
</button>
