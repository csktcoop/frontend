// Store tests validate data fetching behavior and derived categories.
// We use fake timers to skip the artificial delay.
import { setActivePinia, createPinia } from 'pinia'
import { useProductsStore } from '../src/stores/products'

describe('products store', () => {
  beforeEach(() => {
    setActivePinia(createPinia())
    vi.useFakeTimers()
  })

  afterEach(() => {
    vi.useRealTimers()
  })

  it('fetches products and sets status', async () => {
    const store = useProductsStore()
    const p = store.fetchProducts()
    expect(store.status).toBe('loading')

    // Fast-forward timer and await the promise
    vi.runAllTimers()
    await p

    expect(store.status).toBe('success')
    expect(store.items.length).toBeGreaterThan(0)
  })

  it('computes unique categories', async () => {
    const store = useProductsStore()
    const p = store.fetchProducts()
    vi.runAllTimers()
    await p

    expect(store.categories.length).toBeGreaterThan(0)
    // All categories should be unique
    const set = new Set(store.categories)
    expect(set.size).toBe(store.categories.length)
  })
})
