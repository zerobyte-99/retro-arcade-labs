<template>
  <div class="product-detail">
    <div class="card" style="max-width:800px;margin:50px auto;" v-if="product">
      <h1>{{ product.name }}</h1>
      <p>{{ product.description }}</p>
      <div class="price">${{ product.price }}</div>
      <button class="btn" @click="addToCart">🛒 ADD TO CART</button>
    </div>
    <div class="card" style="max-width:800px;margin:50px auto;">
      <h3>💬 Comments</h3>
      <textarea v-model="comment" placeholder="Write a review..." style="width:100%;height:80px;background:#0a0a0f;border:1px solid #00fff5;color:#00fff5;padding:10px;margin:10px 0;"></textarea>
      <button @click="submitComment" class="btn">Submit</button>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'
import { useRoute } from 'vue-router'

const route = useRoute()
const product = ref(null)
const comment = ref('')

const loadProduct = async () => {
  try {
    const res = await axios.get('/api/products/list.php')
    product.value = res.data.products.find(p => p.id == route.params.id)
  } catch (e) { console.error(e) }
}

const submitComment = async () => {
  try {
    await axios.post('/api/comments/', {
      product_id: route.params.id,
      body: comment.value
    })
    comment.value = ''
  } catch (e) { console.error(e) }
}

const addToCart = () => {
  window.location.href = '/pages/cart.php?action=add&id=' + route.params.id
}

onMounted(loadProduct)
</script>
