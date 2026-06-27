<template>
  <div class="dashboard">
    <div class="card" style="max-width:800px;margin:50px auto;">
      <h1>🎮 WELCOME BACK!</h1>
      <p>User: {{ user?.username }}</p>
      <p>Role: {{ user?.role }}</p>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
const user = computed(() => JSON.parse(localStorage.getItem('user') || '{}'))
</script>
