<script setup lang="ts">
import { onMounted, reactive, ref } from 'vue'

type AuthMode = 'login' | 'register'
type Role = 'user' | 'admin'

type Product = {
  id: number
  name: string
  price: number
  description?: string
  image?: string
}

const mode = ref<AuthMode>('login')
const role = ref<Role>('user')
const loading = ref(false)
const message = ref('')
const error = ref('')
const isLoggedIn = ref(false)
const showShop = ref(false)
const products = ref<Product[]>([])
const cartCount = ref(0)
const backendBaseUrl = (import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000').replace(/\/$/, '')

const form = reactive({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
})

const toggleMode = () => {
  mode.value = mode.value === 'login' ? 'register' : 'login'
  message.value = ''
  error.value = ''
}

const fetchProducts = async () => {
  try {
    const response = await fetch(`${backendBaseUrl}/api/products`)
    const data = await response.json()
    const list = Array.isArray(data) ? data : data.data || []
    products.value = list
  } catch (err) {
    console.error(err)
  }
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
    const endpoint = `/api/${mode.value}`
    const payload = mode.value === 'login'
      ? {
          email: form.email,
          password: form.password,
        }
      : {
          name: form.name,
          email: form.email,
          password: form.password,
          role: role.value,
        }

    const response = await fetch(endpoint, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
      },
      body: JSON.stringify(payload),
    })

    const data = await response.json()

    if (!response.ok) {
      throw new Error(data.message || `Unable to ${mode.value}`)
    }

    localStorage.setItem('token', data.token || '')
    localStorage.setItem('user', JSON.stringify(data.user || {}))

    message.value = `${role.value === 'admin' ? 'Admin' : 'User'} ${mode.value} successful.`
    isLoggedIn.value = true

    if (data.user?.role === 'admin') {
      window.location.href = `${backendBaseUrl}/admin/dashboard`
      return
    }

    showShop.value = true
    await fetchProducts()
  } catch (err) {
    error.value = err instanceof Error ? err.message : 'Something went wrong.'
  } finally {
    loading.value = false
  }
}

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  isLoggedIn.value = false
  showShop.value = false
  message.value = ''
  error.value = ''
}

onMounted(() => {
  const savedUser = localStorage.getItem('user')
  if (savedUser) {
    isLoggedIn.value = true
    showShop.value = JSON.parse(savedUser).role !== 'admin'
    if (showShop.value) {
      fetchProducts()
    }
  }
})
</script>

<template>
  <main class="page-shell">
    <section v-if="!showShop" class="auth-panel">
      <div class="auth-card">
        <div class="auth-header">
          <p class="eyebrow">Welcome</p>
          <h1>{{ mode === 'login' ? 'Login' : 'Register' }}</h1>
        </div>

        <div class="role-switcher">
          <button :class="role === 'user' ? 'active' : ''" @click="role = 'user'">User</button>
          <button :class="role === 'admin' ? 'active' : ''" @click="role = 'admin'">Admin</button>
        </div>

        <form @submit.prevent="submitForm">
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

          <button class="submit-btn" type="submit" :disabled="loading">
            {{ loading ? 'Please wait...' : mode === 'login' ? 'Login' : 'Register' }}
          </button>
        </form>

        <button class="toggle-btn" @click="toggleMode">
          {{ mode === 'login' ? 'Create an account' : 'Back to login' }}
        </button>

        <p v-if="message" class="success-message">{{ message }}</p>
        <p v-if="error" class="error-message">{{ error }}</p>
      </div>
    </section>

    <section v-else class="shop-panel">
      <header class="shop-header">
        <div>
          <p class="eyebrow">Shop</p>
          <h2>Featured Products</h2>
        </div>
        <div class="shop-actions">
          <span class="cart-pill">Cart: {{ cartCount }}</span>
          <button class="logout-btn" @click="logout">Logout</button>
        </div>
      </header>

      <div class="product-grid">
        <article v-for="product in products" :key="product.id" class="product-card">
          <div class="product-image">{{ product.name.charAt(0) }}</div>
          <h3>{{ product.name }}</h3>
          <p>{{ product.description || 'Great product for your daily needs.' }}</p>
          <div class="product-footer">
            <strong>${{ product.price }}</strong>
            <button @click="cartCount++">Add to cart</button>
          </div>
        </article>
      </div>
    </section>
  </main>
</template>

<style scoped>
.page-shell {
  min-height: 100vh;
  background: linear-gradient(135deg, #eef5ff, #f7fbf5);
  padding: 2rem;
}

.auth-panel {
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
}

.auth-card {
  width: min(460px, 100%);
  background: #fff;
  border-radius: 18px;
  padding: 2rem;
  box-shadow: 0 18px 45px rgba(15, 23, 42, 0.08);
}

.auth-header h1 {
  margin: 0;
  font-size: 2rem;
  color: #0f172a;
}

.role-switcher {
  display: flex;
  gap: 0.5rem;
  margin: 1rem 0;
}

.role-switcher button {
  flex: 1;
  border: 1px solid #cbd5e1;
  background: #fff;
  padding: 0.7rem;
  border-radius: 10px;
  cursor: pointer;
}

.role-switcher button.active {
  background: #0f172a;
  color: #fff;
}

form {
  display: flex;
  flex-direction: column;
  gap: 0.9rem;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 0.4rem;
}

.field label {
  font-size: 0.9rem;
  font-weight: 600;
  color: #334155;
}

.field input {
  padding: 0.8rem 0.9rem;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  outline: none;
}

.field input:focus {
  border-color: #4f46e5;
  box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.08);
}

.submit-btn {
  background: #0f172a;
  color: #fff;
  border: 0;
  border-radius: 10px;
  padding: 0.9rem;
  font-weight: 700;
  cursor: pointer;
}

.toggle-btn {
  margin-top: 0.9rem;
  background: transparent;
  border: 0;
  color: #2563eb;
  cursor: pointer;
  font-weight: 600;
}

.success-message {
  margin-top: 0.9rem;
  color: #0f9f6e;
  font-weight: 600;
}

.error-message {
  margin-top: 0.9rem;
  color: #dc2626;
  font-weight: 600;
}

.shop-panel {
  width: min(1200px, 100%);
  margin: 0 auto;
}

.shop-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
}

.shop-actions {
  display: flex;
  gap: 0.75rem;
  align-items: center;
}

.cart-pill {
  background: #eef2ff;
  color: #1d4ed8;
  padding: 0.6rem 0.9rem;
  border-radius: 999px;
  font-weight: 700;
}

.logout-btn {
  background: #111827;
  color: #fff;
  border: 0;
  border-radius: 10px;
  padding: 0.7rem 1rem;
  cursor: pointer;
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 1rem;
}

.product-card {
  background: #fff;
  padding: 1rem;
  border-radius: 16px;
  box-shadow: 0 12px 32px rgba(15, 23, 42, 0.06);
}

.product-image {
  background: #e2e8f0;
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 12px;
  font-size: 2rem;
  font-weight: 700;
  color: #334155;
  margin-bottom: 0.75rem;
}

.product-card h3 {
  margin: 0;
}

.product-card p {
  color: #64748b;
  min-height: 48px;
}

.product-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: 0.8rem;
}

.product-footer button {
  background: #2563eb;
  color: #fff;
  border: 0;
  border-radius: 10px;
  padding: 0.6rem 0.8rem;
  cursor: pointer;
}
</style>
