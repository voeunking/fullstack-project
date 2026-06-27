<template>
  <div class="profile-page">
    <div class="page-header">
      <h1>Profile Settings</h1>
      <p class="sub">Manage your personal information and preferences</p>
    </div>

    <div v-if="loadingInitial" class="profile-content state-center">
      <div class="spinner" aria-hidden="true"></div>
      <p class="muted">Loading profile...</p>
    </div>

    <div v-else class="profile-content">
      <div class="profile-card">
        <div class="profile-avatar">
          <div class="avatar-wrapper" @click="triggerAvatarPicker">
            <img v-if="avatarPreview || user?.avatar" :src="avatarPreview || (user?.avatar ? backendUrl + '/storage/' + user.avatar : '')" :alt="user?.name || 'User'" />
            <div v-else class="avatar-placeholder">{{ userInitials }}</div>
            <div class="avatar-overlay">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
              </svg>
            </div>
          </div>
          <input ref="avatarInput" type="file" accept="image/*" @change="handleAvatarChange" class="hidden" />
        </div>


        <form @submit.prevent="updateProfile" class="profile-form">
          <div class="field-group">
            <label for="name">Full Name</label>
            <input id="name" v-model="form.name" type="text" placeholder="Your name" required />
          </div>

          <div class="field-group">
            <label for="email">Email Address</label>
            <input id="email" v-model="form.email" type="email" placeholder="Your email" required />
          </div>

          <div class="field-group">
            <label for="phone">Phone Number</label>
            <input id="phone" v-model="form.phone" type="tel" placeholder="+1 (555) 123-4567" />
          </div>

          <div class="field-group">
            <label for="address">Address</label>
            <textarea id="address" v-model="form.address" placeholder="Your shipping address" rows="3"></textarea>
          </div>

          <div class="form-actions">
            <button type="submit" class="save-btn" :disabled="loading">
              {{ loading ? 'Saving...' : 'Save Changes' }}
            </button>
            <p v-if="message" class="success-message">{{ message }}</p>
            <p v-if="error" class="error-message">{{ error }}</p>
          </div>
        </form>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import axios from 'axios'

const user = ref<any>(null)
const loading = ref(false)
const loadingInitial = ref(false)
const message = ref('')
const error = ref('')
const avatarInput = ref<HTMLInputElement | null>(null)
const avatarPreview = ref('')
const avatarFile = ref<File | null>(null)

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const form = ref({
  name: '',
  email: '',
  phone: '',
  address: ''
})

const userInitials = computed(() => {
  const n = user.value?.name || 'User'
  return n.split(' ').map((p: string) => p[0]).join('').toUpperCase().slice(0, 2)
})

const handleAvatarChange = (e: Event) => {
  const target = e.target as HTMLInputElement
  const file = target.files?.[0]
  if (file) {
    avatarFile.value = file
    const reader = new FileReader()
    reader.onload = (e) => {
      avatarPreview.value = e.target?.result as string
    }
    reader.readAsDataURL(file)
  }
}

const triggerAvatarPicker = () => {
  if (avatarInput.value) avatarInput.value.click()
}

const updateProfile = async () => {
  loading.value = true
  message.value = ''
  error.value = ''

  try {
    const token = localStorage.getItem('token')
    const formData = new FormData()
    
    Object.keys(form.value).forEach(key => {
      formData.append(key, form.value[key as keyof typeof form.value])
    })
    
    if (avatarFile.value) {
      formData.append('avatar', avatarFile.value)
    }

    const res = await axios.put(`${backendUrl}/api/profile`, formData, {
      headers: { 
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    })
    
    const data = res.data?.data ?? res.data
    user.value = data
    message.value = 'Profile updated successfully!'
    
    // Clear avatar preview and file after successful upload
    avatarPreview.value = ''
    avatarFile.value = null
    
    // Reset file input
    if (avatarInput.value) {
      avatarInput.value.value = ''
    }
  } catch (err: any) {
    error.value = err.response?.data?.message || 'Failed to update profile'
  } finally {
    loading.value = false
  }
}

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (!token) return

  loadingInitial.value = true
  try {
    const res = await axios.get(`${backendUrl}/api/profile`, {
      headers: { Authorization: `Bearer ${token}` }
    })

    const data = res.data?.data ?? res.data
    user.value = data
    form.value = {
      name: data?.name || '',
      email: data?.email || '',
      phone: data?.phone || '',
      address: data?.address || ''
    }
  } catch (err) {
    console.error('Failed to load profile', err)
  } finally {
    loadingInitial.value = false
  }
})
</script>

<style scoped>
/* Modern, single-screen profile layout (no page scroll) */
.profile-page {
  width: 100%;
  max-width: 1080px;
  margin: 0 auto;
  padding: 24px 16px;
  min-height: 100vh;
  overflow: hidden; /* keep one page */
  display: flex;
  flex-direction: column;
  justify-content: center;
}

.page-header {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 18px;
}

.page-header h1 {
  font-size: 28px;
  font-weight: 900;
  margin: 0;
  color: #0f172a;
  letter-spacing: -0.02em;
}

.sub {
  color: #64748b;
  margin: 0;
  font-weight: 650;
}

.profile-shell {
  flex: 1;
  display: grid;
  grid-template-columns: 0.95fr 1.05fr;
  gap: 18px;
  align-items: stretch;
}

/* If small screen: stack but still keep everything on one screen */
@media (max-width: 900px) {
  .profile-shell {
    grid-template-columns: 1fr;
  }
}

/* Left panel */
.profile-content {
  background: rgba(255, 255, 255, 0.85);
  border: 1px solid rgba(226, 232, 240, 0.95);
  border-radius: 18px;
  box-shadow: 0 30px 70px rgba(15, 23, 42, 0.10);
  backdrop-filter: blur(14px);
  overflow: hidden;
}

.profile-content::before {
  content: '';
  position: absolute;
  inset: 0;
}

.profile-left {
  padding: 22px;
  display: flex;
  flex-direction: column;
  gap: 16px;
  position: relative;
}

.profile-left .glow {
  position: absolute;
  inset: -1px;
  background:
    radial-gradient(circle at 12% 18%, rgba(15, 118, 110, 0.25), transparent 40%),
    radial-gradient(circle at 75% 10%, rgba(249, 115, 22, 0.22), transparent 36%),
    radial-gradient(circle at 65% 85%, rgba(59, 130, 246, 0.18), transparent 42%);
  pointer-events: none;
}

.profile-avatar {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 10px;
}

.avatar-wrapper {
  position: relative;
  cursor: pointer;
  width: 112px;
  height: 112px;
}

.profile-avatar img {
  width: 112px;
  height: 112px;
  border-radius: 999px;
  object-fit: cover;
  border: 4px solid rgba(255, 255, 255, 0.95);
  box-shadow:
    0 18px 40px rgba(2, 132, 199, 0.18),
    0 6px 18px rgba(15, 23, 42, 0.08);
  transition: filter 0.2s ease;
}

.avatar-wrapper:hover .profile-avatar img {
  filter: brightness(0.8);
}

.avatar-placeholder {
  width: 112px;
  height: 112px;
  border-radius: 999px;
  background:
    radial-gradient(circle at 25% 15%, rgba(255, 255, 255, 0.55), transparent 45%),
    linear-gradient(135deg, #0f766e, #f97316);
  border: 4px solid rgba(255, 255, 255, 0.95);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 34px;
  font-weight: 950;
  letter-spacing: -0.04em;
  box-shadow:
    0 18px 40px rgba(2, 132, 199, 0.18),
    0 6px 18px rgba(15, 23, 42, 0.08);
  transition: filter 0.2s ease;
}

.avatar-wrapper:hover .avatar-placeholder {
  filter: brightness(0.8);
}

.avatar-overlay {
  position: absolute;
  inset: 0;
  border-radius: 999px;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  align-items: center;
  justify-content: center;
  opacity: 0;
  transition: opacity 0.2s ease;
  color: white;
  border: 4px solid rgba(255, 255, 255, 0.95);
}

.avatar-wrapper:hover .avatar-overlay {
  opacity: 1;
}

.avatar-overlay svg {
  width: 24px;
  height: 24px;
}

.profile-left .mini {
  position: relative;
  z-index: 1;
  margin-top: 4px;
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 12px;
  width: 100%;
}

.mini-card {
  background: rgba(248, 250, 252, 0.9);
  border: 1px solid rgba(226, 232, 240, 0.95);
  border-radius: 14px;
  padding: 12px;
}

.mini-card .k {
  font-size: 12px;
  color: #64748b;
  font-weight: 750;
}

.mini-card .v {
  margin-top: 4px;
  font-size: 14px;
  color: #0f172a;
  font-weight: 900;
  letter-spacing: -0.01em;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

/* Right panel (form) */
.profile-form {
  padding: 22px;
  position: relative;
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.profile-card {
  height: 100%;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 14px;
}

@media (max-width: 900px) {
  .form-grid {
    grid-template-columns: 1fr;
  }
}

.field-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.field-group label {
  font-size: 0.9rem;
  font-weight: 850;
  color: #334155;
}

.field-group input,
.field-group textarea {
  padding: 12px 12px;
  border: 1px solid rgba(226, 232, 240, 0.98);
  border-radius: 14px;
  background: rgba(248, 250, 252, 0.9);
  font-size: 0.95rem;
  transition: border-color 0.15s ease, box-shadow 0.15s ease, background 0.15s ease;
  font-weight: 650;
}

.field-group textarea {
  resize: none;
  min-height: 96px;
}

.field-group input:focus,
.field-group textarea:focus {
  outline: none;
  border-color: rgba(15, 118, 110, 0.7);
  background: white;
  box-shadow: 0 0 0 4px rgba(15, 118, 110, 0.14);
}

.form-actions {
  margin-top: 6px;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.save-btn {
  min-height: 46px;
  padding: 0.85rem 1.1rem;
  background: linear-gradient(135deg, #0f766e, #0ea5e9);
  color: white;
  border: none;
  border-radius: 16px;
  font-size: 1rem;
  font-weight: 950;
  cursor: pointer;
  width: 100%;
  box-shadow: 0 18px 40px rgba(14, 165, 233, 0.20);
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}

.save-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 24px 55px rgba(14, 165, 233, 0.28);
}

.save-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
  box-shadow: none;
}

.success-message,
.error-message {
  text-align: center;
  font-weight: 850;
  font-size: 0.9rem;
}

.success-message {
  color: #059669;
}

.error-message {
  color: #dc2626;
}

/* Hide internal vertical scrolling; keep everything visible */
.profile-shell,
.profile-content,
.profile-form {
  overflow: hidden;
}
</style>
