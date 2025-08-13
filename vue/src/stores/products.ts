import { defineStore } from 'pinia'

const MOCK_PRODUCTS = [
  { id: 1, name: 'Green Tea', category: 'Beverage', price: 2.5, stock: 10 },
  { id: 2, name: 'Black Coffee', category: 'Beverage', price: 3.2, stock: 0 },
  { id: 3, name: 'Almonds 200g', category: 'Grocery', price: 5.9, stock: 25 },
  { id: 4, name: 'Dark Chocolate', category: 'Grocery', price: 3.5, stock: 12 },
  { id: 5, name: 'Orange Juice', category: 'Beverage', price: 2.9, stock: 5 },
  { id: 6, name: 'Olive Oil 500ml', category: 'Grocery', price: 8.5, stock: 8 },
  { id: 7, name: 'Granola', category: 'Grocery', price: 4.2, stock: 15 },
  { id: 8, name: 'Herbal Tea', category: 'Beverage', price: 3.0, stock: 20 },
  { id: 9, name: 'Pasta 1kg', category: 'Grocery', price: 2.1, stock: 9 },
  { id: 10, name: 'Mineral Water', category: 'Beverage', price: 1.0, stock: 100 },
  { id: 11, name: 'Tomato Sauce', category: 'Grocery', price: 1.8, stock: 14 },
  { id: 12, name: 'Peanut Butter', category: 'Grocery', price: 3.9, stock: 6 }
]

export const useProductsStore = defineStore('products', {
  state: () => ({
    items: [],
    status: 'idle', // 'idle' | 'loading' | 'success' | 'error'
    error: null
  }),
  getters: {
    // Unique categories derived from items (for the category filter).
    categories: (state) => {
      const set = new Set(state.items.map(p => p.category))
      return Array.from(set).sort()
    }
  },
  actions: {
    async fetchProducts () {
      this.status = 'loading'
      this.error = null
      try {
        // Simulate a small network delay
        await new Promise(res => setTimeout(res, 200))
        // In real life, you'd call your API here.
        // e.g., const { data } = await axios.get('/api/products')
        this.items = MOCK_PRODUCTS
        this.status = 'success'
      } catch (e) {
        this.error = 'Failed to load products'
        this.status = 'error'
      }
    }
  }
})

