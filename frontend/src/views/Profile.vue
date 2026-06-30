<template>
  <div class="account-dashboard-layout">
    <aside class="sidebar-menu">
      <h2 class="menu-title">My Account</h2>
      <nav class="nav-links">
        <a href="#" class="nav-item">
          <svg class="menu-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M3 3h7v7H3V3zm11 0h7v7h-7V3zm-11 11h7v7H3v-7zm11 0h7v7h-7v-7z"/>
          </svg>
          Overview
        </a>
        <a href="#" class="nav-item">
          <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
            <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
            <line x1="3" y1="6" x2="21" y2="6"></line>
            <path d="M16 10a4 4 0 0 1-8 0"></path>
          </svg>
          Orders
        </a>
        <a href="#" class="nav-item">
          <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
          Wishlist
        </a>
        <a href="#" class="nav-item">
          <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Address Book
        </a>
        <a href="#" class="nav-item active">
          <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
          </svg>
          Account Settings
        </a>
      </nav>
      <hr class="menu-divider" />
      <button class="logout-btn" @click="logout">
        <svg class="menu-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        Log Out
      </button>
    </aside>

    <main class="main-content">
      <header class="welcome-banner">
        <div class="user-meta">
          <div class="avatar-wrapper" @click="triggerAvatarPicker">
            <img
              v-if="avatarPreview || user?.avatar"
              :src="avatarPreview || (user?.avatar ? backendUrl + '/storage/' + user.avatar : '')"
              :alt="user?.name || 'User'"
            />
            <div v-else class="avatar-placeholder">{{ userInitials }}</div>
            <div class="avatar-overlay">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
          </div>
          <input ref="avatarInput" type="file" accept="image/*" @change="handleAvatarChange" class="hidden" />

          <div class="welcome-text">
            <h1>Welcome back, {{ (form.name || '').split(' ')[0] || 'Sarah' }}!</h1>
            <p>Your organic harvest is waiting for you today.</p>
          </div>
        </div>

        <div class="stat-badges">
          <div class="stat-card">
            <span class="stat-label">Total Orders</span>
            <span class="stat-value count-color">24</span>
          </div>
          <div class="stat-card">
            <span class="stat-label">Points</span>
            <span class="stat-value points-color">1,250</span>
          </div>
        </div>
      </header>

      <section class="form-panel-card">
        <div class="panel-header">
          <h2>Account Settings</h2>
          <p>Update your personal info, login credentials, and delivery addresses.</p>
        </div>

        <div v-if="loadingInitial" class="state-center">
          <div class="spinner" aria-hidden="true"></div>
          <p class="muted">Loading profile details...</p>
        </div>

        <form v-else @submit.prevent="updateProfile" class="profile-form">
          <div class="form-grid">
            <div class="field-group">
              <label for="name">Full Name</label>
              <input id="name" v-model="form.name" type="text" placeholder="Sarah Jenkins" required />
            </div>

            <div class="field-group">
              <label for="email">Email Address</label>
              <input id="email" v-model="form.email" type="email" placeholder="sarah@example.com" required />
            </div>

            <div class="field-group">
              <label for="phone">Phone Number</label>
              <input id="phone" v-model="form.phone" type="tel" placeholder="(555) 123-4567" />
            </div>
          </div>

          <div class="field-group textarea-group">
            <label for="address">Primary Delivery Address</label>
            <textarea
              id="address"
              v-model="form.address"
              placeholder="482 Organic Way, Suite 102\nGreen Valley, CA 90210"
              rows="3"
            ></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="save-btn" :disabled="loading">
              <span v-if="loading">Saving Changes...</span>
              <span v-else>Save Changes</span>
            </button>
            <p v-if="message" class="success-message">{{ message }}</p>
            <p v-if="error" class="error-message">{{ error }}</p>
          </div>
        </form>
      </section>
    </main>
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, reactive, ref } from 'vue'
import axios from 'axios'

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const user = ref<any>(null)
const form = reactive<{ name: string; email: string; phone: string; address: string }>({
  name: '',
  email: '',
  phone: '',
  address: '',
})

const avatarPreview = ref<string>('')
const avatarFile = ref<File | null>(null)
const avatarInput = ref<HTMLInputElement | null>(null)

const loadingInitial = ref(true)
const loading = ref(false)
const message = ref('')
const error = ref('')

const token = () => localStorage.getItem('token')

const userInitials = computed(() => {
  const n = user.value?.name || 'User'
  return n
    .split(' ')
    .map((p: string) => p[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const fetchProfile = async () => {
  const t = token()
  if (!t) return

  loadingInitial.value = true
  try {
    // Get current user + profile fields (profile endpoint returns auth()->user)
    const [u, p] = await Promise.all([
      axios.get(`${backendUrl}/api/user`, { headers: { Authorization: `Bearer ${t}` } }),
      axios.get(`${backendUrl}/api/profile`, { headers: { Authorization: `Bearer ${t}` } }),
    ])

    user.value = p.data || u.data

    form.name = user.value?.name || ''
    form.email = user.value?.email || ''
    form.phone = user.value?.phone || ''
    form.address = user.value?.address || ''
  } catch {
    // if token invalid, clear it
    localStorage.removeItem('token')
    localStorage.removeItem('user')
    user.value = null
  } finally {
    loadingInitial.value = false
  }
}

const triggerAvatarPicker = () => {
  avatarInput.value?.click()
}

const handleAvatarChange = (e: Event) => {
  const input = e.target as HTMLInputElement
  const file = input.files?.[0]
  if (!file) return

  avatarFile.value = file
  avatarPreview.value = URL.createObjectURL(file)
}

const updateProfile = async () => {
  const t = token()
  if (!t) return

  loading.value = true
  message.value = ''
  error.value = ''

  try {
    const payload = new FormData()
    payload.append('name', form.name)
    payload.append('email', form.email)
    payload.append('phone', form.phone || '')
    payload.append('address', form.address || '')
    if (avatarFile.value) payload.append('avatar', avatarFile.value)

    const res = await axios.put(`${backendUrl}/api/profile`, payload, {
      headers: {
        Authorization: `Bearer ${t}`,
        'Content-Type': 'multipart/form-data',
      },
    })

    message.value = res.data?.message || 'Profile updated'
    // Refresh
    await fetchProfile()
  } catch (e: any) {
    error.value = e?.response?.data?.message || 'Update failed'
  } finally {
    loading.value = false
  }
}

const logout = async () => {
  const t = token()
  try {
    if (t) {
      await axios.post(
        `${backendUrl}/api/logout`,
        {},
        {
          headers: { Authorization: `Bearer ${t}` },
        },
      )
    }
  } catch {
    // ignore
  }

  localStorage.removeItem('token')
  localStorage.removeItem('user')
  localStorage.removeItem('userRole')
  window.location.href = '/'
}

onMounted(() => {
  fetchProfile()
})
</script>

<style scoped>
/* Main Layout structure */
.account-dashboard-layout {
  display: grid;
  grid-template-columns: 260px 1fr;
  gap: 32px;
  max-width: 1200px;
  margin: 0 auto;
  padding: 40px 24px;
  font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
  background-color: #f8fafc;
  min-height: 100vh;
}

/* Sidebar Menu styling */
.sidebar-menu {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 24px;
  align-self: start;
}

.menu-title {
  font-size: 20px;
  color: #064e3b;
  font-weight: 700;
  margin: 0 0 20px 0;
}

.nav-links {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.nav-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 12px 16px;
  text-decoration: none;
  color: #64748b;
  font-weight: 500;
  border-radius: 12px;
  transition: all 0.2s ease;
}

.nav-item:hover {
  background-color: #f1f5f9;
  color: #0f172a;
}

.nav-item.active {
  background-color: #10b981; /* Organic Green accent */
  color: white;
  font-weight: 600;
}

/* Shared styling for menu SVG icons */
.menu-icon {
  width: 20px;
  height: 20px;
  flex-shrink: 0;
}

.nav-item:not(.active) .menu-icon {
  color: #94a3b8; /* Subtle gray for inactive links */
}

.nav-item.active .menu-icon {
  color: #ffffff; /* White icon on active state */
}

.menu-divider {
  border: 0;
  border-top: 1px solid #e2e8f0;
  margin: 20px 0;
}

.logout-btn {
  display: flex;
  align-items: center;
  gap: 12px;
  width: 100%;
  padding: 12px 16px;
  background: none;
  border: none;
  color: #dc2626;
  font-weight: 600;
  text-align: left;
  cursor: pointer;
  border-radius: 12px;
  transition: background-color 0.2s;
}

.logout-btn .menu-icon {
  color: #dc2626;
}

.logout-btn:hover {
  background-color: #fef2f2;
}

/* Main content structure */
.main-content {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

/* Welcome Banner matching design layout */
.welcome-banner {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  flex-wrap: wrap;
  gap: 20px;
}

.user-meta {
  display: flex;
  align-items: center;
  gap: 20px;
}

.welcome-text h1 {
  font-size: 24px;
  color: #0f172a;
  margin: 0 0 4px 0;
  font-weight: 700;
}

.welcome-text p {
  color: #64748b;
  margin: 0;
  font-size: 14px;
}

/* Stat Counters boxes from layout */
.stat-badges {
  display: flex;
  gap: 16px;
}

.stat-card {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  padding: 12px 20px;
  min-width: 100px;
  text-align: center;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-label {
  font-size: 12px;
  color: #64748b;
  font-weight: 500;
}

.stat-value {
  font-size: 22px;
  font-weight: 700;
}

.count-color { color: #0f766e; }
.points-color { color: #d97706; }

/* Photo uploader layout */
.avatar-wrapper {
  position: relative;
  cursor: pointer;
  width: 72px;
  height: 72px;
}

.avatar-wrapper img, .avatar-placeholder {
  width: 72px;
  height: 72px;
  border-radius: 50%;
  object-fit: cover;
  border: 3px solid #10b981;
}

.avatar-placeholder {
  background: #e2e8f0;
  color: #475569;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 22px;
  font-weight: 700;
}

.avatar-overlay {
  position: absolute;
  inset: 0;
  border-radius: 50%;
  background: rgba(0, 0, 0, 0.4);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  color: white;
  transition: opacity 0.2s ease;
}

.avatar-wrapper:hover .avatar-overlay {
  opacity: 1;
}

/* Settings Form Panel Container */
.form-panel-card {
  background: white;
  border: 1px solid #e2e8f0;
  border-radius: 20px;
  padding: 32px;
}

.panel-header {
  margin-bottom: 24px;
}

.panel-header h2 {
  font-size: 20px;
  color: #0f172a;
  margin: 0 0 6px 0;
}

.panel-header p {
  color: #64748b;
  font-size: 14px;
  margin: 0;
}

/* Modern inputs grid setup */
.form-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
  gap: 20px;
  margin-bottom: 20px;
}

.field-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.field-group label {
  font-size: 13px;
  font-weight: 600;
  color: #334155;
}

.field-group input,
.field-group textarea {
  padding: 12px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 10px;
  background-color: #ffffff;
  font-size: 14px;
  color: #0f172a;
  transition: border-color 0.2s, box-shadow 0.2s;
}

.field-group input:focus,
.field-group textarea:focus {
  outline: none;
  border-color: #10b981;
  box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.15);
}

.textarea-group {
  margin-bottom: 24px;
}

/* Save changes button styling */
.save-btn {
  padding: 14px 28px;
  background-color: #10b981;
  color: white;
  border: none;
  border-radius: 12px;
  font-size: 15px;
  font-weight: 600;
  cursor: pointer;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s;
}

.save-btn:hover:not(:disabled) {
  background-color: #059669;
}

.save-btn:disabled {
  background-color: #a7f3d0;
  cursor: not-allowed;
}

.hidden { display: none; }

@media (max-width: 930px) {
  .account-dashboard-layout {
    grid-template-columns: 1fr;
  }
}

/* basic spinner placeholders */
.state-center { display: flex; flex-direction: column; align-items: center; gap: 12px; padding: 24px 0; }
.spinner { width: 24px; height: 24px; border: 3px solid #e2e8f0; border-top-color: #10b981; border-radius: 50%; animation: spin 0.8s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
.muted { color: #64748b; font-size: 14px; }
.success-message { color: #0f766e; margin-top: 12px; font-weight: 700; }
.error-message { color: #dc2626; margin-top: 12px; font-weight: 700; }
</style>

