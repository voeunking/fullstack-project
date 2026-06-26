<template>
  <div class="user-dashboard">
    <header class="top-nav">
      <div class="nav-inner">
        <router-link to="/dashboard/products" class="logo">
          <svg class="logo-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
          </svg>
          <span>KING TECH</span>
        </router-link>

  <nav class="nav-links">
           <router-link to="/dashboard/products" class="nav-link" active-class="active">
             <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
             </svg>
             Shop
           </router-link>
           <router-link to="/dashboard/cart" class="nav-link" active-class="active">
             <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
             </svg>
             Cart
             <span v-if="cartCount" class="cart-pill">{{ cartCount }}</span>
           </router-link>
           <router-link to="/dashboard/orders" class="nav-link" active-class="active">
             <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
             </svg>
             Orders
           </router-link>
           <router-link to="/dashboard/profile" class="nav-link" active-class="active">
             <svg class="nav-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" width="20" height="20">
               <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
             </svg>
             Profile
           </router-link>
         </nav>

        <div class="nav-actions">
          <div class="search-box">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Search products..." />
          </div>

          <div class="user-menu" v-if="user">
            <div class="avatar-sm">{{ userInitials }}</div>
            <span class="user-name">{{ user.name }}</span>
            <button @click="logout" class="logout-sm" title="Logout">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <main class="content">
      <router-view
        :products="filteredProducts"
        :categories="categories"
        :cart="cart"
        :orders="orders"
        :search-query="searchQuery"
        @add-to-cart="addToCart"
        @update-cart="updateCart"
        @remove-from-cart="removeFromCart"
        @place-order="placeOrder"
      />
    </main>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'
import logo from '../public/images/image.png';
const router = useRouter()
const searchQuery = ref('')
const products = ref<any[]>([])
const categories = ref<any[]>([])
const cart = ref<any[]>([])
const orders = ref<any[]>([])
const user = ref<any>(null)

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const userInitials = computed(() => {
  const n = user.value?.name || 'User'
  return n.split(' ').map((p: string) => p[0]).join('').toUpperCase().slice(0, 2)
})

const cartCount = computed(() => cart.value.reduce((s: number, i: any) => s + i.quantity, 0))

const filteredProducts = computed(() => {
  let items = products.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    items = items.filter((p: any) => p.name.toLowerCase().includes(q))
  }
  return items
})

const fetchProducts = async () => {
  const res = await axios.get(`${backendUrl}/api/products`)
  products.value = res.data.data || []
}

const fetchCategories = async () => {
  const res = await axios.get(`${backendUrl}/api/categories`)
  categories.value = res.data.data || []
}

const fetchOrders = async () => {
  const token = localStorage.getItem('token')
  if (!token) return

  const res = await axios.get(`${backendUrl}/api/orders`, {
    headers: { Authorization: `Bearer ${token}` }
  })
  orders.value = res.data.data || []
}

const addToCart = (product: any) => {
  const existing = cart.value.find((i: any) => i.id === product.id)
  if (existing) {
    existing.quantity++
  } else {
    cart.value.push({ ...product, quantity: 1 })
  }
  saveCart()
}

const updateCart = (id: number, qty: number) => {
  const item = cart.value.find((i: any) => i.id === id)
  if (item) {
    if (qty <= 0) {
      removeFromCart(id)
    } else {
      item.quantity = qty
    }
  }
  saveCart()
}

const removeFromCart = (id: number) => {
  cart.value = cart.value.filter((i: any) => i.id !== id)
  saveCart()
}

const saveCart = () => localStorage.setItem('cart', JSON.stringify(cart.value))
const loadCart = () => {
  const saved = localStorage.getItem('cart')
  if (saved) cart.value = JSON.parse(saved)
}

const placeOrder = async (payment: string) => {
  const token = localStorage.getItem('token')
  if (!token) {
    router.push('/')
    return
  }

  const orderItems = cart.value.map((item: any) => ({
    product_id: item.id,
    name: item.name,
    price: Number(item.price),
    quantity: item.quantity,
    image: item.image || item.product?.image || null
  }))

  const res = await axios.post(`${backendUrl}/api/orders`, {
    items: orderItems,
    total: orderItems.reduce((s: number, i: any) => s + i.price * i.quantity, 0),
    payment_method: payment
  }, { headers: { Authorization: `Bearer ${token}` } })
  orders.value.unshift(res.data.data)
  cart.value = []
  saveCart()
  router.push('/dashboard/orders')
}

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  cart.value = []
  router.push('/')
}

onMounted(async () => {
  const token = localStorage.getItem('token')
  if (token) {
    try {
      const res = await axios.get(`${backendUrl}/api/user`, {
        headers: { Authorization: `Bearer ${token}` }
      })
      user.value = res.data
    } catch {
      localStorage.removeItem('token')
      localStorage.removeItem('user')
    }
  }
  loadCart()
  await Promise.all([
    fetchProducts(),
    fetchCategories(),
    fetchOrders()
  ])
})
</script>

<style scoped>
.user-dashboard {
  min-height: 100vh;
  background:
    radial-gradient(circle at top left, rgba(20, 184, 166, 0.08), transparent 30rem),
    radial-gradient(circle at top right, rgba(249, 115, 22, 0.06), transparent 28rem),
    linear-gradient(180deg, var(--background-alt) 0%, var(--slate-100) 48%, var(--background-alt) 100%);
  color: var(--text-primary);
}

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
  max-width: 1280px;
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

.cart-pill {
  min-width: 20px;
  height: 20px;
  padding: 0 var(--spacing-xs);
  border-radius: var(--radius-full);
  background: var(--accent-500);
  color: var(--surface);
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: var(--text-xs);
  font-weight: var(--font-black);
  box-shadow: var(--shadow-sm);
}

.nav-actions {
  flex: 1;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: var(--spacing-md);
}

.search-box {
  position: relative;
  width: 100%;
  max-width: 320px;
}

.search-icon {
  position: absolute;
  left: var(--spacing-sm);
  top: 50%;
  transform: translateY(-50%);
  width: 18px;
  height: 18px;
  color: var(--text-tertiary);
  pointer-events: none;
}

.search-box input {
  width: 100%;
  padding: var(--spacing-sm) var(--spacing-md) var(--spacing-sm) 2.5rem;
  border: 1px solid var(--border-light);
  border-radius: var(--radius-lg);
  background: var(--surface);
  font-size: var(--text-sm);
  box-shadow: var(--shadow-sm);
  transition: all var(--transition-fast);
  color: var(--text-primary);
}

.search-box input::placeholder {
  color: var(--text-tertiary);
}

.search-box input:focus {
  outline: none;
  border-color: var(--primary-500);
  background: var(--surface);
  box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.15);
}

.user-menu {
  display: flex;
  align-items: center;
  gap: var(--spacing-sm);
  padding-left: var(--spacing-lg);
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
  flex-shrink: 0;
}

.user-name {
  font-size: var(--text-sm);
  color: var(--text-primary);
  font-weight: var(--font-bold);
  max-width: 120px;
  overflow: hidden;
  text-overflow: ellipsis;
  white-space: nowrap;
}

.logout-sm {
  background: none;
  border: none;
  color: var(--text-tertiary);
  cursor: pointer;
  padding: var(--spacing-xs);
  border-radius: var(--radius-md);
  transition: all var(--transition-fast);
  display: flex;
  align-items: center;
  justify-content: center;
}

.logout-sm:hover {
  color: var(--error);
  background: var(--error-light);
}

.content {
  max-width: 1280px;
  margin: 0 auto;
  padding: var(--spacing-2xl) var(--spacing-lg);
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

  .nav-actions {
    width: 100%;
    justify-content: space-between;
    order: 3;
  }

  .nav-links {
    order: 2;
    width: 100%;
  }

  .search-box {
    max-width: none;
    order: 1;
    width: 100%;
  }
}

@media (max-width: 640px) {
  .nav-inner {
    padding-inline: var(--spacing-md);
  }

  .nav-links {
    gap: var(--spacing-xs);
  }

  .nav-link {
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: var(--text-xs);
  }

  .user-name {
    display: none;
  }

  .content {
    padding: var(--spacing-lg) var(--spacing-md);
  }

  .logo {
    font-size: var(--text-lg);
  }

  .logo-icon {
    width: 28px;
    height: 28px;
  }
}
</style>
