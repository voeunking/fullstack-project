<template>
  <header class="top-nav">
    <div class="nav-inner">
      <router-link to="/home" class="logo">
        <svg class="logo-icon" fill="currentColor" viewBox="0 0 24 24">
          <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5" />
        </svg>
        <span>KING TECH</span>
      </router-link>

      <nav class="nav-links">
        <router-link to="/home" class="nav-link" active-class="active">
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 9.75L12 3l9 6.75V21a1.5 1.5 0 01-1.5 1.5H4.5A1.5 1.5 0 013 21V9.75z" />
          </svg>
          Home
        </router-link>

        <router-link to="/about" class="nav-link" active-class="active">
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
          </svg>
          About
        </router-link>

        <router-link to="/products" class="nav-link" active-class="active">
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
          </svg>
          Shop
        </router-link>

        <router-link to="/orders" class="nav-link" active-class="active">
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
          </svg>
          Orders
        </router-link>

        <router-link to="/vendor" class="nav-link" active-class="active">
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
          </svg>
          Vendor
        </router-link>

        <router-link to="/contact" class="nav-link" active-class="active">
          <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
          </svg>
          Contact
        </router-link>
      </nav>

      <div class="nav-actions">
        <button class="search-btn" @click="toggleSearch">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
          </svg>
        </button>

        <router-link to="/wishlist" class="wishlist-btn" title="Wishlist">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
          </svg>
        </router-link>


        <router-link to="/cart" class="cart-btn" active-class="active">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
          </svg>
          <span v-if="cartCount" class="cart-pill">{{ cartCount }}</span>
        </router-link>

        <router-link to="/profile" class="profile-btn" active-class="active">
          <div v-if="user?.avatar" class="avatar-img">
            <img :src="backendUrl + '/storage/' + user.avatar" :alt="user.name" />
          </div>
          <div v-else class="avatar-sm">{{ userInitials }}</div>
        </router-link>

        <button v-if="user" @click="logout" class="logout-btn" title="Logout">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
            <path
              stroke-linecap="round"
              stroke-linejoin="round"
              stroke-width="2"
              d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
        </button>
      </div>
    </div>

    <div v-if="showSearch" class="search-overlay">
      <div class="search-container">
        <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
        </svg>
        <input v-model="searchQuery" type="text" placeholder="Search products..." ref="searchInput" />
        <button class="close-search" @click="toggleSearch">
          <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>
    </div>
  </header>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, nextTick, watch } from 'vue'
import axios from 'axios'

const emit = defineEmits<{
  (e: 'update:searchQuery', v: string): void
}>()

const searchQuery = ref('')
const showSearch = ref(false)
const searchInput = ref<HTMLInputElement | null>(null)

const user = ref<any>(null)

const cart = ref<any[]>([])

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const userInitials = computed(() => {
  const n = user.value?.name || 'User'
  return n
    .split(' ')
    .map((p: string) => p[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const cartCount = computed(() => cart.value.reduce((s: number, i: any) => s + i.quantity, 0))

const loadCart = () => {
  const saved = localStorage.getItem('cart')
  if (saved) cart.value = JSON.parse(saved)
}

const toggleSearch = async () => {
  showSearch.value = !showSearch.value
  if (showSearch.value) {
    await nextTick()
    searchInput.value?.focus()
  }
}

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  cart.value = []
  window.location.href = '/'
  localStorage.removeItem('userRole')
}

watch(searchQuery, (newVal) => {
  emit('update:searchQuery', newVal)
})

onMounted(async () => {
  loadCart()
  const token = localStorage.getItem('token')
  if (!token) return

  try {
    const res = await axios.get(`${backendUrl}/api/user`, {
      headers: { Authorization: `Bearer ${token}` }
    })
    user.value = res.data
  } catch {
    localStorage.removeItem('token')
    localStorage.removeItem('user')
  }
})
</script>

<style scoped>
.top-nav {
  background: rgba(255, 255, 255, 0.95);
  border-bottom: 1px solid var(--border-light);
  box-shadow: var(--shadow-lg);
  backdrop-filter: blur(20px);
  position: sticky;
  top: 0;
  z-index: var(--z-sticky);
}

.nav-inner {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 var(--spacing-lg);
  height: 72px;
  display: flex;
  align-items: center;
  gap: var(--spacing-xl);
}

.logo {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  text-decoration: none;
  color: var(--text-primary);
  font-weight: var(--font-extrabold);
  font-size: var(--text-xl);
  letter-spacing: -0.02em;
  transition: opacity var(--transition-fast);
  flex-shrink: 0;
}

.logo:hover {
  opacity: 0.8;
}

.logo-icon {
  width: 32px;
  height: 32px;
  color: var(--primary-600);
}

.nav-links {
  display: flex;
  gap: var(--spacing-xs);
  flex: 1;
  justify-content: center;
}

.nav-link {
  display: inline-flex;
  align-items: center;
  gap: var(--spacing-xs);
  min-height: 44px;
  padding: var(--spacing-sm) var(--spacing-md);
  color: var(--text-secondary);
  text-decoration: none;
  font-weight: var(--font-bold);
  font-size: var(--text-sm);
  transition: all var(--transition-fast);
  border-radius: var(--radius-md);
  position: relative;
}

.nav-icon {
  flex-shrink: 0;
}

.nav-link:hover {
  background: var(--primary-50);
  color: var(--primary-700);
}

.nav-link.active {
  color: var(--primary-700);
  background: var(--primary-50);
}

.nav-link.active::after {
  content: '';
  position: absolute;
  bottom: 0;
  left: 50%;
  transform: translateX(-50%);
  width: 24px;
  height: 3px;
  background: var(--primary-600);
  border-radius: var(--radius-full);
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  flex-shrink: 0;
}

.search-btn,
.wishlist-btn,
.cart-btn,
.profile-btn,
.logout-btn {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 44px;
  height: 44px;
  border: none;
  background: transparent;
  color: var(--text-secondary);
  cursor: pointer;
  border-radius: var(--radius-full);
  transition: all var(--transition-fast);
  text-decoration: none;
  position: relative;
}

.search-btn:hover,
.wishlist-btn:hover,
.cart-btn:hover,
.profile-btn:hover,
.logout-btn:hover {
  background: var(--primary-50);
  color: var(--primary-700);
}

.cart-btn.active,
.profile-btn.active {
  background: var(--primary-50);
  color: var(--primary-700);
}

.cart-pill {
  position: absolute;
  top: 4px;
  right: 4px;
  min-width: 18px;
  height: 18px;
  padding: 0 5px;
  border-radius: var(--radius-full);
  background: var(--accent-500);
  color: var(--surface);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 10px;
  font-weight: var(--font-black);
  box-shadow: var(--shadow-sm);
}

.avatar-sm {
  width: 36px;
  height: 36px;
  border-radius: var(--radius-full);
  background: linear-gradient(135deg, var(--primary-600), var(--accent-500));
  color: var(--surface);
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: var(--text-sm);
  font-weight: var(--font-bold);
  box-shadow: var(--shadow-md);
}

.avatar-img {
  width: 36px;
  height: 36px;
  border-radius: var(--radius-full);
  overflow: hidden;
  box-shadow: var(--shadow-md);
}

.avatar-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.search-overlay {
  position: absolute;
  top: 72px;
  left: 0;
  right: 0;
  background: rgba(255, 255, 255, 0.98);
  border-bottom: 1px solid var(--border-light);
  padding: var(--spacing-lg);
  box-shadow: var(--shadow-lg);
  z-index: var(--z-dropdown);
  pointer-events: auto;
}


.search-container {
  max-width: 800px;
  margin: 0 auto;
  position: relative;
  display: flex;
  align-items: center;
  gap: var(--spacing-md);
}

.search-container .search-icon {
  position: absolute;
  left: var(--spacing-md);
  width: 20px;
  height: 20px;
  color: var(--text-tertiary);
  pointer-events: none;
}

.search-container input {
  width: 100%;
  padding: var(--spacing-md) var(--spacing-md) var(--spacing-md) 3rem;
  border: 2px solid var(--border-light);
  border-radius: var(--radius-lg);
  background: var(--surface);
  font-size: var(--text-base);
  transition: all var(--transition-fast);
  color: var(--text-primary);
}

.search-container input::placeholder {
  color: var(--text-tertiary);
}

.search-container input:focus {
  outline: none;
  border-color: var(--primary-500);
  box-shadow: 0 0 0 4px rgba(20, 184, 166, 0.15);
}

.close-search {
  background: none;
  border: none;
  color: var(--text-tertiary);
  cursor: pointer;
  padding: var(--spacing-sm);
  border-radius: var(--radius-md);
  transition: all var(--transition-fast);
}

.close-search:hover {
  color: var(--text-primary);
  background: var(--slate-100);
}

@media (max-width: 1200px) {
  .nav-links {
    gap: var(--spacing-xs);
  }

  .nav-link {
    padding: var(--spacing-sm);
    font-size: var(--text-xs);
  }

  .nav-link span {
    display: none;
  }
}

@media (max-width: 900px) {
  .nav-inner {
    height: auto;
    min-height: 68px;
    flex-wrap: wrap;
    padding-top: var(--spacing-md);
    padding-bottom: var(--spacing-md);
    gap: var(--spacing-md);
  }

  .nav-links {
    order: 2;
    width: 100%;
    justify-content: flex-start;
    overflow-x: auto;
    padding-bottom: var(--spacing-xs);
  }

  .nav-actions {
    order: 1;
    margin-left: auto;
  }

  .nav-link span {
    display: inline;
  }
}

@media (max-width: 640px) {
  .nav-inner {
    padding-inline: var(--spacing-md);
  }

  .logo {
    font-size: var(--text-lg);
  }

  .logo-icon {
    width: 28px;
    height: 28px;
  }

  .nav-link {
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: var(--text-xs);
  }

  .nav-link span {
    display: none;
  }
}
</style>

