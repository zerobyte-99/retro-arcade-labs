<template>
  <div class="products">
    <h1>🎮 ALL PRODUCTS 🎮</h1>
    <div class="search">
      <input v-model="search" placeholder="Search products..." @keyup.enter="doSearch" />
      <button @click="doSearch">🔍 SEARCH</button>
    </div>
    <div class="grid">
      <div v-for="p in products" :key="p.id" class="card">
        <h3>{{ p.name }}</h3>
        <p>{{ p.description }}</p>
        <div class="price">${{ p.price }}</div>
        <router-link :to="'/products/' + p.id" class="btn">View</router-link>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const products = ref([])
const search = ref('')
const route = useRoute()

const loadProducts = async () => {
  try {
    const res = await axios.get('/api/products/list.php')
    products.value = res.data.products || []
  } catch (e) { console.error(e) }
}

const doSearch = async () => {
  try {
    const res = await axios.get('/api/products/search.php?q=' + encodeURIComponent(search.value))
    products.value = res.data.products || []
  } catch (e) { console.error(e) }
}

onMounted(loadProducts)
</script>
