// src/stores/products.ts
import { defineStore } from 'pinia'
import { ref } from 'vue'

export const useProducts = defineStore('products', () => {
  const items = ref([])
  async function load() {
    const res = await fetch('/api/products.json')
    items.value = await res.json()
  }
  return { items, load }
})
