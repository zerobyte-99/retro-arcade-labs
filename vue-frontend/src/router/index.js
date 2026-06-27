import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  { path: '/', component: () => import('../views/Home.vue') },
  { path: '/products', component: () => import('../views/Products.vue') },
  { path: '/products/:id', component: () => import('../views/ProductDetail.vue') },
  { path: '/login', component: () => import('../views/Login.vue') },
  { path: '/cart', component: () => import('../views/Cart.vue') },
  { path: '/dashboard', component: () => import('../views/Dashboard.vue') },
  { path: '/games/player', component: () => import('../views/GamePlayer.vue') },
]

export default createRouter({
  history: createWebHistory(),
  routes
})
