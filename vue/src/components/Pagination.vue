<template>
  <nav class="pagination" role="navigation" aria-label="Pagination">
    <button
      class="page-btn"
      :disabled="page <= 1"
      @click="$emit('update:page', page - 1)"
    >
      ← Prev
    </button>

    <span class="page-info">
      Page {{ page }} / {{ totalPages }}
    </span>

    <button
      class="page-btn"
      :disabled="page >= totalPages"
      @click="$emit('update:page', page + 1)"
    >
      Next →
    </button>

    <select
      class="page-size"
      :value="pageSize"
      @change="$emit('update:pageSize', Number($event.target.value))"
      aria-label="Items per page"
    >
      <option v-for="s in sizes" :key="s" :value="s">{{ s }} / page</option>
    </select>
  </nav>
</template>

<script setup>
defineProps({
  page: { type: Number, required: true },
  totalPages: { type: Number, required: true },
  pageSize: { type: Number, required: true },
  sizes: { type: Array, default: () => [6, 12, 24] }
})
defineEmits(['update:page', 'update:pageSize'])
</script>

<style scoped>
.pagination {
  display: flex; flex-wrap: wrap; gap: .6rem; align-items: center; justify-content: space-between;
  padding: .6rem; background: var(--panel); border: 1px solid var(--border); border-radius: .5rem;
}
.page-btn[disabled] { opacity: .5; cursor: not-allowed; }
.page-info { color: var(--muted); }
.page-size { margin-left: auto; }
@media (max-width: 480px) {
  .page-size { width: 100%; }
  .pagination { gap: .4rem; }
}
</style>
