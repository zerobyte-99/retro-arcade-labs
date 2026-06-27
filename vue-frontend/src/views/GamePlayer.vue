<template>
  <div class="game-player">
    <div class="card" style="max-width:800px;margin:50px auto;">
      <h1>🎮 GAME PLAYER 🎮</h1>
      <!-- LAB: VULN-XSS-006 - DOM XSS via v-html -->
      <div v-html="playerContent"></div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRoute } from 'vue-router'

const route = useRoute()
const playerContent = ref('')

onMounted(() => {
  // VULNERABLE: Reflecting URL param without sanitization
  // LAB: VULN-XSS-006
  const player = route.query.player || 'Guest Player'
  playerContent.value = `<h2>Current Player: ${player}</h2><p>High Score: 10000</p>`
})
</script>
