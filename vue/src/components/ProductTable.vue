<script setup>
import { ref, computed, onMounted } from 'vue'

// Mock data (replace with fetch if have time)
const products = ref([
  { id: 1, name: 'Air Zoom', category: 'Shoes', price: 120, inStock: true },
  { id: 2, name: 'Travel Backpack', category: 'Bags', price: 80, inStock: false },
  { id: 3, name: 'Trail Runner', category: 'Shoes', price: 95, inStock: true },
  { id: 4, name: 'City Jacket', category: 'Apparel', price: 150, inStock: true },
  { id: 5, name: 'Everyday Tee', category: 'Apparel', price: 25, inStock: false }
])

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

const filters = ref({
  search: '',
  category: 'All',
  inStockOnly: false,
  sort: 'name-asc' // name-asc | name-desc | price-asc | price-desc
})

const categories = computed(() => {
  const set = new Set(products.value.map(p => p.category))
  return ['All', ...Array.from(set)]
})

const filtered = computed(() => {
  const s = filters.value.search.trim().toLowerCase()
  const cat = filters.value.category
  const inStockOnly = filters.value.inStockOnly

  let list = products.value.filter(p => {
    const matchesSearch = !s || p.name.toLowerCase().includes(s)
    const matchesCat = cat === 'All' || p.category === cat
    const matchesStock = !inStockOnly || p.inStock
    return matchesSearch && matchesCat && matchesStock
  })

  switch (filters.value.sort) {
    case 'name-asc': list = list.sort((a,b) => a.name.localeCompare(b.name)); break
    case 'name-desc': list = list.sort((a,b) => b.name.localeCompare(a.name)); break
    case 'price-asc': list = list.sort((a,b) => a.price - b.price); break
    case 'price-desc': list = list.sort((a,b) => b.price - a.price); break
  }
  return list
})
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
    </div>

    <div v-if="loading">Loading…</div>
    <div v-else-if="error">{{ error }}</div>
    <table v-else border="1" cellpadding="6" cellspacing="0" style="width:100%;">
      <thead>
        <tr>
          <th style="text-align:left;">Name</th>
          <th style="text-align:left;">Category</th>
          <th style="text-align:right;">Price</th>
          <th style="text-align:center;">In Stock</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="p in filtered" :key="p.id">
          <td>{{ p.name }}</td>
          <td>{{ p.category }}</td>
          <td style="text-align:right;">${{ p.price }}</td>
          <td style="text-align:center;">{{ p.inStock ? '✓' : '—' }}</td>
        </tr>
        <tr v-if="filtered.length === 0">
          <td colspan="4" style="text-align:center; padding:12px;">No results</td>
        </tr>
      </tbody>
    </table>
  </div>
</template>
