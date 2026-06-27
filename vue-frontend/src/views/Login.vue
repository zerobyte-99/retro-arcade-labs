<template>
  <div class="login">
    <div class="card" style="max-width:400px;margin:100px auto;">
      <h1>🎮 LOGIN 🎮</h1>
      <div v-if="error" class="error">{{ error }}</div>
      <form @submit.prevent="doLogin">
        <div class="form-group">
          <label>Username</label>
          <input v-model="username" type="text" required />
        </div>
        <div class="form-group">
          <label>Password</label>
          <input v-model="password" type="password" required />
        </div>
        <button type="submit" class="btn" style="width:100%">INSERT COIN</button>
      </form>
      <p style="margin-top:20px;text-align:center;">
        <router-link to="/register">No account? Register</router-link>
      </p>
      <p style="margin-top:10px;color:#666;font-size:0.8em;text-align:center;">
        💡 Try: admin' -- (SQL Injection)
      </p>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const username = ref('')
const password = ref('')
const error = ref('')
const router = useRouter()

const doLogin = async () => {
  try {
    const res = await axios.post('/api/auth/login.php', {
      username: username.value,
      password: password.value
    })
    if (res.data.success) {
      localStorage.setItem('user', JSON.stringify(res.data.user))
      router.push('/dashboard')
    }
  } catch (e) {
    error.value = 'Invalid credentials'
  }
}
</script>

<style scoped>
.form-group { margin-bottom: 15px; }
.form-group label { display: block; margin-bottom: 5px; color: var(--neon-yellow); }
.form-group input { width: 100%; padding: 10px; background: var(--bg-dark); border: 1px solid var(--neon-cyan); color: var(--neon-cyan); }
.error { color: #ff3333; background: #330000; padding: 10px; margin-bottom: 15px; text-align: center; }
</style>
