<template>
  <main class="container">
    <h1 style="margin: 0 0 1rem 0;">Products</h1>

    <!-- Filters: small form grouped in a panel -->
    <section class="panel" style="padding: 1rem; margin-bottom: 1rem;">
      <form class="filters" @submit.prevent>
        <div class="field">
          <label for="search">Search</label>
          <input id="search" v-model="search" placeholder="Type product name..." />
        </div>

        <div class="field">
          <label for="category">Category</label>
          <select id="category" v-model="category">
            <option value="">All</option>
            <option v-for="c in categories" :key="c" :value="c">{{ c }}</option>
          </select>
        </div>

        <div class="field row">
          <div>
            <label for="minPrice">Min price</label>
            <input id="minPrice" type="number" min="0" step="0.1" v-model.number="minPrice" />
          </div>
          <div>
            <label for="maxPrice">Max price</label>
            <input id="maxPrice" type="number" min="0" step="0.1" v-model.number="maxPrice" />
          </div>
        </div>

        <div class="actions">
          <button class="primary" type="button" @click="resetFilters">Reset</button>
        </div>
      </form>
    </section>

    <!-- List + pagination -->
    <section class="grid">
      <ProductCard v-for="p in paginated" :key="p.id" :product="p" />
    </section>

    <footer style="margin-top: 1rem;">
      <Pagination
        v-if="status === 'success' && totalPages > 0"
        :page="page"
        :totalPages="totalPages"
        :pageSize="pageSize"
        :sizes="[6, 12, 24]"
        @update:page="page = $event"
        @update:pageSize="onChangePageSize"
      />
    </footer>

    <!-- Loading & empty states -->
    <p v-if="status === 'loading'">Loading products…</p>
    <p v-if="status === 'success' && filtered.length === 0">No products match your filters.</p>
    <p v-if="status === 'error'">Could not load products.</p>
  </main>
</template>

<script setup>
// This view composes store data + local UI state (filters/pagination).
// YAGNI: we don’t push filters into the store until we truly need global sync.
import { computed, onMounted, ref, watch } from 'vue'
import { storeToRefs } from 'pinia'
import { useProductsStore } from '../stores/products'
import ProductCard from '../components/ProductCard.vue'
import Pagination from '../components/Pagination.vue'

const store = useProductsStore()
const { items, status, categories } = storeToRefs(store)

// Filters (local state)
const search = ref('')
const category = ref('')
const minPrice = ref(null)
const maxPrice = ref(null)

// Pagination (local state)
const page = ref(1)
const pageSize = ref(6)

// Fetch on mount
onMounted(() => {
  store.fetchProducts()
})

// Compute filtered products based on current filters.
// Each filter is optional; we short-circuit when possible to keep it simple.
const filtered = computed(() => {
  const s = search.value.trim().toLowerCase()
  const cat = category.value
  const min = Number.isFinite(minPrice.value) ? minPrice.value : -Infinity
  const max = Number.isFinite(maxPrice.value) ? maxPrice.value : Infinity

  return items.value.filter(p => {
    if (s && !p.name.toLowerCase().includes(s)) return false
    if (cat && p.category !== cat) return false
    if (!(p.price >= min && p.price <= max)) return false
    return true
  })
})

// Keep page within valid range when filters change
watch(filtered, () => { page.value = 1 })

// Compute paginated slice and total pages
const totalPages = computed(() => Math.max(1, Math.ceil(filtered.value.length / pageSize.value)))
const paginated = computed(() => {
  const start = (page.value - 1) * pageSize.value
  return filtered.value.slice(start, start + pageSize.value)
})

// Reset filters to initial state
function resetFilters () {
  search.value = ''
  category.value = ''
  minPrice.value = null
  maxPrice.value = null
  page.value = 1
}

function onChangePageSize (size) {
  pageSize.value = size
  page.value = 1
}
</script>

<style scoped>
/* Responsive grid: auto-fit makes cards wrap nicely on small screens */
.grid {
  display: grid;
  gap: var(--gap);
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
}

/* Simple filter layout that collapses to one column on small screens */
.filters {
  display: grid;
  gap: .8rem;
  grid-template-columns: repeat(4, 1fr);
}
.field { display: grid; gap: .3rem; }
.field.row { grid-template-columns: 1fr 1fr; gap: .8rem; }
.actions { align-self: end; justify-self: end; }

@media (max-width: 900px) {
  .filters { grid-template-columns: 1fr 1fr; }
  .actions { justify-self: start; }
}
@media (max-width: 520px) {
  .filters { grid-template-columns: 1fr; }
  .field.row { grid-template-columns: 1fr; }
}
</style>
