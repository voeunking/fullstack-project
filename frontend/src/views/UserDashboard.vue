<template>
  <div class="shop-layout">
    <header class="top-nav">
      <div class="nav-inner">
        <router-link to="/dashboard/products" class="logo">
          <svg class="logo-icon" fill="currentColor" viewBox="0 0 24 24">
            <path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/>
          </svg>
          <span>ShopHub</span>
        </router-link>

        <nav class="nav-links">
          <router-link to="/dashboard/products" class="nav-link" active-class="active">Shop</router-link>
          <router-link to="/dashboard/orders" class="nav-link" active-class="active">Orders</router-link>
        </nav>

        <div class="nav-actions">
          <div class="search-box">
            <svg class="search-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input v-model="searchQuery" type="text" placeholder="Search products..." />
          </div>

          <button @click="showCart = true" class="cart-trigger">
            <svg class="cart-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span v-if="cartCount > 0" class="cart-count">{{ cartCount }}</span>
          </button>

          <div class="user-menu">
            <div class="avatar-sm">{{ userInitials }}</div>
            <span class="user-name">{{ userName }}</span>
            <button @click="logout" class="logout-sm" title="Logout">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
              </svg>
            </button>
          </div>
        </div>
      </div>
    </header>

    <main class="shop-main">
      <router-view
        :cart="cart"
        :products="products"
        :categories="categories"
        :orders="orders"
        :searchQuery="searchQuery"
        @add-to-cart="addToCart"
        @update-cart="updateCartItem"
        @remove-from-cart="removeFromCart"
        @place-order="placeOrder"
        @refresh-products="fetchProducts"
        @change-page="router.push('/dashboard/' + $event)"
      />
    </main>

    <Teleport to="body">
      <Transition name="cart">
        <div v-if="showCart" class="cart-overlay" @click.self="showCart = false">
          <div class="cart-drawer">
            <div class="cart-drawer-header">
              <h2>Shopping Cart <span v-if="cartCount > 0" class="cart-count-inline">({{ cartCount }})</span></h2>
              <button @click="showCart = false" class="close-drawer">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
              </button>
            </div>

            <div v-if="cart.length === 0" class="cart-empty">
              <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="48" height="48">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
              </svg>
              <p>Your cart is empty</p>
              <button @click="showCart = false" class="continue-btn">Continue Shopping</button>
            </div>

            <div v-else class="cart-drawer-body">
              <div class="cart-items-list">
                <div v-for="item in cart" :key="item.id" class="cart-drawer-item">
                  <div class="item-thumb">
                    <span>{{ getInitials(item.name) }}</span>
                  </div>
                  <div class="item-meta">
                    <h4>{{ item.name }}</h4>
                    <p class="item-price-sm">${{ item.price.toFixed(2) }}</p>
                    <div class="qty-control">
                      <button @click="updateCartItem(item.id, item.quantity - 1)">-</button>
                      <span>{{ item.quantity }}</span>
                      <button @click="updateCartItem(item.id, item.quantity + 1)">+</button>
                    </div>
                  </div>
                  <div class="item-right">
                    <span class="item-subtotal">${{ (item.price * item.quantity).toFixed(2) }}</span>
                    <button @click="removeFromCart(item.id)" class="remove-xs">&times;</button>
                  </div>
                </div>
              </div>

              <div class="cart-drawer-footer">
                <div class="cart-total-row">
                  <span>Total</span>
                  <strong>${{ cartTotal.toFixed(2) }}</strong>
                </div>
                <button @click="goToCheckout" class="checkout-drawer-btn">Checkout</button>
              </div>
            </div>
          </div>
        </div>
      </Transition>
    </Teleport>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted, provide } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const showCart = ref(false)

interface Product {
  id: number
  name: string
  description?: string
  price: number
  image?: string
  category_id: number
  category?: { id: number; name: string }
}

interface CartItem extends Product {
  quantity: number
}

interface Order {
  id: number
  items: CartItem[]
  total: number
  status: string
  payment_method: string
  created_at: string
}

const backendBaseUrl = 'http://127.0.0.1:8000'
console.log('Backend URL configured:', backendBaseUrl)
console.log('Environment variable:', import.meta.env.VITE_BACKEND_URL)
const products = ref<Product[]>([])
const categories = ref<any[]>([])
const cart = ref<CartItem[]>([])
const orders = ref<Order[]>([])
const searchQuery = ref('')
const user = ref<any>(null)

const userInitials = computed(() => {
  const name = user.value?.name || 'User'
  return name.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2)
})
const userName = computed(() => user.value?.name || 'User')
const cartCount = computed(() => cart.value.reduce((sum, item) => sum + item.quantity, 0))
const cartTotal = computed(() => cart.value.reduce((sum, item) => sum + item.price * item.quantity, 0))

const axiosInstance = axios.create({
  baseURL: backendBaseUrl,
  headers: { Authorization: `Bearer ${localStorage.getItem('token') || ''}` },
})

const fetchProducts = async () => {
  try {
    console.log('Fetching products from:', `${backendBaseUrl}/api/products`)
    const [productsRes, categoriesRes] = await Promise.all([
      axios.get(`${backendBaseUrl}/api/products`),
      axios.get(`${backendBaseUrl}/api/categories`),
    ])
    console.log('Products response:', productsRes.data)
    console.log('Categories response:', categoriesRes.data)
    
    const productsData = productsRes.data.data || []
    const categoriesData = categoriesRes.data.data || []
    
    products.value = productsData.map((p: any) => ({
      ...p,
      category: categoriesData.find((c: any) => c.id === p.category_id),
    }))
    categories.value = categoriesData
    
    console.log('Products loaded:', products.value.length)
    console.log('Categories loaded:', categories.value.length)
  } catch (err) {
    console.error('Failed to fetch products:', err)
  }
}

const fetchOrders = async () => {
  try {
    const res = await axiosInstance.get('/api/orders')
    orders.value = res.data.data || []
  } catch (err) {
    console.error('Failed to fetch orders:', err)
  }
}

const addToCart = (product: Product) => {
  const existing = cart.value.find((item) => item.id === product.id)
  if (existing) {
    existing.quantity++
  } else {
    cart.value.push({ ...product, quantity: 1 })
  }
  saveCart()
}

const updateCartItem = (productId: number, quantity: number) => {
  const item = cart.value.find((item) => item.id === productId)
  if (item) {
    item.quantity = quantity
    if (item.quantity <= 0) {
      removeFromCart(productId)
      return
    }
  }
  saveCart()
}

const removeFromCart = (productId: number) => {
  cart.value = cart.value.filter((item) => item.id !== productId)
  saveCart()
}

const saveCart = () => { localStorage.setItem('cart', JSON.stringify(cart.value)) }
const loadCart = () => {
  const saved = localStorage.getItem('cart')
  if (saved) cart.value = JSON.parse(saved)
}

const placeOrder = async (paymentMethod: string) => {
  try {
    const res = await axiosInstance.post('/api/orders', {
      items: cart.value.map((item) => ({ product_id: item.id, quantity: item.quantity, price: item.price })),
      total: cartTotal.value,
      payment_method: paymentMethod,
    })
    orders.value.unshift(res.data.data)
    cart.value = []
    saveCart()
    return res.data
  } catch (err) {
    console.error('Failed to place order:', err)
    throw err
  }
}

const logout = () => {
  localStorage.removeItem('token')
  localStorage.removeItem('user')
  localStorage.removeItem('cart')
  window.location.href = '/'
}

const goToCheckout = () => {
  showCart.value = false
  router.push('/dashboard/checkout')
}

const getInitials = (name: string) => {
  return name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
}

let pollInterval: number

onMounted(async () => {
  user.value = { name: 'Guest' }
  loadCart()
  await fetchProducts()
  
  if (localStorage.getItem('token')) {
    try {
      await fetchOrders()
    } catch (err) {
      // Orders require auth, ignore error
    }
  }

  pollInterval = window.setInterval(() => {
    fetchProducts()
    if (localStorage.getItem('token')) {
      fetchOrders()
    }
  }, 15000)
})

onUnmounted(() => {
  if (pollInterval) clearInterval(pollInterval)
})

provide('cart', cart)
provide('addToCart', addToCart)
provide('updateCartItem', updateCartItem)
provide('removeFromCart', removeFromCart)
</script>

<style scoped>
.shop-layout {
  min-height: 100vh;
  background: #f8fafc;
  font-family: 'Inter', system-ui, -apple-system, sans-serif;
}

.top-nav {
  background: white;
  border-bottom: 1px solid #e2e8f0;
  position: sticky;
  top: 0;
  z-index: 100;
}

.nav-inner {
  max-width: 1400px;
  margin: 0 auto;
  padding: 0 2rem;
  height: 70px;
  display: flex;
  align-items: center;
  gap: 2rem;
}

.logo {
  display: flex;
  align-items: center;
  gap: 0.625rem;
  text-decoration: none;
  color: #0f172a;
  font-weight: 800;
  font-size: 1.25rem;
  letter-spacing: -0.025em;
}

.logo-icon {
  width: 32px;
  height: 32px;
  color: #3b82f6;
}

.nav-links {
  display: flex;
  gap: 0.5rem;
}

.nav-link {
  padding: 0.5rem 1rem;
  border-radius: 0.5rem;
  color: #475569;
  text-decoration: none;
  font-weight: 500;
  font-size: 0.9375rem;
  transition: all 0.15s;
}

.nav-link:hover {
  background: #f1f5f9;
  color: #0f172a;
}

.nav-link.active {
  background: #eff6ff;
  color: #2563eb;
}

.nav-actions {
  flex: 1;
  display: flex;
  align-items: center;
  gap: 1.5rem;
  justify-content: flex-end;
}

.search-box {
  flex: 1;
  max-width: 400px;
  position: relative;
}

.search-icon {
  position: absolute;
  left: 0.875rem;
  top: 50%;
  transform: translateY(-50%);
  width: 18px;
  height: 18px;
  color: #94a3b8;
}

.search-box input {
  width: 100%;
  padding: 0.5rem 1rem 0.5rem 2.25rem;
  border: 1px solid #e2e8f0;
  border-radius: 9999px;
  background: #f8fafc;
  font-size: 0.875rem;
}

.search-box input:focus {
  outline: none;
  border-color: #3b82f6;
  background: white;
}

.cart-trigger {
  position: relative;
  background: transparent;
  border: none;
  padding: 0.5rem;
  color: #334155;
  cursor: pointer;
  border-radius: 0.5rem;
  transition: all 0.15s;
}

.cart-trigger:hover {
  background: #f1f5f9;
}

.cart-icon {
  width: 22px;
  height: 22px;
}

.cart-count {
  position: absolute;
  top: 0;
  right: 0;
  background: #3b82f6;
  color: white;
  font-size: 0.6875rem;
  font-weight: 700;
  padding: 0.125rem 0.375rem;
  border-radius: 9999px;
  min-width: 18px;
  text-align: center;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.avatar-sm {
  width: 34px;
  height: 34px;
  border-radius: 50%;
  background: linear-gradient(135deg, #3b82f6, #8b5cf6);
  color: white;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  font-size: 0.75rem;
}

.user-name {
  font-weight: 500;
  font-size: 0.875rem;
  color: #1e293b;
}

.logout-sm {
  background: transparent;
  border: none;
  padding: 0.375rem;
  color: #94a3b8;
  cursor: pointer;
  border-radius: 0.375rem;
  transition: all 0.15s;
  display: flex;
  align-items: center;
}

.logout-sm:hover {
  background: #fef2f2;
  color: #ef4444;
}

.shop-main {
  max-width: 1400px;
  margin: 0 auto;
  padding: 2rem;
  min-height: calc(100vh - 70px);
}

/* Cart Drawer */
.cart-overlay {
  position: fixed;
  inset: 0;
  z-index: 200;
  display: flex;
  justify-content: flex-end;
}

.cart-overlay::before {
  content: '';
  position: absolute;
  inset: 0;
  background: rgba(15, 23, 42, 0.4);
  -webkit-backdrop-filter: blur(4px);
  backdrop-filter: blur(4px);
}

.cart-drawer {
  position: relative;
  width: 420px;
  max-width: 90vw;
  background: white;
  height: 100vh;
  display: flex;
  flex-direction: column;
  box-shadow: -20px 0 60px rgba(0, 0, 0, 0.1);
}

.cart-drawer-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 1.25rem 1.5rem;
  border-bottom: 1px solid #e2e8f0;
}

.cart-drawer-header h2 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0;
}

.cart-count-inline {
  font-weight: 400;
  color: #64748b;
  font-size: 0.875rem;
}

.close-drawer {
  background: transparent;
  border: none;
  color: #64748b;
  cursor: pointer;
  padding: 0.25rem;
  border-radius: 0.375rem;
  display: flex;
}

.close-drawer:hover {
  background: #f1f5f9;
  color: #0f172a;
}

.cart-empty {
  flex: 1;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  color: #94a3b8;
  text-align: center;
  padding: 2rem;
}

.cart-empty p {
  margin: 1rem 0;
  font-size: 0.9375rem;
}

.continue-btn {
  padding: 0.625rem 1.5rem;
  background: #3b82f6;
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  font-size: 0.875rem;
}

.cart-drawer-body {
  flex: 1;
  display: flex;
  flex-direction: column;
  overflow: hidden;
}

.cart-items-list {
  flex: 1;
  overflow-y: auto;
  padding: 1rem 1.5rem;
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-drawer-item {
  display: flex;
  gap: 1rem;
  padding-bottom: 1rem;
  border-bottom: 1px solid #f1f5f9;
}

.item-thumb {
  width: 72px;
  height: 72px;
  border-radius: 0.75rem;
  background: linear-gradient(135deg, #e0e7ff, #f3e8ff);
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: #6366f1;
  font-size: 0.875rem;
}

.item-meta {
  flex: 1;
  min-width: 0;
}

.item-meta h4 {
  font-size: 0.875rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 0.25rem;
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}

.item-price-sm {
  font-size: 0.8125rem;
  color: #64748b;
  margin: 0 0 0.5rem;
}

.qty-control {
  display: inline-flex;
  align-items: center;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
}

.qty-control button {
  width: 28px;
  height: 28px;
  background: transparent;
  border: none;
  color: #334155;
  cursor: pointer;
  font-size: 1rem;
  font-weight: 600;
  display: flex;
  align-items: center;
  justify-content: center;
}

.qty-control button:hover {
  background: #f1f5f9;
}

.qty-control span {
  width: 32px;
  text-align: center;
  font-weight: 600;
  font-size: 0.8125rem;
  color: #0f172a;
}

.item-right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.5rem;
}

.item-subtotal {
  font-weight: 600;
  color: #0f172a;
  font-size: 0.875rem;
}

.remove-xs {
  background: transparent;
  border: none;
  color: #cbd5e1;
  font-size: 1.25rem;
  cursor: pointer;
  line-height: 1;
  padding: 0 0.25rem;
}

.remove-xs:hover {
  color: #ef4444;
}

.cart-drawer-footer {
  padding: 1.25rem 1.5rem;
  border-top: 1px solid #e2e8f0;
  background: #fafbfc;
}

.cart-total-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 1rem;
  font-size: 1rem;
  color: #334155;
}

.cart-total-row strong {
  font-size: 1.25rem;
  color: #0f172a;
}

.checkout-drawer-btn {
  width: 100%;
  padding: 0.75rem;
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: white;
  border: none;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.9375rem;
  cursor: pointer;
  transition: all 0.2s;
}

.checkout-drawer-btn:hover {
  transform: scale(1.01);
  box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
}

.cart-enter-active,
.cart-leave-active {
  transition: all 0.3s ease;
}

.cart-enter-from,
.cart-leave-to {
  opacity: 0;
  transform: translateX(100%);
}

.cart-enter-active .cart-drawer,
.cart-leave-active .cart-drawer {
  transition: transform 0.3s ease;
}

.cart-enter-from .cart-drawer,
.cart-leave-to .cart-drawer {
  transform: translateX(100%);
}
</style>
