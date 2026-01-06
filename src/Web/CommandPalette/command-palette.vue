<script setup lang="ts">
import {
	ComboboxContent,
	ComboboxEmpty,
	ComboboxGroup,
	ComboboxInput,
	ComboboxItem,
	ComboboxLabel,
	ComboboxRoot,
} from 'reka-ui'
import { ref } from 'vue'
import BaseDialog from './base-dialog.vue'
import { registerPalette } from './register-palette'
import { handleCommand, useSearch } from './use-search'

const open = ref(false)

const query = ref<string>('')
const { results } = useSearch({ query, open })

registerPalette({ value: open })
</script>

<template>
	<base-dialog
		v-model:open="open"
		content-class="w-full h-full sm:h-auto sm:max-w-xl md:max-w-2xl focus-visible:outline-none text-sm"
		title="Command palette"
	>
		<combobox-root
			:open="true"
			:ignore-filter="true"
			:reset-search-term-on-blur="false"
			:reset-search-term-on-select="false"
		>
			<!-- Search -->
			<combobox-input
				v-model="query"
				placeholder="Search..."
				class="bg-transparent px-6 py-4 outline-none w-full placeholder-on-dialog-muted"
				auto-focus
				@keydown.enter.prevent
			/>
			<!-- Results -->
			<combobox-content
				class="p-2 border-(--ui-border) border-t h-full sm:max-h-160 overflow-y-auto"
				@escape-key-down="open = false"
			>
				<!-- No result -->
				<combobox-empty class="p-4 w-full text-center grow text-(--ui-text-muted)">
					<template v-if="query">
						No result. Try another query.
					</template>
					<template v-else>
						Type something to search.
					</template>
				</combobox-empty>

				<!-- Group by category -->
				<combobox-group v-for="category in results" :key="category.title">
					<!-- Category title -->
					<combobox-label class="my-2 px-4 pl-4 text-(--ui-primary)">
						{{ category.title }}
					</combobox-label>
					<!-- Items -->
					<combobox-item
						v-for="(item, c) in category.children"
						:key="item.hierarchy.join('_') + c"
						:as="item.type === 'uri' ? 'a' : 'button'"
						:value="item"
						:href="item.uri"
						class="flex flex-col data-highlighted:bg-(--ui-primary)/10 dark:data-highlighted:bg-(--ui-bg-elevated)/60 px-4 py-2 pl-4 rounded-md w-full text-(--ui-text-highlighted) text-left transition-colors"
						@select="(e) => handleCommand(item, e)"
					>
						<div class="flex items-center gap-x-1 text-(--ui-text-dimmed)">
							<template
								v-for="(breadcrumb, i) in item.hierarchy.slice(1)"
								:key="breadcrumb + i + c"
							>
								<template v-if="breadcrumb !== item.title">
									<span class="inline-block font-medium text-sm" v-text="breadcrumb" />
									<span v-if="i < item.hierarchy.length" class="last:hidden">·</span>
								</template>
							</template>
						</div>
						<span class="text-(--ui-text-toned)">{{ item.title }}</span>
					</combobox-item>
				</combobox-group>
			</combobox-content>
		</combobox-root>
	</base-dialog>
</template>
