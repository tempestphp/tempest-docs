<x-component name="x-home-section">
	<section class="mb-20 lg:mb-[7vh] flex justify-center flex-col tracking-tighter px-6">
		<div class="grid grid-cols-1 lg:grid-cols-2 gap-x-12 items-center">
			<!-- Left -->
			<div class="p-2 lg:p-0">
				<div class="max-w-xl text-sans">
					<span class="text-xl md:text-4xl xl:text-5xl font-semibold leading-none flex flex-col text-display">
						{{ $heading }}
					</span>
					<p :foreach="$paragraphs as $paragraph" class="mt-3 md:mt-2 xl:mt-4 text-lg xl:text-2xl text-(--ui-text-muted)">
						{{ $paragraph }}
					</p>
				</div>
				<div class="mt-6">
					<a :href="$linkUri" class="no-primary rounded-md font-medium inline-flex items-center focus:outline-hidden disabled:cursor-not-allowed aria-disabled:cursor-not-allowed disabled:opacity-75 aria-disabled:opacity-75 transition-colors px-4 py-2 gap-2 ring ring-inset ring-(--ui-border-accented) text-(--ui-text) bg-(--ui-bg) hover:bg-(--ui-bg-elevated) disabled:bg-(--ui-bg) aria-disabled:bg-(--ui-bg) focus-visible:ring-2 focus-visible:ring-(--ui-border-inverted)">
						<span>{{ $linkLabel }}</span>
						<x-icon name="tabler:arrow-right" class="size-4" />
					</a>
				</div>
			</div>
			<!-- Right -->
			<div class="flex flex-col gap-2 p-2 text-sm bg-(--ui-bg)/20 backdrop-blur rounded-xl">
				<div :foreach="$snippets as $snippet" class="bg-(--ui-bg)/50 border border-(--ui-border) rounded-md p-4 [&_pre]:h-full [&_pre]:overflow-x-auto">
					{!! $codeBlocks[$snippet] !!}
				</div>
			</div>
		</div>
	</section>
	<style>
	</style>
</x-component>
