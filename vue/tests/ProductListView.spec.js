// Component tests: filtering and pagination behavior.
// We mount the view and control store state for deterministic checks.
import { mount } from '@vue/test-utils'
import { createTestingPinia } from '@pinia/testing'
import { createRouter, createWebHistory } from 'vue-router'
import ProductListView from '../src/views/ProductListView.vue'
import { useProductsStore } from '../src/stores/products'

// Helper to create a router for RouterView (even if not used much here)
function makeRouter() {
  return createRouter({
    history: createWebHistory(),
    routes: [{ path: '/', component: ProductListView }]
  })
}

describe('ProductListView', () => {
  it('shows products and filters by category', async () => {
    const pinia = createTestingPinia({ stubActions: false })
    const router = makeRouter()
    const wrapper = mount(ProductListView, {
      global: { plugins: [pinia, router] }
    })

    const store = useProductsStore()
    // Speed up simulated fetch
    vi.useFakeTimers()
    const p = store.fetchProducts()
    vi.runAllTimers()
    await p
    await wrapper.vm.$nextTick()

    // Initially shows items
    expect(wrapper.text()).toContain('Products')
    expect(store.items.length).toBeGreaterThan(0)

    // Choose first category
    const firstCategory = store.categories[0]
    const select = wrapper.get('#category')
    await select.setValue(firstCategory)

    // All rendered cards should match that category
    const cards = wrapper.findAllComponents({ name: 'ProductCard' })
    expect(cards.length).toBeGreaterThan(0)
    for (const c of cards) {
      expect(c.props('product').category).toBe(firstCategory)
    }
  })

  it('applies pagination when page changes', async () => {
    const pinia = createTestingPinia({ stubActions: false })
    const router = makeRouter()
    const wrapper = mount(ProductListView, {
      global: { plugins: [pinia, router] }
    })

    const store = useProductsStore()
    vi.useFakeTimers()
    const p = store.fetchProducts()
    vi.runAllTimers()
    await p
    await wrapper.vm.$nextTick()

    // Default page size is 6
    const firstPageCount = wrapper.findAllComponents({ name: 'ProductCard' }).length
    expect(firstPageCount).toBeLessThanOrEqual(6)

    // Click Next →
    const nextBtn = wrapper.get('button:contains("Next")') // fallback: query by text
    await nextBtn.trigger('click')
    await wrapper.vm.$nextTick()

    // Page 2 should also render <= 6 items
    const secondPageCount = wrapper.findAllComponents({ name: 'ProductCard' }).length
    expect(secondPageCount).toBeLessThanOrEqual(6)
  })
})
