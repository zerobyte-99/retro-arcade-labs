<template>
  <div class="home">
    <div class="hero">
      <h1>🎮 INSERT COIN TO CONTINUE 🎮</h1>
      <p>Welcome to the ultimate cyberpunk marketplace</p>
      <router-link to="/products" class="btn">BROWSE GAMES</router-link>
    </div>
    <div class="products">
      <h2>🔥 FEATURED PRODUCTS 🔥</h2>
      <div class="grid">
        <div v-for="product in products" :key="product.id" class="card">
          <h3>{{ product.name }}</h3>
          <p>{{ product.description }}</p>
          <div class="price">${{ product.price }}</div>
          <router-link :to="'/products/' + product.id" class="btn">View</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const products = ref([])

onMounted(async () => {
  try {
    const res = await axios.get('/api/products/list.php')
    products.value = res.data.products || []
  } catch (e) {
    console.error('Failed to load products')
  }
})
</script>
