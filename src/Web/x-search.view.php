<x-component name="x-search">
	<button toggle-palette class="cursor-pointer">
		<label for="search" class="sr-only">Search</label>
		<div class="flex rounded-xl group-[[data-scrolling]]:bg-[transparent] bg-background dark:bg-transparent outline-1 -outline-offset-1 outline-input-border focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-on-background-primary">
			<span class="block min-w-0 grow px-4 py-2 text-base text-on-input-muted focus:outline-0 sm:text-sm/6">
				Search docs, blog...
			</span>
			<div class="flex py-1.5 pr-1.5">
				<kbd class="inline-flex items-center rounded px-2 font-sans text-xs text-gray-400">⌘K</kbd>
			</div>
		</div>
	</button>
</x-component>
