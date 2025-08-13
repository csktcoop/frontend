import { createRouter, createWebHistory } from 'vue-router'
import ProductListView from '../views/ProductListView.vue'

const routes = [
  { path: '/', name: 'products', component: ProductListView }
]

export default createRouter({
  history: createWebHistory(),
  routes
})