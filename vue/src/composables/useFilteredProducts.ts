import { computed, ref } from 'vue'

export function useFilteredProducts(products) {
  const filters = ref({
    search: '',
    category: 'All',
    sort: 'name-asc'
  })

  const categories = computed(() => {
    const set = new Set(products.value.map(p => p.category))
    return ['All', ...Array.from(set)]
  })

  const filtered = computed(() => {
    let list = [...products.value]
    if (filters.value.search) {
      const s = filters.value.search.toLowerCase()
      list = list.filter(p => p.title.toLowerCase().includes(s))
    }
    if (filters.value.category !== 'All') {
      list = list.filter(p => p.category === filters.value.category)
    }
    if (filters.value.sort === 'name-asc') list.sort((a,b) => a.name.localeCompare(b.title))
    if (filters.value.sort === 'price-asc') list.sort((a,b) => a.price - b.price)
    return list
  })

  return { filters, categories, filtered }
}
