<script setup>
import ProductTable from './components/ProductTable.vue'
</script>

<template>
  <main style="max-width:900px; margin:40px auto; padding:0 16px;">
    <ProductTable />
  </main>
</template>

<style scoped>
.logo {
  height: 6em;
  padding: 1.5em;
  will-change: filter;
  transition: filter 300ms;
}
.logo:hover {
  filter: drop-shadow(0 0 2em #646cffaa);
}
.logo.vue:hover {
  filter: drop-shadow(0 0 2em #42b883aa);
}
</style>
