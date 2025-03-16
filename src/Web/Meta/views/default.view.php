<x-meta-image>
	<div class="absolute inset-0 flex items-center justify-center">
		<img class="opacity-30 absolute right-10 size-40" src="/tempest-logo-transparent.svg">
	</div>
  <div class="relative w-full flex-col justify-center font-display gap-2">
    <span class="leading-none text-3xl font-extralight text-(--ui-text-highlighted)">{{ $title ?? 'Tempest' }}</span>
    <div class="flex items-center gap-x-1 mt-0.5 text-xs text-(--ui-text-muted)">
      <span>{{ $subtitle ?? 'The PHP framework that gets out of your way.' }}</span>
    </div>
  </div>
</x-meta-image>
