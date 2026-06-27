<template>
  <div id="app">
    <nav class="nav">
      <div class="nav-brand">🎮 RETRO ARCADE LABS</div>
      <div class="nav-links">
        <router-link to="/">Home</router-link>
        <router-link to="/products">Products</router-link>
        <router-link to="/cart">Cart</router-link>
        <router-link v-if="!isLoggedIn" to="/login">Login</router-link>
        <router-link v-if="isLoggedIn" to="/dashboard">Dashboard</router-link>
      </div>
    </nav>
    <router-view />
    <div class="scanlines"></div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'

const isLoggedIn = computed(() => !!localStorage.getItem('user'))
</script>

<style>
/* Retro Arcade Theme */
:root {
  --bg-dark: #0a0a0f;
  --bg-card: #1a1a2e;
  --neon-cyan: #00fff5;
  --neon-magenta: #ff00ff;
  --neon-yellow: #ffff00;
}

body {
  background: var(--bg-dark);
  color: var(--neon-cyan);
  font-family: 'Courier New', monospace;
  margin: 0;
}

.nav {
  background: var(--bg-card);
  padding: 20px;
  border-bottom: 2px solid var(--neon-magenta);
  display: flex;
  justify-content: space-between;
}

.nav-brand { font-weight: bold; color: var(--neon-magenta); }
.nav-links a { color: var(--neon-cyan); margin-left: 20px; text-decoration: none; }
.nav-links a:hover { color: var(--neon-magenta); }

.scanlines {
  position: fixed;
  top: 0; left: 0;
  width: 100%; height: 100%;
  background: repeating-linear-gradient(0deg, rgba(0,0,0,0.1), rgba(0,0,0,0.1) 1px, transparent 1px, transparent 2px);
  pointer-events: none;
  z-index: 1000;
}

.card {
  background: var(--bg-card);
  border: 2px solid var(--neon-cyan);
  padding: 20px;
  margin: 20px;
  border-radius: 10px;
}

.btn {
  background: var(--neon-magenta);
  color: var(--bg-dark);
  padding: 10px 20px;
  border: none;
  cursor: pointer;
  border-radius: 5px;
}

.btn:hover { background: var(--neon-cyan); }
</style>
