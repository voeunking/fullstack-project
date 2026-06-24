<template>
  <div class="products-page">
    <!-- Hero Banner -->
    <div class="hero-banner">
      <div class="hero-content">
        <span class="hero-tag">New Collection</span>
        <h1>Discover Amazing Products</h1>
        <p>Shop the latest trends with exclusive deals and fast delivery</p>
        <div class="hero-stats">
          <div class="stat">
            <strong>{{ products.length }}+</strong>
            <span>Products</span>
          </div>
          <div class="stat">
            <strong>{{ categories.length }}+</strong>
            <span>Categories</span>
          </div>
          <div class="stat">
            <strong>Free</strong>
            <span>Shipping</span>
          </div>
        </div>
        <router-link to="/dashboard/products" class="hero-cta">Shop Now →</router-link>
      </div>
      <div class="hero-graphic">
        <svg viewBox="0 0 400 300" class="hero-svg">
          <rect x="20" y="60" width="160" height="180" rx="12" fill="#dbeafe"/>
          <rect x="40" y="80" width="120" height="100" rx="8" fill="#3b82f6" opacity="0.3"/>
          <circle cx="100" cy="130" r="30" fill="#3b82f6" opacity="0.2"/>
          <rect x="220" y="20" width="160" height="140" rx="12" fill="#e0e7ff"/>
          <rect x="240" y="40" width="120" height="80" rx="8" fill="#8b5cf6" opacity="0.4"/>
          <rect x="220" y="180" width="160" height="60" rx="12" fill="#f3e8ff"/>
          <rect x="100" y="260" width="120" height="30" rx="6" fill="#10b981" opacity="0.3"/>
        </svg>
      </div>
    </div>

    <!-- Categories Filter -->
    <div class="categories-bar">
      <button class="cat-pill" :class="{ active: selectedCategoryId === '' }" @click="selectedCategoryId = ''">
        All Products
      </button>
      <button v-for="cat in categories" :key="cat.id" class="cat-pill" :class="{ active: selectedCategoryId == cat.id }" @click="selectedCategoryId = cat.id">
        {{ cat.name }}
      </button>
    </div>

    <!-- Loading -->
    <div v-if="loading" class="state-center">
      <div class="spinner"></div>
      <p>Loading products...</p>
    </div>

    <!-- Empty -->
    <div v-else-if="filteredProducts.length === 0" class="state-center">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="64" height="64">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
      </svg>
      <p class="empty-msg">No products available</p>
      <p class="empty-sub">Check back later for new arrivals</p>
    </div>

    <!-- Products Grid -->
    <div v-else class="products-grid">
      <div v-for="product in filteredProducts" :key="product.id" class="product-card">
        <div class="card-image">
          <span v-if="!product.image">{{ getInitials(product.name) }}</span>
          <img v-else :src="backendBaseUrl + '/storage/' + product.image" :alt="product.name" />
          <span class="card-badge" v-if="product.price > 500">Featured</span>
        </div>
        <div class="card-body">
          <span class="card-category">{{ product.category?.name || 'Products' }}</span>
          <h3>{{ product.name }}</h3>
          <p class="card-desc">{{ product.description || 'High quality product' }}</p>
          <div class="card-footer">
            <div class="price-wrapper">
              <span class="card-price">${{ product.price.toFixed(2) }}</span>
              <span class="price-label">USD</span>
            </div>
            <button @click="onAddToCart(product)" class="add-btn" :disabled="adding === product.id">
              <svg v-if="adding !== product.id" width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
              </svg>
              <svg v-else width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
              </svg>
              <span>{{ adding === product.id ? 'Added!' : 'Add' }}</span>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'

const props = defineProps<{
  products: any[]
  categories: any[]
  searchQuery: string
}>()

const emit = defineEmits<{
  addToCart: [product: any]
}>()

const backendBaseUrl = 'http://127.0.0.1:8000'
const selectedCategoryId = ref('')
const adding = ref<number | null>(null)
const loading = ref(false)
const filteredProducts = computed(() => {
  let items = props.products
  if (selectedCategoryId.value) {
    items = items.filter((p: any) => p.category_id == selectedCategoryId.value)
  }
  if (props.searchQuery) {
    const q = props.searchQuery.toLowerCase()
    items = items.filter((p: any) => p.name.toLowerCase().includes(q))
  }
  return items
})

const getInitials = (name: string) => name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)

const onAddToCart = (product: any) => {
  adding.value = product.id
  emit('addToCart', product)
  setTimeout(() => { adding.value = null }, 600)
}
</script>

<style scoped>
.products-page {
  max-width: 1400px;
  margin: 0 auto;
}

.hero-banner {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 2rem;
  align-items: center;
  background: white;
  border-radius: 1.5rem;
  padding: 3rem;
  margin-bottom: 2rem;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
  border: 1px solid #f1f5f9;
}

.hero-content h1 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0.5rem 0 1rem;
  letter-spacing: -0.02em;
  line-height: 1.1;
}

.hero-tag {
  display: inline-block;
  padding: 0.375rem 0.875rem;
  background: #dbeafe;
  color: #1d4ed8;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
}

.hero-content p {
  color: #64748b;
  font-size: 1.0625rem;
  margin: 0 0 1.5rem;
  max-width: 420px;
}

.hero-stats {
  display: flex;
  gap: 2rem;
}

.stat strong {
  display: block;
  font-size: 1.5rem;
  color: #0f172a;
}

.stat span {
  font-size: 0.8125rem;
  color: #64748b;
}

.hero-graphic {
  display: flex;
  align-items: center;
  justify-content: center;
}

.hero-svg {
  width: 100%;
  max-width: 400px;
  height: auto;
}

.categories-bar {
  display: flex;
  gap: 0.5rem;
  margin-bottom: 2rem;
  flex-wrap: wrap;
}

.cat-pill {
  padding: 0.5rem 1.125rem;
  border: 1px solid #e2e8f0;
  background: white;
  border-radius: 9999px;
  font-size: 0.875rem;
  font-weight: 500;
  color: #475569;
  cursor: pointer;
  transition: all 0.15s;
}

.cat-pill:hover {
  border-color: #cbd5e1;
  color: #1e293b;
}

.cat-pill.active {
  background: #0f172a;
  color: white;
  border-color: #0f172a;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 1.5rem;
}

.product-card {
  background: white;
  border-radius: 1rem;
  overflow: hidden;
  border: 1px solid #f1f5f9;
  transition: all 0.25s ease;
}

.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06), 0 0 0 1px #e2e8f0;
}

.card-badge {
  position: absolute;
  top: 0.5rem;
  right: 0.5rem;
  background: #fbbf24;
  color: #78350f;
  font-size: 0.625rem;
  font-weight: 700;
  padding: 0.25rem 0.5rem;
  border-radius: 0.25rem;
  text-transform: uppercase;
}

.card-image {
  height: 200px;
  background: linear-gradient(135deg, #eef2ff, #f5f3ff);
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  position: relative;
}

.card-image span {
  font-size: 1.75rem;
  font-weight: 700;
  color: #6366f1;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.card-body {
  padding: 1.25rem;
}

.card-category {
  display: inline-block;
  padding: 0.25rem 0.625rem;
  background: #f1f5f9;
  color: #475569;
  font-size: 0.6875rem;
  font-weight: 600;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  border-radius: 0.25rem;
  margin-bottom: 0.5rem;
}

.card-body h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 0.375rem;
}

.card-desc {
  color: #64748b;
  font-size: 0.8125rem;
  line-height: 1.5;
  margin: 0 0 1rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.card-footer {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
}

.price-wrapper {
  display: flex;
  flex-direction: column;
}

.price-label {
  font-size: 0.6875rem;
  color: #94a3b8;
  font-weight: 500;
}

.card-price {
  font-size: 1.25rem;
  font-weight: 700;
  color: #0f172a;
}

.add-btn {
  display: flex;
  align-items: center;
  gap: 0.375rem;
  padding: 0.5rem 0.875rem;
  background: #0f172a;
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.8125rem;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.15s;
}

.add-btn:hover:not(:disabled) {
  background: #1e293b;
  transform: scale(1.03);
}

.add-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.state-center {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem;
  text-align: center;
  background: white;
  border-radius: 1rem;
  border: 1px solid #f1f5f9;
  color: #94a3b8;
}

.spinner {
  width: 32px;
  height: 32px;
  border: 3px solid #e2e8f0;
  border-top-color: #0f172a;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.hero-cta {
  display: inline-block;
  padding: 0.75rem 2rem;
  background: linear-gradient(135deg, #3b82f6, #2563eb);
  color: white;
  text-decoration: none;
  border-radius: 9999px;
  font-weight: 600;
  font-size: 0.9375rem;
  margin-top: 1rem;
  transition: all 0.2s;
}

.hero-cta:hover {
  transform: translateY(-2px);
  box-shadow: 0 8px 20px rgba(59, 130, 246, 0.3);
}

.empty-sub {
  margin-top: 0.5rem;
  color: #94a3b8;
  font-size: 0.875rem;
}
</style>
