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
    <div class="auth-bg" aria-hidden="true"></div>

    <section class="auth-panel">
      <div class="auth-grid">
        <div class="auth-hero">
          <div class="hero-badge">ShopHub</div>
          <h1>Welcome back</h1>
          <p>Fast login, smooth checkout, and a clean dashboard experience.</p>

          <div class="hero-cards">
            <div class="hero-card">
              <div class="hero-card-icon">⚡</div>
              <div>
                <p class="hero-card-title">Quick</p>
                <p class="hero-card-sub">Sign in in seconds</p>
              </div>
            </div>
            <div class="hero-card">
              <div class="hero-card-icon">🔒</div>
              <div>
                <p class="hero-card-title">Secure</p>
                <p class="hero-card-sub">Token-based access</p>
              </div>
            </div>
            <div class="hero-card">
              <div class="hero-card-icon">🛍️</div>
              <div>
                <p class="hero-card-title">Shop</p>
                <p class="hero-card-sub">Products & orders</p>
              </div>
            </div>
          </div>
        </div>

        <div class="auth-card">
          <div class="auth-header">
            <h1>{{ mode === 'login' ? 'Sign in' : 'Create account' }}</h1>
            <p>{{ mode === 'login' ? 'Use your email to continue.' : 'Start your ShopHub journey.' }}</p>
          </div>

          <form @submit.prevent="submitForm" class="auth-form">
            <div class="field" v-if="mode === 'register'">
              <label for="name">Full name</label>
              <input id="name" v-model="form.name" type="text" placeholder="Jane Doe" autocomplete="name" />
            </div>

            <div class="field">
              <label for="email">Email</label>
              <input id="email" v-model="form.email" type="email" placeholder="you@example.com" autocomplete="email" />
            </div>

            <div class="field">
              <label for="password">Password</label>
              <input id="password" v-model="form.password" type="password" placeholder="••••••••" autocomplete="current-password" />
            </div>

            <div class="field" v-if="mode === 'register'">
              <label for="confirmPassword">Confirm password</label>
              <input id="confirmPassword" v-model="form.confirmPassword" type="password" placeholder="Repeat password" autocomplete="new-password" />
            </div>

            <div class="role-switcher" v-if="mode === 'register'">
              <button type="button" :class="role === 'user' ? 'active' : ''" @click="role = 'user'">User</button>
              <button type="button" :class="role === 'admin' ? 'active' : ''" @click="role = 'admin'">Admin</button>
            </div>

            <button class="submit-btn" type="submit" :disabled="loading">
              {{ loading ? 'Please wait...' : mode === 'login' ? 'Login' : 'Register' }}
            </button>

            <p v-if="message" class="success-message">{{ message }}</p>
            <p v-if="error" class="error-message">{{ error }}</p>
          </form>

          <button class="toggle-btn" @click="toggleMode">
            {{ mode === 'login' ? "Don't have an account? Register" : 'Already have an account? Login' }}
          </button>

          <div class="auth-foot">
            <span>By continuing you agree to our</span>
            <a href="#" @click.prevent>Terms</a>
            <span>and</span>
            <a href="#" @click.prevent>Privacy</a>
          </div>
        </div>
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
  position: relative;
}

.auth-bg {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at 18% 10%, rgba(20, 184, 166, 0.20), transparent 35rem),
    radial-gradient(circle at 85% 18%, rgba(249, 115, 22, 0.16), transparent 32rem);
  pointer-events: none;
}

.auth-panel {
  width: 100%;
  max-width: 980px;
  position: relative;
  z-index: 1;
}

.auth-grid {
  display: grid;
  grid-template-columns: 1.05fr 0.95fr;
  gap: 1.5rem;
  align-items: stretch;
}

@media (max-width: 920px) {
  .auth-grid {
    grid-template-columns: 1fr;
  }
}

.auth-hero {
  background: rgba(255, 255, 255, 0.65);
  border: 1px solid rgba(226, 232, 240, 0.85);
  border-radius: 1.25rem;
  padding: 2.25rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.06);
  backdrop-filter: blur(14px);
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.35rem 0.75rem;
  border-radius: 999px;
  background: rgba(15, 118, 110, 0.12);
  color: #0f766e;
  font-weight: 950;
  margin-bottom: 1rem;
}

.auth-hero h1 {
  margin: 0;
  font-size: 2.05rem;
  font-weight: 950;
  color: #0f172a;
  line-height: 1.15;
}

.auth-hero p {
  margin: 0.85rem 0 1.5rem;
  color: #64748b;
  line-height: 1.6;
  font-weight: 600;
}

.hero-cards {
  display: grid;
  grid-template-columns: 1fr;
  gap: 0.75rem;
}

.hero-card {
  display: flex;
  gap: 0.85rem;
  align-items: center;
  padding: 0.95rem;
  border-radius: 1rem;
  background: rgba(255, 255, 255, 0.85);
  border: 1px solid rgba(226, 232, 240, 0.75);
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.hero-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 18px 35px rgba(15, 23, 42, 0.10);
}

.hero-card-icon {
  width: 40px;
  height: 40px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, rgba(15, 118, 110, 0.18), rgba(249, 115, 22, 0.14));
  font-size: 1.1rem;
}

.hero-card-title {
  margin: 0;
  font-weight: 950;
  color: #0f172a;
}

.hero-card-sub {
  margin: 0;
  color: #64748b;
  font-weight: 650;
  font-size: 0.9rem;
}

.auth-card {
  background: white;
  border-radius: 1.25rem;
  padding: 2rem;
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.08), 0 0 0 1px rgba(0, 0, 0, 0.03);
}

.auth-header h1 {
  font-size: 1.6rem;
  font-weight: 950;
  margin: 0;
  color: #0f172a;
}

.auth-header p {
  margin: 0.5rem 0 1.25rem;
  color: #64748b;
  font-weight: 650;
  line-height: 1.5;
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
  font-weight: 700;
  color: #334155;
}

.field input {
  padding: 0.9rem 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.85rem;
  background: #f8fafc;
  font-size: 0.95rem;
  transition: all 0.2s;
  font-weight: 650;
}

.field input:focus {
  outline: none;
  border-color: #0f766e;
  background: white;
  box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.14);
}

.role-switcher {
  display: flex;
  gap: 0.5rem;
  background: #f1f5f9;
  padding: 0.25rem;
  border-radius: 0.85rem;
}

.role-switcher button {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0.7rem;
  border-radius: 0.75rem;
  font-weight: 800;
  font-size: 0.9rem;
  color: #64748b;
  cursor: pointer;
  transition: all 0.15s;
}

.role-switcher button.active {
  background: linear-gradient(135deg, rgba(15, 118, 110, 0.15), rgba(249, 115, 22, 0.12));
  color: #0f172a;
  box-shadow: 0 10px 22px rgba(15, 23, 42, 0.08);
  border: 1px solid rgba(15, 118, 110, 0.20);
}

.submit-btn {
  margin-top: 0.5rem;
  padding: 0.95rem;
  background: linear-gradient(135deg, #0f766e, #0ea5e9);
  color: white;
  border: none;
  border-radius: 0.9rem;
  font-size: 1rem;
  font-weight: 900;
  cursor: pointer;
  transition: all 0.2s;
  box-shadow: 0 16px 30px rgba(15, 118, 110, 0.20);
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 22px 40px rgba(15, 118, 110, 0.26);
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.toggle-btn {
  margin-top: 1rem;
  background: transparent;
  border: none;
  color: #0ea5e9;
  cursor: pointer;
  font-weight: 800;
  font-size: 0.95rem;
  width: 100%;
  text-align: center;
}

.toggle-btn:hover {
  text-decoration: underline;
}

.auth-foot {
  margin-top: 1.25rem;
  font-size: 0.85rem;
  color: #94a3b8;
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: 0.35rem;
  font-weight: 650;
}

.auth-foot a {
  color: #0f766e;
  text-decoration: none;
  font-weight: 900;
}

.auth-foot a:hover {
  text-decoration: underline;
}

.success-message {
  margin-top: 1rem;
  text-align: center;
  color: #059669;
  font-weight: 750;
  font-size: 0.9rem;
}

.error-message {
  margin-top: 1rem;
  text-align: center;
  color: #dc2626;
  font-weight: 750;
  font-size: 0.9rem;
}
</style>
