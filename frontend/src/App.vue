<script setup lang="ts">
import { ref, reactive, onMounted, computed } from 'vue'
import { useRoute, useRouter } from 'vue-router'

type AuthMode = 'login' | 'register'
type Role = 'user' | 'admin'

const router = useRouter()
const route = useRoute()

// Sync initial mode with current route if applicable, default to 'login'
const mode = ref<AuthMode>((route.name === 'register' || route.path === '/register') ? 'register' : 'login')
const role = ref<Role>('user')
const loading = ref(false)
const message = ref('')
const error = ref('')

// Added rememberMe and agreeToTerms fields to match UI checkboxes
const form = reactive({
  name: '',
  email: '',
  password: '',
  confirmPassword: '',
  rememberMe: false,
  agreeToTerms: false,
})

const backendBaseUrl = (import.meta.env.VITE_BACKEND_URL || 'http://localhost:8000').replace(/\/$/, '')

// Helper to check if we are on the dedicated auth view
const showAuth = computed(() => route.name === 'root' || route.name === 'register' || route.path === '/' || route.path === '/login')

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
  if (mode.value === 'register' && !form.agreeToTerms) {
    error.value = 'You must agree to the Terms of Service and Privacy Policy.'
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

    router.push('/home')
    localStorage.setItem('userRole', data.user?.role || '')

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
})
</script>

<template>
  <router-view />

  <div v-if="showAuth" class="om-page-wrapper">
    <main class="om-main-content">
      <section class="om-hero-side">
        <div 
          class="om-hero-bg" 
          :style="{ backgroundImage: mode === 'login' 
            ? 'url(https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=1000)' 
            : 'url(https://images.unsplash.com/photo-1610348725531-843dff563e2c?auto=format&fit=crop&q=80&w=1000)' 
          }"
        ></div>
        <div class="om-hero-overlay">
          <div class="om-hero-text-box">
            <h1>{{ mode === 'login' ? 'Fresh from the farm to your doorstep.' : 'Pure Nature, Delivered.' }}</h1>
            <p>{{ mode === 'login' ? 'Experience the purest organic produce, hand-picked for quality and sustainability.' : 'Experience the vitality of hand-picked organic produce from local sustainable farms delivered directly to your doorstep.' }}</p>
          </div>
        </div>
      </section>

      <section class="om-form-side">
        <div class="om-form-container">
          <header class="om-form-header">
            <h2>{{ mode === 'login' ? 'Welcome Back' : 'Create your account' }}</h2>
            <p>{{ mode === 'login' ? 'Please enter your details to sign in to your account.' : 'Start your journey towards a healthier lifestyle today.' }}</p>
          </header>

          <div class="om-social-block" v-if="mode === 'login'">
            <div class="om-social-buttons">
              <button type="button" class="om-btn-social"><span class="social-icon">G</span> Google</button>
              <button type="button" class="om-btn-social"><span class="social-icon">F</span> Facebook</button>
            </div>
            <div class="om-divider">
              <span>or continue with email</span>
            </div>
          </div>

          <form @submit.prevent="submitForm" class="om-auth-form">
            
            <div class="om-field" v-if="mode === 'register'">
              <label for="name">Full Name</label>
              <div class="om-input-icon-wrapper">
                <!-- <span class="input-icon">👤</span> -->
                <input id="name" v-model="form.name" type="text" placeholder="Enter your full name" autocomplete="name" required />
              </div>
            </div>

            <div class="om-field">
              <label for="email">Email Address</label>
              <div class="om-input-icon-wrapper">
                <span class="input-icon" v-if="mode === 'register'"></span>
                <input id="email" v-model="form.email" type="email" placeholder="name@example.com" autocomplete="email" required />
              </div>
            </div>

            <div :class="mode === 'register' ? 'om-field-row' : 'om-field-single'">
              <div class="om-field">
                <div class="om-label-row">
                  <label for="password">Password</label>
                  <a href="#" v-if="mode === 'login'" class="om-link-forgot" @click.prevent>Forgot Password?</a>
                </div>
                <div class="om-input-icon-wrapper">
                  <span class="input-icon" v-if="mode === 'register'"></span>
                  <input id="password" v-model="form.password" type="password" placeholder="••••••••" autocomplete="current-password" required />
                  <button type="button" class="om-input-action-btn" aria-label="Toggle password view"></button>
                </div>
              </div>

              <div class="om-field" v-if="mode === 'register'">
                <label for="confirmPassword">Confirm Password</label>
                <div class="om-input-icon-wrapper">
                  <span class="input-icon"></span>
                  <input id="confirmPassword" v-model="form.confirmPassword" type="password" placeholder="••••••••" autocomplete="new-password" required />
                </div>
              </div>
            </div>

            <div class="om-form-options">
              <label class="om-checkbox-label" v-if="mode === 'login'">
                <input type="checkbox" v-model="form.rememberMe" />
                <span class="checkbox-text">Remember me for 30 days</span>
              </label>

              <label class="om-checkbox-label" v-if="mode === 'register'">
                <input type="checkbox" v-model="form.agreeToTerms" required />
                <span class="checkbox-text">
                  I agree to the <a href="#" @click.prevent>Terms of Service</a> and <a href="#" @click.prevent>Privacy Policy</a>
                </span>
              </label>
            </div>

            <button class="om-btn-submit" type="submit" :disabled="loading">
              <span>{{ loading ? 'Processing...' : mode === 'login' ? 'Login to Account' : 'Create Account' }}</span>
              <span class="btn-arrow" v-if="!loading">➔</span>
            </button>

            <p v-if="message" class="om-msg-success">{{ message }}</p>
            <p v-if="error" class="om-msg-error">{{ error }}</p>
          </form>

          <div class="om-switch-mode-container">
            <span class="switch-text">
              {{ mode === 'login' ? "Don't have an account?" : "Already part of our community?" }}
            </span>
            <button class="om-btn-link" @click="toggleMode">
              {{ mode === 'login' ? 'Create an account' : 'Log in' }}
            </button>
          </div>

          <div class="om-social-block registration-footer-social" v-if="mode === 'register'">
            <div class="om-divider">
              <span>OR REGISTER WITH</span>
            </div>
            <div class="om-social-buttons">
              <button type="button" class="om-btn-social"><span class="social-icon">G</span> Google</button>
              <button type="button" class="om-btn-social"><span class="social-icon">📘</span> Facebook</button>
            </div>
          </div>

        </div>
      </section>
    </main>

   
  </div>
</template>

<style scoped>
/* RESET & CSS VARIABLES FOR ORGANIC MARKET BRANDING */
.om-page-wrapper {
  --om-primary: #006838;
  --om-primary-hover: #004d2a;
  --om-text-dark: #1a1a1a;
  --om-text-muted: #666666;
  --om-bg-light: #f9f9f9;
  --om-border-color: #e5e5e5;
  --om-input-bg-register: #edf4f0;
  
  font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
  background-color: #ffffff;
  color: var(--om-text-dark);
  min-height: 100vh;
  display: flex;
  flex-direction: column;
}

/* NAVBAR BRANDING */
.om-navbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1rem 4%;
  border-bottom: 1px solid var(--om-border-color);
  background-color: #ffffff;
  height: 70px;
  box-sizing: border-box;
}

.om-logo, .om-footer-logo {
  font-size: 1.5rem;
  font-weight: 700;
  color: var(--om-primary);
}

.om-nav-center {
  flex: 0 1 450px;
  margin: 0 1.5rem;
}

.om-search-container {
  position: relative;
  width: 100%;
}

.om-search-icon {
  position: absolute;
  left: 1rem;
  top: 50%;
  transform: translateY(-50%);
  color: var(--om-text-muted);
}

.om-search-container input {
  width: 100%;
  padding: 0.6rem 1rem 0.6rem 2.5rem;
  border: 1px solid var(--om-border-color);
  border-radius: 50px;
  font-size: 0.9rem;
  outline: none;
}

.om-nav-right {
  display: flex;
  align-items: center;
  gap: 2rem;
}

.om-nav-links {
  display: flex;
  gap: 1.5rem;
}

.om-nav-links a {
  text-decoration: none;
  color: var(--om-text-dark);
  font-size: 0.95rem;
  font-weight: 500;
}

.om-nav-icons {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.icon-btn {
  background: none;
  border: none;
  font-size: 1.2rem;
  cursor: pointer;
  padding: 0.25rem;
}

.profile-active {
  border-bottom: 2px solid var(--om-primary);
  border-radius: 0;
  padding-bottom: 4px;
}

/* SPLIT HERO LAYOUT MAIN HOUSING */
.om-main-content {
  display: flex;
  flex: 1;
  min-height: calc(100vh - 70px);
}

/* LEFT IMAGE SPLIT PANEL */
.om-hero-side {
  flex: 1;
  position: relative;
  display: flex;
  align-items: flex-end;
  padding: 4rem;
  overflow: hidden;
}

.om-hero-bg {
  position: absolute;
  inset: 0;
  background-size: cover;
  background-position: center;
  transition: background-image 0.5s ease-in-out;
}

.om-hero-overlay {
  position: absolute;
  inset: 0;
  background: linear-gradient(to top, rgba(0, 0, 0, 0.65) 0%, rgba(0, 0, 0, 0.1) 100%);
  display: flex;
  align-items: flex-end;
  padding: 4rem;
}

.om-hero-text-box {
  color: #ffffff;
  max-width: 500px;
  z-index: 2;
}

.om-hero-text-box h1 {
  font-size: 2.8rem;
  font-weight: 700;
  line-height: 1.2;
  margin-bottom: 1.5rem;
}

.om-hero-text-box p {
  font-size: 1.05rem;
  line-height: 1.5;
  opacity: 0.9;
}

/* RIGHT AUTH PANEL FORM FIELDS */
.om-form-side {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 4rem 2rem;
  background-color: #ffffff;
}

.om-form-container {
  width: 100%;
  max-width: 460px;
}

.om-form-header h2 {
  font-size: 2.2rem;
  font-weight: 700;
  margin-bottom: 0.5rem;
}

.om-form-header p {
  color: var(--om-text-muted);
  margin-bottom: 2rem;
  font-size: 0.95rem;
}

/* SOCIAL AUTH BUTTONS */
.om-social-block {
  margin-bottom: 1.5rem;
}

.om-social-buttons {
  display: flex;
  gap: 1rem;
}

.om-btn-social {
  flex: 1;
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  padding: 0.75rem;
  border: 1px solid var(--om-border-color);
  border-radius: 8px;
  background-color: #ffffff;
  cursor: pointer;
  font-weight: 500;
  font-size: 0.95rem;
}

.om-divider {
  display: flex;
  align-items: center;
  text-align: center;
  color: var(--om-text-muted);
  font-size: 0.85rem;
  margin: 1.5rem 0;
}

.om-divider::before, .om-divider::after {
  content: '';
  flex: 1;
  border-bottom: 1px solid var(--om-border-color);
}

.om-divider:not(:empty)::before { margin-right: .75rem; }
.om-divider:not(:empty)::after { margin-left: .75rem; }

/* INPUT CORE FIELDS FORM DESIGN */
.om-auth-form {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.om-field-row {
  display: flex;
  gap: 1rem;
}

.om-field-row .om-field {
  flex: 1;
}

.om-field {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.om-label-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.om-field label {
  font-size: 0.9rem;
  font-weight: 600;
}

.om-link-forgot {
  font-size: 0.85rem;
  color: var(--om-primary);
  text-decoration: none;
  font-weight: 600;
}

.om-input-icon-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.om-input-icon-wrapper .input-icon {
  position: absolute;
  left: 1rem;
  color: var(--om-text-muted);
  pointer-events: none;
}

.om-auth-form input[type="text"],
.om-auth-form input[type="email"],
.om-auth-form input[type="password"] {
  width: 100%;
  padding: 0.85rem 1rem;
  border: 1px solid var(--om-border-color);
  border-radius: 8px;
  font-size: 0.95rem;
  outline: none;
  box-sizing: border-box;
}

/* Registration input specific modifications */
.om-page-wrapper .om-main-content .om-form-side v-if="mode === 'register'" input, 
.om-field-row input, 
.om-input-icon-wrapper input {
  padding-left: 2.5rem;
}

/* Apply light greenish background to registration fields matching the mockup design */
.om-field-row input,
.om-input-icon-wrapper .input-icon ~ input {
  background-color: var(--om-input-bg-register);
  border-color: transparent;
}

.om-input-action-btn {
  position: absolute;
  right: 1rem;
  background: none;
  border: none;
  cursor: pointer;
  color: var(--om-text-muted);
}

/* CHECKBOX & OPTIONS SPECIFICS */
.om-form-options {
  margin-top: 0.25rem;
}

.om-checkbox-label {
  display: flex;
  align-items: flex-start;
  gap: 0.5rem;
  cursor: pointer;
}

.om-checkbox-label input[type="checkbox"] {
  margin-top: 0.2rem;
  accent-color: var(--om-primary);
}

.checkbox-text {
  font-size: 0.88rem;
  color: var(--om-text-muted);
}

.checkbox-text a {
  color: var(--om-primary);
  text-decoration: none;
  font-weight: 600;
}

/* BUTTON INTERACTION ACCENTS */
.om-btn-submit {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 0.5rem;
  background-color: var(--om-primary);
  color: #ffffff;
  border: none;
  border-radius: 8px;
  padding: 1rem;
  font-size: 1rem;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s ease;
  margin-top: 0.5rem;
}

.om-btn-submit:hover {
  background-color: var(--om-primary-hover);
}

.btn-arrow {
  font-size: 0.9rem;
}

/* MODE TOGGLE CONTROLS */
.om-switch-mode-container {
  display: flex;
  justify-content: center;
  gap: 0.4rem;
  margin-top: 2rem;
  font-size: 0.95rem;
}

.switch-text {
  color: var(--om-text-muted);
}

.om-btn-link {
  background: none;
  border: none;
  color: var(--om-primary);
  font-weight: 700;
  cursor: pointer;
  padding: 0;
  font-size: 0.95rem;
}

.om-btn-link:hover {
  text-decoration: underline;
}

.registration-footer-social {
  margin-top: 2rem;
  margin-bottom: 0;
}

/* ALERTS & NOTIFICATIONS */
.om-msg-success {
  color: #2e7d32;
  background-color: #edf7ed;
  padding: 0.75rem;
  border-radius: 6px;
  font-size: 0.85rem;
  text-align: center;
}

.om-msg-error {
  color: #d32f2f;
  background-color: #fdeded;
  padding: 0.75rem;
  border-radius: 6px;
  font-size: 0.85rem;
  text-align: center;
}

/* STRUCTURAL SITE FOOTER HOUSING */
.om-footer {
  background-color: #ffffff;
  border-top: 1px solid var(--om-border-color);
  padding: 4rem 4rem 1.5rem 4rem;
}

.om-footer-top {
  display: flex;
  flex-wrap: wrap;
  gap: 3rem;
  justify-content: space-between;
  margin-bottom: 3rem;
}

.om-footer-brand-col {
  flex: 0 1 280px;
}

.om-footer-brand-col p {
  color: var(--om-text-muted);
  font-size: 0.9rem;
  line-height: 1.5;
  margin: 1rem 0;
}

.om-social-icons {
  display: flex;
  gap: 1rem;
}

.om-social-icons a {
  text-decoration: none;
  font-size: 1.2rem;
}

.om-footer-link-col h3, .om-footer-newsletter-col h3 {
  font-size: 0.85rem;
  font-weight: 700;
  letter-spacing: 0.05em;
  margin-bottom: 1.2rem;
}

.om-footer-link-col ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.om-footer-link-col li {
  margin-bottom: 0.75rem;
}

.om-footer-link-col a {
  text-decoration: none;
  color: var(--om-text-muted);
  font-size: 0.9rem;
  transition: color 0.2s ease;
}

.om-footer-link-col a:hover {
  color: var(--om-primary);
}

.om-footer-newsletter-col {
  flex: 0 1 240px;
}

.om-newsletter-input-group {
  display: flex;
  background-color: #f0f4f8;
  border-radius: 6px;
  padding: 0.25rem;
}

.om-newsletter-input-group input {
  flex: 1;
  border: none;
  background: transparent;
  padding: 0.6rem;
  font-size: 0.9rem;
  outline: none;
}

.om-newsletter-input-group button {
  background-color: var(--om-primary);
  color: #ffffff;
  border: none;
  border-radius: 4px;
  padding: 0 0.8rem;
  cursor: pointer;
}

.om-footer-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid var(--om-border-color);
  padding-top: 1.5rem;
  color: var(--om-text-muted);
  font-size: 0.85rem;
}

.footer-bottom-corner-accent {
  opacity: 0.3;
}

/* RESPONSIVE LAYOUT BREAKPOINTS */
@media (max-width: 992px) {
  .om-main-content {
    flex-direction: column;
  }
  .om-hero-side {
    min-height: 350px;
    padding: 2rem;
  }
  .om-hero-overlay {
    padding: 2rem;
  }
  .om-form-side {
    padding: 3rem 1.5rem;
  }
  .om-footer-top {
    gap: 2rem;
  }
}

@media (max-width: 600px) {
  .om-nav-links {
    display: none;
  }
  .om-field-row {
    flex-direction: column;
    gap: 1.25rem;
  }
  .om-footer {
    padding: 2rem 1.5rem 1rem 1.5rem;
  }
}
</style>