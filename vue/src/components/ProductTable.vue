<script setup>
import { ref, computed, watch, onMounted } from 'vue'
import { useFilteredProducts } from '../composables/useFilteredProducts'
import { useProductsStore } from '../stores/products'

const store = useProductsStore()

onMounted(() => {
  store.fetchProducts()
})
// const products = computed(() => store.items)
// const loading = computed(() => store.loading)
// const error = computed(() => store.error)

// Mock data (replace with fetch if have time)
const products = ref([
  { id: 1, name: 'Air Zoom', category: 'Shoes', price: 120, inStock: true },
  { id: 2, name: 'Travel Backpack', category: 'Bags', price: 80, inStock: false },
  { id: 3, name: 'Trail Runner', category: 'Shoes', price: 95, inStock: true },
  { id: 4, name: 'City Jacket', category: 'Apparel', price: 150, inStock: true },
  { id: 5, name: 'Everyday Tee', category: 'Apparel', price: 25, inStock: false }
])
const { filters, categories, filtered } = useFilteredProducts(products)

const loading = ref(false)
const error = ref(null)

// Optional: fetch later
// onMounted(async () => {
//   loading.value = true
//   try {
//     const res = await fetch('/api/products.json')
//     products.value = await res.json()
//   } catch (e) {
//     error.value = 'Failed to load products'
//   } finally {
//     loading.value = false
//   }
// })

// Pagination
const page = ref(1)
const perPage = ref(5)
const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / perPage.value)))

watch([filtered, perPage], () => {
  page.value = 1 // reset when filters or perPage change
})

const paginated = computed(() => {
  const start = (page.value - 1) * perPage.value
  return filtered.value.slice(start, start + perPage.value)
})

function toFirst() { page.value = 1 }
function toPrev() { page.value = Math.max(1, page.value - 1) }
function toNext() { page.value = Math.min(totalPages.value, page.value + 1) }
function toLast() { page.value = totalPages.value }
</script>

<template>
  <div class="product-table">
    <h2>Products</h2>

    <div class="controls" style="display:flex; gap:12px; margin:12px 0;">
      <input type="search" v-model="filters.search" placeholder="Search by name..." />
      <select v-model="filters.category">
        <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
      </select>
      <label style="display:flex; align-items:center; gap:6px;">
        <input type="checkbox" v-model="filters.inStockOnly" /> In stock only
      </label>
      <select v-model="filters.sort">
        <option value="name-asc">Name ↑</option>
        <option value="name-desc">Name ↓</option>
        <option value="price-asc">Price ↑</option>
        <option value="price-desc">Price ↓</option>
      </select>
      <label>
        Per page:
        <select v-model.number="perPage">
          <option :value="1">1</option>
          <option :value="2">2</option>
          <option :value="5">5</option>
          <option :value="10">10</option>
          <option :value="20">20</option>
        </select>
      </label>
    </div>

    <div v-if="loading">Loading…</div>
    <div v-else-if="error">{{ error }}</div>
    <template v-else>
      <table border="1" cellpadding="6" cellspacing="0" style="width:100%;">
        <thead>
          <tr>
            <th style="text-align:left;">Name</th>
            <th style="text-align:left;">Category</th>
            <th style="text-align:right;">Price</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="p in paginated" :key="p.id">
            <td>{{ p.name }}</td>
            <td>{{ p.category }}</td>
            <td style="text-align:right;">${{ p.price.toFixed(2) }}</td>
          </tr>
          <tr v-if="paginated.length === 0">
            <td colspan="3" style="text-align:center;">No results</td>
          </tr>
        </tbody>
      </table>

      <div class="pagination" style="display:flex; align-items:center; gap:8px; margin-top:12px;">
        <button @click="toFirst" :disabled="page === 1" aria-label="First page">« First</button>
        <button @click="toPrev" :disabled="page === 1" aria-label="Previous page">‹ Prev</button>
        <span>Page {{ page }} of {{ totalPages }}</span>
        <button @click="toNext" :disabled="page === totalPages" aria-label="Next page">Next ›</button>
        <button @click="toLast" :disabled="page === totalPages" aria-label="Last page">Last »</button>
      </div>
    </template>
  </div>
</template>
