<script setup lang="ts">
import { ref } from 'vue'
import { ComboboxContent, ComboboxGroup, ComboboxInput, ComboboxItem, ComboboxLabel, ComboboxRoot } from 'reka-ui'
import { registerPalette } from './register-palette'
import BaseDialog from './base-dialog.vue'
import { handleCommand, useSearch } from './use-search'

const open = ref(false)

const query = ref<string>('')
const { results } = useSearch({ query })

registerPalette({ value: open })
</script>

<template>
	<base-dialog v-model:open="open" content-class="w-full max-w-2xl" title="Command palette">
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
				@keydown.enter.prevent
			/>
			<!-- Results -->
			<combobox-content
				class="p-2 border-(--ui-border) border-t max-h-[40rem] overflow-y-auto"
				@escape-key-down="open = false"
			>
				<!-- No result -->
				<div v-if="!Object.values(results).filter(Boolean)?.length" class="p-4 w-full text-center grow">
					<template v-if="query">
						No result. Try another query.
					</template>
					<template v-else>
						Type something to search.
					</template>
				</div>

				<!-- Group by category -->
				<combobox-group v-for="category in results" :key="category.title">
					<!-- Category title -->
					<combobox-label class="mt-3 mb-3 px-4 pl-4 font-semibold">
						{{ category.title }}
					</combobox-label>
					<!-- Items -->
					<combobox-item
						v-for="item in category.children"
						:key="item.hierarchy.join('_')"
						:as="item.type === 'uri' ? 'a' : 'button'"
						:value="item"
						:href="item.uri"
						class="flex flex-col dark:data-[highlighted]:bg-(--ui-bg-elevated)/60 data-[highlighted]:bg-(--ui-primary)/10 px-4 py-2 pl-4 rounded-md w-full text-(--ui-text-highlighted) text-left transition-colors"
						@select="(e) => handleCommand(item, e)"
					>
						<span class="font-medium text-(--ui-text-dimmed) text-sm" v-text="item.hierarchy[1]" />
						<span>{{ item.title }}</span>
					</combobox-item>
				</combobox-group>
			</combobox-content>
		</combobox-root>
	</base-dialog>
</template>
