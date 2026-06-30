<template>
  <div class="user-dashboard">
    <AppNavbar />

    <main class="content">
      <router-view
        :products="filteredProducts"
        :categories="visibleCategories"

        :cart="cart"
        :orders="orders"
        :search-query="searchQuery"
        @add-to-cart="addToCart"
        @update-cart="updateCart"
        @remove-from-cart="removeFromCart"
        @place-order="placeOrder"
      />
    </main>

    <AppFooter />
  </div>
</template>


<script setup lang="ts">
import { ref, computed, onMounted, watch } from 'vue'

import axios from 'axios'
import { useRouter } from 'vue-router'
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'

const router = useRouter()


const searchQuery = ref('')
const products = ref<any[]>([])
const categories = ref<any[]>([])
const cart = ref<any[]>([])
const orders = ref<any[]>([])

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const filteredProducts = computed(() => {
  let items = products.value
  if (searchQuery.value) {
    const q = searchQuery.value.toLowerCase()
    items = items.filter((p: any) => p.name.toLowerCase().includes(q))
  }
  return items
})

// Only show categories that have at least 1 product
const visibleCategories = computed(() => {
  const productCategoryIds = new Set(products.value.map((p: any) => p.category_id))
  return categories.value.filter((c: any) => productCategoryIds.has(c.id))
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

// logout handled in AppNavbar


onMounted(async () => {
  loadCart()
  await Promise.all([
    fetchProducts(),
    fetchCategories(),
    fetchOrders()
  ])
})

watch(
  () => router.currentRoute.value.fullPath,
  async (newPath, oldPath) => {
    // If user is on products page, re-fetch to reflect admin edits
    if (newPath === '/dashboard/products' && newPath !== oldPath) {
      await Promise.all([fetchProducts(), fetchCategories()])
    }
  }
)

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

.content {
  max-width: 1280px;
  margin: 0 auto;
  padding: var(--spacing-2xl) var(--spacing-lg);
}

@media (max-width: 640px) {
  .content {
    padding: var(--spacing-lg) var(--spacing-md);
  }
}
</style>

