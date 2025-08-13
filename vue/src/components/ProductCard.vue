<template>
  <article class="card panel" :aria-label="product.name">
    <header class="card__header">
      <h3 class="card__title">{{ product.name }}</h3>
      <span class="card__category">{{ product.category }}</span>
    </header>
    <div class="card__body">
      <div class="price"><span class="muted">Price:</span> ${{ product.price.toFixed(2) }}</div>
      <div class="stock" :class="{ out: product.stock === 0 }">
        <span class="muted">Stock:</span> {{ product.stock === 0 ? 'Out of stock' : product.stock }}
      </div>
    </div>
  </article>
</template>

<script setup>
const props = defineProps({
  product: {
    type: Object,
    required: true
  }
})
</script>

<style scoped>
.card { padding: .9rem; display: flex; flex-direction: column; gap: .4rem; }
.card__header { display: flex; align-items: baseline; justify-content: space-between; gap: .6rem; }
.card__title { margin: 0; font-size: 1rem; }
.card__category { font-size: .8rem; color: var(--muted); }
.card__body { display: grid; gap: .3rem; font-size: .95rem; }
.price .muted, .stock .muted { color: var(--muted); margin-right: .3rem; }
.stock.out { color: #fca5a5; }
</style>
