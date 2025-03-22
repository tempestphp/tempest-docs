import type { FuseResult } from 'fuse.js'
import Fuse from 'fuse.js'
import type { Ref } from 'vue'
import { shallowRef, watch } from 'vue'
import index from './index.json'

interface Options {
	query: Ref<string>
}

interface Command {
	title: string
	hierarchy: string[]
	// eslint-disable-next-line ts/ban-types
	type: 'uri' | 'js' | (string & {})
	uri?: string | null
	javascript?: string | null
}

type SearchResultItem = FuseResult<Command>
type TreeNode = Command & {
	children?: TreeNode[]
}

export function handleCommand(item: TreeNode, event: Event) {
	// @ts-expect-error not typed
	window.toggleCommandPalette?.()

	if (item.type === 'uri') {
		return event.preventDefault()
	}

	if (item.type === 'js') {
		window[item.javascript!]?.()
	}
}

function buildHierarchyTree(items: SearchResultItem[]): Record<string, TreeNode> {
	const tree: Record<string, TreeNode> = {}
	const seenTitles = new Set<string>()

	for (const { item } of items) {
		const topLevel = item.hierarchy[0]

		if (!tree[topLevel]) {
			tree[topLevel] = {
				title: topLevel,
				children: [],
				hierarchy: [topLevel],
				type: item.type,
				uri: item.uri,
				javascript: item.javascript,
			}
		}

		if (!seenTitles.has(item.hierarchy.join('_'))) {
			tree[topLevel].children!.push({
				hierarchy: item.hierarchy,
				title: item.title,
				uri: item.uri,
				javascript: item.javascript,
				type: item.type,
			})
			seenTitles.add(item.hierarchy.join('_'))
		}
	}

	return tree
}

export function useSearch(options: Options) {
	const results = shallowRef<Record<string, TreeNode>>({ Commands: { title: 'Commands', type: 'uri', hierarchy: ['Commands'], children: index.filter((item) => item.hierarchy.at(0) === 'Commands') } })
	const fuse = new Fuse(index, {
		keys: ['title', 'hierarchy', 'fields'],
		includeScore: true,
		shouldSort: true,
		threshold: 0.2,
	})

	watch(options.query, (query) => {
		results.value = buildHierarchyTree(fuse.search(query) as SearchResultItem[])
	})

	return {
		results,
	}
}
