<template>
  <div class="wishlist-page">
   

    <main class="content">
      <div class="header">
        <h1>My Wishlist</h1>
        <p v-if="wishlistItems.length > 0">{{ wishlistItems.length }} favorite(s)</p>
        <p v-else>You have no favorites yet.</p>
      </div>

      <div v-if="wishlistItems.length === 0" class="empty">
        <router-link to="/dashboard/products" class="primary-cta">Browse products</router-link>
      </div>

      <div v-else class="grid">
        <div class="grid-inner">
          <ProductCard
            v-for="p in wishlistItems"
            :key="p.id"
            :product="p"
            @add-to-cart="$emit('add-to-cart', $event)"
            @compare="addToCompare"
          />
        </div>
      </div>

      <ProductCompare v-if="compareList.length > 0" :products="compareList" @close="compareList = []" />
    </main>


  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref, watch } from 'vue'
import { useRouter } from 'vue-router'

import axios from 'axios'

import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'
import ProductCard from '../components/ProductCard.vue'
import ProductCompare from '../components/ProductCompare.vue'

const router = useRouter()

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const wishlist = ref<any[]>([])
const compareList = ref<any[]>([])

const loadWishlist = () => {
  const saved = localStorage.getItem('wishlist')
  wishlist.value = saved ? JSON.parse(saved) : []
}

// If product in localStorage is stale (removed/changed), refresh from API.
// Best-effort: if API fails, keep localStorage data.
const refreshFromApi = async () => {
  try {
    const ids = wishlist.value.map((i) => i.id)
    if (ids.length === 0) return

    // backend may return all products; filter client-side
    const res = await axios.get(`${backendUrl}/api/products`)
    const all = res.data.data || []
    wishlist.value = all.filter((p: any) => ids.includes(p.id))
  } catch {
    // ignore
  }
}

onMounted(async () => {
  loadWishlist()
  await refreshFromApi()
})

watch(
  () => localStorage.getItem('wishlist'),
  () => {
    loadWishlist()
  }
)

const wishlistItems = computed(() => wishlist.value)

const addToCompare = (product: any) => {
  if (compareList.value.length < 3 && !compareList.value.find((p: any) => p.id === product.id)) {
    compareList.value.push(product)
  } else if (compareList.value.find((p: any) => p.id === product.id)) {
    compareList.value = compareList.value.filter((p: any) => p.id !== product.id)
  }
}
</script>

<style scoped>
.wishlist-page {
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

.header {
  margin-bottom: var(--spacing-xl);
}

.header h1 {
  font-size: clamp(1.4rem, 3vw, 2rem);
  font-weight: var(--font-black);
  margin: 0;
}

.header p {
  margin: var(--spacing-xs) 0 0 0;
  color: var(--text-secondary);
  font-weight: var(--font-semibold);
}

.empty {
  margin-top: var(--spacing-2xl);
  display: flex;
  justify-content: center;
}

.primary-cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 44px;
  padding: 12px 18px;
  border-radius: var(--radius-lg);
  background: var(--accent-500);
  color: var(--surface);
  text-decoration: none;
  font-weight: var(--font-black);
  box-shadow: var(--shadow-lg);
}

.grid-inner {
  display: grid;
  grid-template-columns: repeat(6, 1fr);
  gap: var(--spacing-xl);
}

@media (max-width: 1200px) {
  .grid-inner {
    grid-template-columns: repeat(4, 1fr);
  }
}

@media (max-width: 960px) {
  .grid-inner {
    grid-template-columns: repeat(3, 1fr);
  }
}

@media (max-width: 640px) {
  .content {
    padding: var(--spacing-lg) var(--spacing-md);
  }

  .grid-inner {
    grid-template-columns: repeat(2, 1fr);
    gap: var(--spacing-lg);
  }
}

@media (max-width: 480px) {
  .grid-inner {
    grid-template-columns: 1fr;
  }
}
</style>

