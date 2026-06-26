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
           <router-link to="/dashboard/products" class="nav-link" active-class="active">Shop</router-link>
           <router-link to="/dashboard/cart" class="nav-link" active-class="active">
             Cart
             <span v-if="cartCount" class="cart-pill">{{ cartCount }}</span>
           </router-link>
           <router-link to="/dashboard/orders" class="nav-link" active-class="active">Orders</router-link>
           <router-link to="/dashboard/profile" class="nav-link" active-class="active">Profile</router-link>
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
    radial-gradient(circle at top left, rgba(20, 184, 166, 0.16), transparent 30rem),
    radial-gradient(circle at top right, rgba(249, 115, 22, 0.12), transparent 28rem),
    linear-gradient(180deg, #f8fafc 0%, #eef2f7 48%, #f8fafc 100%);
  color: #111827;
}

.top-nav {
  background: rgba(255, 255, 255, 0.9);
  border-bottom: 1px solid rgba(226, 232, 240, 0.8);
  box-shadow: 0 12px 36px rgba(15, 23, 42, 0.06);
  backdrop-filter: blur(18px);
  position: sticky;
  top: 0;
  z-index: 100;
}

.nav-inner {
  max-width: 1240px;
  margin: 0 auto;
  padding: 0 1.5rem;
  height: 68px;
  display: flex;
  align-items: center;
  gap: 1.5rem;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  text-decoration: none;
  color: #0f172a;
  font-weight: 800;
  font-size: 1.25rem;
  letter-spacing: 0;
}

.logo-icon {
  width: 28px;
  height: 28px;
  color: #0f766e;
}

.nav-links {
  display: flex;
  gap: 0.5rem;
}

.nav-link {
  display: inline-flex;
  align-items: center;
  gap: 0.4rem;
  min-height: 40px;
  padding: 0.5rem 0.9rem;
  /* border-radius: 0.5rem; */
  color: #475569;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.875rem;
  transition: all 0.2s ease;
}

.nav-link:hover {
  background: #ecfeff;
  color: #0f766e;
}

.nav-link.active {
  /* background: #066c64; */
  color: #066c64;
  border-bottom: #066c64 3px solid;

  /* box-shadow: 0 10px 22px rgba(15, 23, 42, 0.16); */
}

.cart-pill {
  min-width: 1.35rem;
  height: 1.35rem;
  padding: 0 0.35rem;
  border-radius: 999px;
  background: #f97316;
  color: #ffffff;
  display: inline-flex;
  align-items: center;
  justify-content: center;
  font-size: 0.72rem;
  font-weight: 800;
}

.nav-actions {
  flex: 1;
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 1rem;
}

.search-box {
  position: relative;
  /* max-width: 340px; */
  
  width: 80%;
}

.search-icon {
  position: absolute;
  left: 0.75rem;
  top: 50%;
  transform: translateY(-50%);
  width: 16px;
  height: 16px;
  color: #94a3b8;
}

.search-box input {
  width: 100%;
  min-height: 20px;
  padding: 0.6rem 0.75rem 0.6rem 2.25rem;
  border: 1px solid #dbe3ef;
  border-radius: 0.5rem;
  background: #ffffff;
  font-size: 0.875rem;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.05);
}

.search-box input:focus {
  outline: none;
  border-color: #0f766e;
  background: white;
  box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.14);
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding-left: 50px;
}

.avatar-sm {
  width: 32px;
  height: 32px;
  border-radius: 50%;
  background: linear-gradient(135deg, #0f766e, #f97316);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 0.75rem;
  font-weight: 700;
}

.user-name {
  font-size: 0.875rem;
  color: #334155;
  font-weight: 700;
}

.logout-sm {
  background: none;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.25rem;
}

.logout-sm:hover {
  color: #ef4444;
  background: #fef2f2;
}

.content {
  max-width: 1240px;
  margin: 0 auto;
  padding: 2rem 1.5rem;
}

@media (max-width: 900px) {
  .nav-inner {
    height: auto;
    min-height: 64px;
    flex-wrap: wrap;
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }

  .nav-actions {
    width: 100%;
    justify-content: space-between;
  }
}

@media (max-width: 640px) {
  .nav-inner {
    padding-inline: 1rem;
  }

  .nav-links {
    order: 3;
    width: 100%;
    overflow-x: auto;
  }

  .search-box {
    max-width: none;
  }

  .user-name {
    display: none;
  }

  .content {
    padding: 1.25rem 1rem;
  }
}
</style>
