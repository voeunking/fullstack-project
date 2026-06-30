<template>
  <div class="product-detail">
  

    <div v-if="loading" class="state-center">
      <div class="spinner"></div>
      <p>Loading product...</p>
    </div>

    <div v-else-if="!product" class="state-center">
      <p>Product not found</p>
    </div>

    <section v-else class="detail-container">
      <div class="detail-card">
        <div class="detail-image">
          <img v-if="product.image" :src="backendUrl + '/storage/' + product.image" :alt="product.name" />
          <div v-else class="placeholder-img">{{ initials }}</div>
        </div>

        <div class="detail-info">
          <div class="meta-row">
            <span class="category">{{ product.category?.name || 'Featured' }}</span>
            <span v-if="product.stock === 0" class="stock sold">Sold Out</span>
            <span v-else-if="product.stock > 0 && product.stock < 5" class="stock low">Hurry! {{ product.stock }} left</span>
            <span v-else class="stock ok">In stock</span>
          </div>

          <h1 class="title">{{ product.name }}</h1>
          <p class="description">{{ product.description || 'No description' }}</p>

          <div class="price-row">
            <div class="price">
              <span v-if="product.discount_percent > 0" class="final-price">${{ finalPrice }}</span>
              <span v-else class="final-price">${{ Number(product.price).toFixed(2) }}</span>

              <span v-if="product.discount_percent > 0" class="original-price">${{ Number(product.price).toFixed(2) }}</span>
            </div>
          </div>

          <div class="specs" v-if="product.ram || product.cpu || product.storage">
            <div v-if="product.ram" class="spec">RAM: {{ product.ram }}</div>
            <div v-if="product.cpu" class="spec">CPU: {{ product.cpu }}</div>
            <div v-if="product.storage" class="spec">Storage: {{ product.storage }}</div>
          </div>

          <div class="actions">
            <button class="btn-primary" :disabled="product.stock === 0" @click="addToCart">
              {{ product.stock === 0 ? 'Sold Out' : 'Add to Cart' }}
            </button>
            <button class="btn-secondary" @click="goBack">Back</button>
          </div>
        </div>
      </div>
    </section>

    
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'
import { useRoute, useRouter } from 'vue-router'
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'

const route = useRoute()
const router = useRouter()

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const loading = ref(false)
const product = ref<any | null>(null)

const productId = computed(() => route.params.id)

const initials = computed(() => {
  const name = product.value?.name || ''
  return name
    .split(' ')
    .filter(Boolean)
    .map((n: string) => n[0])
    .join('')
    .toUpperCase()
    .slice(0, 2)
})

const finalPrice = computed(() => {
  if (!product.value) return '0.00'
  return (product.value.price * (1 - product.value.discount_percent / 100)).toFixed(2)
})

const fetchProduct = async () => {
  loading.value = true
  try {
    const res = await axios.get(`${backendUrl}/api/products/${productId.value}`)
    product.value = res.data?.data || null
  } finally {
    loading.value = false
  }
}

const addToCart = () => {
  alert('Use Cart page logic; this button is just a UI demo.')
}

const goBack = () => {
  router.back()
}

onMounted(fetchProduct)
</script>

<style scoped>
.product-detail {

  min-height: 100vh;
}

.state-center {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 60px;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #f3f4f6;
  border-top-color: #046c4e;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.detail-container {
  max-width: 1100px;
  margin: 24px auto;
  padding: 0 16px;
}

.detail-card {
  display: grid;
  grid-template-columns: 1fr 1.2fr;
  gap: 24px;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 18px;
  padding: 20px;
}

.detail-image {
  border-radius: 14px;
  overflow: hidden;
  height: 420px;
  background: #f9fafb;
  display: flex;
  align-items: center;
  justify-content: center;
}

.detail-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.placeholder-img {
  font-weight: 800;
  color: #046c4e;
  background: #e0f2fe;
  width: 110px;
  height: 110px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.detail-info {
  padding: 6px 4px;
}

.meta-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 12px;
  margin-bottom: 12px;
}

.category {
  background: #eef2ff;
  color: #4f46e5;
  padding: 6px 12px;
  border-radius: 999px;
  font-size: 12px;
  font-weight: 800;
}

.stock {
  font-size: 12px;
  font-weight: 800;
  padding: 6px 12px;
  border-radius: 999px;
}

/* Ensure style tag is closed */
</style>


