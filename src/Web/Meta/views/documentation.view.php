<?php
/** @var \App\Web\Documentation\Chapter $chapter */
?>

<x-meta-image>
	<div class="w-full flex flex-col justify-center font-display">
    <span class="leading-none text-3xl font-extralight text-(--ui-text-highlighted)">{{ $chapter->title }}</span>
		<div class="flex items-center gap-x-1 mt-1 text-xs text-(--ui-text-muted)">
			<span>Documentation</span>
			<span>/</span>
			<span>{{ \Tempest\Support\Str\to_sentence_case($chapter->category) }}</span>
		</div>
	</div>
</x-meta-image>
