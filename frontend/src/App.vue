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
          <div class="hero-badge">KING TECH</div>
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
              <!-- <button type="button" :class="role === 'user' ? 'active' : ''" @click="role = 'user'">User</button>
              <button type="button" :class="role === 'admin' ? 'active' : ''" @click="role = 'admin'">Admin</button> -->
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
  background: linear-gradient(135deg, var(--primary-50) 0%, var(--accent-50) 100%);
  display: flex;
  align-items: center;
  justify-content: center;
  padding: var(--spacing-xl);
  position: relative;
}

.auth-bg {
  position: absolute;
  inset: 0;
  background:
    radial-gradient(circle at 18% 10%, rgba(20, 184, 166, 0.15), transparent 35rem),
    radial-gradient(circle at 85% 18%, rgba(249, 115, 22, 0.12), transparent 32rem);
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
  gap: var(--spacing-xl);
  align-items: stretch;
}

@media (max-width: 920px) {
  .auth-grid {
    grid-template-columns: 1fr;
  }
}

.auth-hero {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.85) 0%, rgba(255, 255, 255, 0.95) 100%);
  border: 1px solid rgba(255, 255, 255, 0.6);
  border-radius: var(--radius-2xl);
  padding: var(--spacing-2xl);
  box-shadow: 
    var(--shadow-2xl),
    0 0 0 1px rgba(255, 255, 255, 0.5) inset;
  backdrop-filter: blur(20px);
  position: relative;
  overflow: hidden;
}

.auth-hero::before {
  content: '';
  position: absolute;
  top: -50%;
  right: -50%;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(20, 184, 166, 0.08) 0%, transparent 70%);
  pointer-events: none;
}

.hero-badge {
  display: inline-flex;
  align-items: center;
  gap: var(--spacing-sm);
  padding: var(--spacing-sm) var(--spacing-md);
  border-radius: var(--radius-full);
  background: var(--primary-100);
  color: var(--primary-700);
  font-weight: var(--font-black);
  margin-bottom: var(--spacing-md);
  font-size: var(--text-sm);
  letter-spacing: 0.05em;
  text-transform: uppercase;
}

.auth-hero h1 {
  margin: 0;
  font-size: var(--text-3xl);
  font-weight: var(--font-black);
  color: var(--text-primary);
  line-height: 1.15;
}

.auth-hero p {
  margin: var(--spacing-md) 0 var(--spacing-xl);
  color: var(--text-secondary);
  line-height: 1.6;
  font-weight: var(--font-semibold);
}

.hero-cards {
  display: grid;
  grid-template-columns: 1fr;
  gap: var(--spacing-md);
}

.hero-card {
  display: flex;
  gap: var(--spacing-md);
  align-items: center;
  padding: var(--spacing-md);
  border-radius: var(--radius-xl);
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.85) 100%);
  border: 1px solid rgba(255, 255, 255, 0.8);
  transition: transform var(--transition-fast), box-shadow var(--transition-fast);
  box-shadow: var(--shadow-sm);
  position: relative;
  overflow: hidden;
}

.hero-card::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(20, 184, 166, 0.03) 0%, rgba(249, 115, 22, 0.02) 100%);
  pointer-events: none;
}

.hero-card:hover {
  transform: translateY(-3px) scale(1.02);
  box-shadow: var(--shadow-xl);
  border-color: var(--primary-200);
}

.hero-card-icon {
  width: 52px;
  height: 52px;
  border-radius: var(--radius-lg);
  display: flex;
  align-items: center;
  justify-content: center;
  background: linear-gradient(135deg, var(--primary-500) 0%, var(--primary-600) 100%);
  font-size: 1.35rem;
  flex-shrink: 0;
  box-shadow: var(--shadow-md);
  position: relative;
  z-index: 1;
}

.hero-card-title {
  margin: 0;
  font-weight: var(--font-black);
  color: var(--text-primary);
  font-size: var(--text-sm);
}

.hero-card-sub {
  margin: 0;
  color: var(--text-secondary);
  font-weight: var(--font-semibold);
  font-size: var(--text-xs);
}

.auth-card {
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.95) 0%, rgba(255, 255, 255, 0.9) 100%);
  border-radius: var(--radius-2xl);
  padding: var(--spacing-2xl);
  box-shadow: 
    var(--shadow-2xl),
    0 0 0 1px rgba(255, 255, 255, 0.8) inset;
  border: 1px solid rgba(255, 255, 255, 0.6);
  backdrop-filter: blur(20px);
  position: relative;
  overflow: hidden;
}

.auth-card::before {
  content: '';
  position: absolute;
  top: -30%;
  left: -30%;
  width: 100%;
  height: 100%;
  background: radial-gradient(circle, rgba(249, 115, 22, 0.06) 0%, transparent 60%);
  pointer-events: none;
}

.auth-header h1 {
  font-size: var(--text-2xl);
  font-weight: var(--font-black);
  margin: 0;
  color: var(--text-primary);
}

.auth-header p {
  margin: var(--spacing-sm) 0 var(--spacing-lg);
  color: var(--text-secondary);
  font-weight: var(--font-semibold);
  line-height: 1.5;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-md);
}

.field {
  display: flex;
  flex-direction: column;
  gap: var(--spacing-sm);
}

.field label {
  font-size: var(--text-sm);
  font-weight: var(--font-bold);
  color: var(--slate-700);
}

.field input {
  padding: var(--spacing-md) var(--spacing-lg);
  border: 1px solid var(--border-light);
  border-radius: var(--radius-lg);
  background: linear-gradient(135deg, var(--slate-50) 0%, rgba(255, 255, 255, 0.8) 100%);
  font-size: var(--text-base);
  transition: all var(--transition-base);
  font-weight: var(--font-semibold);
  color: var(--text-primary);
  box-shadow: var(--shadow-sm);
}

.field input::placeholder {
  color: var(--text-tertiary);
}

.field input:focus {
  outline: none;
  border-color: var(--primary-500);
  background: var(--surface);
  box-shadow: 
    0 0 0 4px rgba(20, 184, 166, 0.15),
    var(--shadow-md);
  transform: translateY(-1px);
}

.role-switcher {
  display: flex;
  gap: var(--spacing-sm);
  background: var(--slate-100);
  padding: var(--spacing-xs);
  border-radius: var(--radius-lg);
}

.role-switcher button {
  flex: 1;
  border: none;
  background: transparent;
  padding: var(--spacing-sm);
  border-radius: var(--radius-md);
  font-weight: var(--font-extrabold);
  font-size: var(--text-sm);
  color: var(--text-secondary);
  cursor: pointer;
  transition: all var(--transition-fast);
}

.role-switcher button.active {
  background: linear-gradient(135deg, var(--primary-100), var(--accent-100));
  color: var(--text-primary);
  box-shadow: var(--shadow-sm);
  border: 1px solid var(--primary-200);
}

.submit-btn {
  margin-top: var(--spacing-md);
  padding: var(--spacing-md);
  background: linear-gradient(135deg, var(--primary-600) 0%, var(--primary-500) 50%, var(--primary-600) 100%);
  background-size: 200% 200%;
  color: white;
  border: none;
  border-radius: var(--radius-lg);
  font-size: var(--text-base);
  font-weight: var(--font-black);
  cursor: pointer;
  transition: all var(--transition-base);
  box-shadow: 
    0 16px 30px rgba(15, 118, 110, 0.25),
    0 0 0 1px rgba(255, 255, 255, 0.2) inset;
  position: relative;
  overflow: hidden;
}

.submit-btn::before {
  content: '';
  position: absolute;
  inset: 0;
  background: linear-gradient(135deg, rgba(255, 255, 255, 0.2) 0%, transparent 50%);
  opacity: 0;
  transition: opacity var(--transition-base);
}

.submit-btn:hover:not(:disabled) {
  transform: translateY(-2px);
  box-shadow: 
    0 22px 40px rgba(15, 118, 110, 0.35),
    0 0 0 1px rgba(255, 255, 255, 0.3) inset;
  background-position: 100% 100%;
}

.submit-btn:hover:not(:disabled)::before {
  opacity: 1;
}

.submit-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
  transform: none;
}

.toggle-btn {
  margin-top: var(--spacing-md);
  background: transparent;
  border: none;
  color: var(--primary-600);
  cursor: pointer;
  font-weight: var(--font-extrabold);
  font-size: var(--text-sm);
  width: 100%;
  text-align: center;
  transition: color var(--transition-fast);
}

.toggle-btn:hover {
  color: var(--primary-700);
  text-decoration: underline;
}

.auth-foot {
  margin-top: var(--spacing-lg);
  font-size: var(--text-xs);
  color: var(--text-tertiary);
  display: flex;
  flex-wrap: wrap;
  justify-content: center;
  gap: var(--spacing-xs);
  font-weight: var(--font-semibold);
}

.auth-foot a {
  color: var(--primary-600);
  text-decoration: none;
  font-weight: var(--font-bold);
  transition: color var(--transition-fast);
}

.auth-foot a:hover {
  color: var(--primary-700);
  text-decoration: underline;
}

.success-message {
  margin-top: var(--spacing-md);
  text-align: center;
  color: var(--success);
  font-weight: var(--font-bold);
  font-size: var(--text-sm);
  padding: var(--spacing-sm);
  background: var(--success-light);
  border-radius: var(--radius-md);
}

.error-message {
  margin-top: var(--spacing-md);
  text-align: center;
  color: var(--error);
  font-weight: var(--font-bold);
  font-size: var(--text-sm);
  padding: var(--spacing-sm);
  background: var(--error-light);
  border-radius: var(--radius-md);
}
</style>
