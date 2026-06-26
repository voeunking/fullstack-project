<script setup lang="ts">
import { ref, reactive, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

type AuthMode = 'login' | 'register'
type Role = 'user' | 'admin'

const router = useRouter()
const route = useRoute()
const mode = ref<AuthMode>('login')
const role = ref<Role>('user')
const loading = ref(false)
const message = ref('')
const error = ref('')

const form = reactive({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
})

const backendBaseUrl = (import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000').replace(/\/$/, '')

const toggleMode = () => {
  mode.value = mode.value === 'login' ? 'register' : 'login'
  message.value = ''
  error.value = ''
}

const submitForm = async () => {
  if (mode.value === 'register' && form.password !== form.confirmPassword) {
    error.value = 'Passwords do not match.'
    return
  }

  loading.value = true
  message.value = ''
  error.value = ''

  try {
    const endpoint = `${backendBaseUrl}/api/${mode.value}`
    const payload = mode.value === 'login'
      ? { email: form.email, password: form.password }
      : { name: form.name, email: form.email, password: form.password, role: role.value }

    const response = await fetch(endpoint, {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', Accept: 'application/json' },
      body: JSON.stringify(payload),
    })

    const data = await response.json()
    if (!response.ok) {
      throw new Error(data.message || `Unable to ${mode.value}`)
    }

    localStorage.setItem('token', data.token || '')
    localStorage.setItem('user', JSON.stringify(data.user || {}))

    if (data.user?.role === 'admin') {
      window.location.href = `${backendBaseUrl}/admin/login?token=${data.token}`
      return
    }

    router.push('/dashboard/products')
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Something went wrong.'
  } finally {
    loading.value = false
  }
}

onMounted(() => {
  const token = localStorage.getItem('token')
  console.log('App mounted, token present:', !!token)
  console.log('Current route:', route.path)
  if (token && route.path === '/') {
    router.push('/dashboard/products')
  }
})
</script>

<template>
  <router-view v-if="route.path.startsWith('/dashboard')" />

  <main v-else class="auth-page">
    <section class="auth-panel">
      <div class="auth-card">
        <div class="auth-header">
          <h1>Welcome to ShopHub</h1>
          <p>Your one-stop shop for everything</p>
        </div>

        <form @submit.prevent="submitForm" class="auth-form">
          <div class="field" v-if="mode === 'register'">
            <label for="name">Full name</label>
            <input id="name" v-model="form.name" type="text" placeholder="Jane Doe" />
          </div>

          <div class="field">
            <label for="email">Email</label>
            <input id="email" v-model="form.email" type="email" placeholder="you@example.com" />
          </div>

          <div class="field">
            <label for="password">Password</label>
            <input id="password" v-model="form.password" type="password" placeholder="••••••••" />
          </div>

          <div class="field" v-if="mode === 'register'">
            <label for="confirmPassword">Confirm password</label>
            <input id="confirmPassword" v-model="form.confirmPassword" type="password" placeholder="Repeat password" />
          </div>

          <div class="role-switcher" v-if="mode === 'register'">
            <button type="button" :class="role === 'user' ? 'active' : ''" @click="role = 'user'">User</button>
            <button type="button" :class="role === 'admin' ? 'active' : ''" @click="role = 'admin'">Admin</button>
          </div>

          <button class="submit-btn" type="submit" :disabled="loading">
            {{ loading ? 'Please wait...' : mode === 'login' ? 'Login' : 'Register' }}
          </button>
        </form>

        <button class="toggle-btn" @click="toggleMode">
          {{ mode === 'login' ? "Don't have an account? Register" : 'Already have an account? Login' }}
        </button>

        <p v-if="message" class="success-message">{{ message }}</p>
        <p v-if="error" class="error-message">{{ error }}</p>
      </div>
    </section>
  </main>
</template>

<style scoped>
.auth-page {
  min-height: 100vh;
  background: linear-gradient(135deg, #eef5ff 0%, #f7fbf5 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 1.5rem;
}

.auth-panel {
  width: 100%;
  max-width: 480px;
}

.auth-card {
  background: white;
  border-radius: 1.5rem;
  padding: 2.5rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.03);
}

.auth-header {
  text-align: center;
  margin-bottom: 2rem;
}

.auth-header h1 {
  font-size: 1.75rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0 0 0.5rem;
}

.auth-header p {
  color: #64748b;
  font-size: 0.9375rem;
  margin: 0;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.field label {
  font-size: 0.875rem;
  font-weight: 600;
  color: #334155;
}

.field input {
  padding: 0.875rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.75rem;
  background: #f8fafc;
  font-size: 0.9375rem;
  transition: all 0.2s;
}

.field input:focus {
  outline: none;
  border-color: #3b82f6;
  background: white;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

.role-switcher {
  display: flex;
  gap: 0.5rem;
  background: #f1f5f9;
  padding: 0.25rem;
  border-radius: 0.75rem;
}

.role-switcher button {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0.625rem;
  border-radius: 0.625rem;
  font-weight: 600;
  font-size: 0.875rem;
  color: #64748b;
  cursor: pointer;
  transition: all 0.15s;
}

.role-switcher button.active {
  background: white;
  color: #0f172a;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.submit-btn {
  margin-top: 0.5rem;
  padding: 0.875rem;
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: white;
  border: none;
  border-radius: 0.75rem;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.submit-btn:hover:not(:disabled) {
  transform: scale(1.01);
  box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.toggle-btn {
  margin-top: 1rem;
  background: transparent;
  border: none;
  color: #3b82f6;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.875rem;
  width: 100%;
  text-align: center;
}

.toggle-btn:hover {
  text-decoration: underline;
}

.success-message {
  margin-top: 1rem;
  text-align: center;
  color: #059669;
  font-weight: 600;
  font-size: 0.875rem;
}

.error-message {
  margin-top: 1rem;
  text-align: center;
  color: #dc2626;
  font-weight: 600;
  font-size: 0.875rem;
}
</style>
