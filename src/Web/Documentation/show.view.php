<?php
/** @var \Tempest\Web\Documentation\ChapterView $this */
?>

<x-base :title="$this->currentChapter->title">
  <!-- Main container -->
  <main class="container grow px-4 mx-auto xl:px-8 flex isolate">
    <!-- Sidebar -->
		<div class="hidden lg:block xl:px-6 sticky xl:w-[20rem] max-h-[calc(100dvh-var(--ui-header-height))] overflow-auto top-28 pt-4 shrink-0">
			<!-- Menu -->
			<nav class="flex flex-col gap-y-6 pb-8">
				<div :foreach="$this->categories() as $category" class="flex flex-col">
					<!-- Category title -->
					<span class="font-semibold text-(--ui-text) mb-2">
						<?= ucfirst($category) ?>
					</span>
					<!-- Chapter list -->
					<ul class="flex flex-col border-s border-(--ui-border)">
						<li :foreach="$this->chaptersForCategory($category) as $chapter" class="-ms-px ps-1.5">
							<a :href="$chapter->getUri()" class="group relative w-full px-2.5 py-1.5 flex items-center gap-1.5 text-sm focus:outline-none focus-visible:outline-none hover:text-(--ui-text-highlighted) data-[state=open]:text-(--ui-text-highlighted) transition-colors <?= $this->isCurrent($chapter) ? 'text-(--ui-primary) after:absolute after:-left-1.5 after:inset-y-0.5 after:block after:w-px after:rounded-full after:transition-colors after:bg-(--ui-primary)' : 'text-(--ui-text-muted)' ?>">
								{{ $chapter->title }}
							</a>
						</li>
					</ul>
				</div>
			</nav>
		</div>
    <!-- Main content -->
    <div class="grow px-2 w-full lg:pl-12 flex min-w-0">
			<!-- Documentation page -->
			<article class="grow w-full flex flex-col min-w-0">
				<!-- Header -->
				<x-template :if="$this->currentChapter">
					<div class="relative border-b border-(--ui-border) pb-8">
						<a :href="$this->currentChapter->getUri()" class="text-(--ui-info) font-semibold">
							{{ \Tempest\Support\Str\to_title_case($this->currentChapter->category) }}
						</a>
						<h1 id="top" class="mt-2 font-bold text-4xl text-(--ui-text-highlighted) lg:scroll-mt-[calc(1.5*var(--ui-header-height))]">
							{{ $this->currentChapter->title }}
						</h1>
						<div :if="$this->currentChapter->description" class="text-lg text-(--ui-text-muted) mt-4">
							{!! $this->currentChapter->description !!}
						</div>
					</div>
					<div :if="$this->currentChapter" class="prose dark:prose-invert mt-8 pb-24 space-y-12">
						{!! $this->currentChapter->body !!}
					</div>
				</x-template>
				<!-- Docs footer -->
				<nav class="bg-[--card] text-[--card-foreground] font-bold rounded-md p-4 flex justify-between gap-2 my-6">
					<a :if="$this->nextChapter()" :href="$this->nextChapter()?->getUri()" class="underline hover:no-underline">
						Next: {{ $this->nextChapter()?->title }}
					</a>
				</nav>
			</article>
			<!-- On this page -->
			<nav :if="($subChapters = $this->getSubChapters()) !== []" class="w-2xs shrink-0 hidden xl:block sticky max-h-[calc(100dvh-var(--ui-header-height))] overflow-auto top-28 pt-4 pl-12 pr-4">
				<div class="text-sm">
					<span class="inline-block font-bold text-[--primary] mb-3">On this page</span>
					<ul class="flex flex-col">
						<li :foreach="['#top' => $this->currentChapter->title, ...$subChapters] as $url => $title">
							<a :href="$url" :data-on-this-page="$title" class="group relative text-sm flex items-center focus-visible:outline-(--ui-primary) py-1 text-(--ui-text-muted) hover:text-(--ui-text) data-[active]:text-(--ui-primary) transition-colors">
								{{ \Tempest\Support\Str\strip_tags($title) }}
							</a>
						</li>
					</ul>
				</div>
			</nav>
    </div>
		<template id="copy-template">
			<button class="absolute group top-2 right-2 size-6 flex items-center justify-center cursor-pointer opacity-0 group-hover:opacity-100 transition text-(--ui-text-dimmed) bg-(--ui-bg-muted) rounded border-(--ui-border) hover:text-(--ui-text-highlighted)">
				<x-icon name="tabler:copy" class="size-5 absolute inset-0" />
				<x-icon name="tabler:copy-check-filled" class="size-5 absolute inset-0 opacity-0 group-[[data-copied]]:opacity-100 transition" />
			</button>
		</template>
  </main>
	<script>
    function findPreviousH2(element) {
        while (element && element.previousElementSibling) {
            element = element.previousElementSibling;
            if (element.tagName === "H2") {
                return element;
            }
        }
        return null;
    }

    function updateActiveChapters() {
        let visibleH2s = new Set();
        let lastVisibleH2 = null;
        const elements = document.querySelectorAll(".prose *");
        const topMargin = 100; // Adjust this value to control the ejection margin

        for (const el of elements) {
            const rect = el.getBoundingClientRect();
            if (rect.top - topMargin >= 0 && rect.bottom <= window.innerHeight) {
                if (el.tagName === "H2" || el.tagName === "H1") {
                    visibleH2s.add(el.textContent.trim());
                    lastVisibleH2 = el;
                } else {
                    const previousH2 = findPreviousH2(el);
                    if (previousH2) {
                        visibleH2s.add(previousH2.textContent.trim());
                    }
                }
            }
        }
        
        document.querySelectorAll('[data-on-this-page]').forEach(link => {
            if (visibleH2s.has(link.getAttribute('data-on-this-page'))) {
                link.setAttribute('data-active', 'true');
            } else {
                link.removeAttribute('data-active');
            }
        });
    }

    window.addEventListener("scroll", () => {
        requestAnimationFrame(updateActiveChapters);
    });

    document.addEventListener("DOMContentLoaded", updateActiveChapters);
	</script>
	<script>
		function extractPlainText(pre, button) {
    return Array.from(pre.childNodes)
        .filter(node => node !== button) // Exclude the button itself
        .map(node => 
            node.nodeType === Node.TEXT_NODE ? node.textContent :
            node.nodeType === Node.ELEMENT_NODE ? node.textContent : ""
        ).join("").trim();
		}

    document.addEventListener("DOMContentLoaded", () => {
    	document.querySelectorAll(".prose pre").forEach(pre => {
				console.log(pre)
						// Clone the copy button template
						const template = document.getElementById("copy-template");
						if (!template) return;
						
						const copyButton = template.content.cloneNode(true).querySelector("button");
						pre.classList.add("relative", "group"); // Ensure positioning for absolute button placement
						
						// Insert button inside pre
						pre.appendChild(copyButton);

						// Copy event handler
						copyButton.addEventListener("click", () => {
								const content = extractPlainText(pre, copyButton);
								navigator.clipboard.writeText(content).then(() => {
										copyButton.setAttribute("data-copied", "true");
                		setTimeout(() => copyButton.removeAttribute("data-copied"), 2000);
								}).catch(err => console.error("Copy failed", err));
						});
				});
		});
	</script>
</x-base>
