import type { AlgoliaHit } from 'instantsearch.js'
import instantsearch from 'instantsearch.js'
import { liteClient as algoliasearch } from 'algoliasearch/lite'
import { connectHits, connectSearchBox } from 'instantsearch.js/es/connectors'
import { shallowRef } from 'vue'

interface GroupedResults {
	title: string
	id: string
	items: FirstLevelEntry[]
}

interface FirstLevelEntry {
	title: string
	url: string
	anchor?: string
	children: NestedResult[]
	id: string
}

interface NestedResult {
	title: string
	url: string
	anchor?: string
	id: string
}

const results = shallowRef<GroupedResults[]>([])

export default function useAlgolia() {
	let _refine: (string: string) => void
	function refine(string: string): void {
		_refine?.(string)
	}

	const searchClient = algoliasearch('WCE34U42S7', '9bf1ba721d34be73279c688e50e94472')
	const algolia = instantsearch({
		indexName: 'tempestphp',
		searchClient,
		future: {
			preserveSharedStateOnUnmount: true,
		},
	})

	algolia.addWidgets([
		connectSearchBox(({ query, refine }, isFirstRender) => {
			if (isFirstRender) {
				_refine = refine
			}

			// input.value = query
		})({}),
		connectHits((data) => {
			const grouped: Record<string, GroupedResults> = {}

			data.items.forEach((result: AlgoliaHit) => {
				const category = result.hierarchy.lvl0 || 'Other'
				const firstLevel = result.hierarchy.lvl1
				const secondLevel = result.hierarchy.lvl2

				if (!firstLevel) {
					return
				}

				if (!grouped[category]) {
					grouped[category] = {
						title: category,
						items: [],
						id: result.objectID,
					}
				}

				let firstLevelEntry = grouped[category].items.find((item) => item.title === firstLevel)

				if (!firstLevelEntry) {
					firstLevelEntry = {
						title: firstLevel,
						url: result.url,
						anchor: result.anchor,
						children: [],
						id: result.objectID,
					}

					grouped[category].items.push(firstLevelEntry)
				}

				if (secondLevel) {
					firstLevelEntry.children.push({
						title: secondLevel,
						url: result.url,
						anchor: result.anchor,
						id: result.objectID,
					})
				}
			})

			results.value = Object.values(grouped)
			console.log({
				hits: data.items,
				results: results.value,
			})
		})({}),
	])

	algolia.start()

	return {
		refine,
		results,
	}
}
