<template>
  <div class="products-page">
    <section class="shop-hero">
      <div class="hero-copy">
        <span class="eyebrow">Limited deals live today</span>
        <h1>Upgrade your setup with products worth getting excited about.</h1>
        <p>Browse curated tech, fast-moving discounts, and reliable essentials selected for everyday work, study, and play.</p>
        <div class="hero-actions">
          <a href="#product-grid" class="primary-cta">Shop best picks</a>
          <span class="trust-note">Free support | Easy checkout | Secure payment</span>
        </div>
      </div>
      <div class="hero-showcase" aria-hidden="true">
        <div class="showcase-card main">
          <span class="deal-label">Hot deal</span>
          <strong>{{ featuredProduct?.name || 'Featured gear' }}</strong>
          <small v-if="featuredProduct">${{ featuredProduct.price }}</small>
          <small v-else>New arrivals</small>
        </div>
      </div>
    </section>

    <div class="store-strip">
      <div>
        <strong>Fast local delivery</strong>
        <span>Get orders moving quickly.</span>
      </div>
      <div>
        <strong>Fresh discounts</strong>
        <span>Clear prices and visible savings.</span>
      </div>
      <div>
        <strong>Helpful comparison</strong>
        <span>Compare up to 3 products.</span>
      </div>
    </div>

    <div v-if="loading" class="state-center">
      <div class="spinner"></div>
      <p>Loading products...</p>
    </div>

    <div v-else-if="products.length === 0" class="state-center">
      <p>No products available</p>
    </div>

    <div v-else class="products-layout">
      <div class="products-panel">
        <FilterSidebar :categories="categories" :selected="selectedCategory" @change="setCategory" />

        <div id="product-grid" class="products-grid">
          <ProductCard
            v-for="product in filteredProducts"
            :key="product.id"
            :product="product"
            @add-to-cart="$emit('add-to-cart', $event)"
            @compare="addToCompare"
          />
        </div>
      </div>
    </div>

    <ProductCompare v-if="compareList.length > 0" :products="compareList" @close="compareList = []" />
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import ProductCard from '../components/ProductCard.vue'
import FilterSidebar from '../components/FilterSidebar.vue'
import ProductCompare from '../components/ProductCompare.vue'

const props = defineProps<{
  products: any[]
  categories: any[]
  cart: any[]
  searchQuery: string
}>()

defineEmits<{
  'add-to-cart': [product: any]
}>()

const loading = ref(false)
const selectedCategory = ref('')
const compareList = ref<any[]>([])
const featuredProduct = computed(() => props.products.find((p: any) => p.discount_percent > 0) || props.products[0])

const filteredProducts = computed(() => {
  let items = props.products
  if (selectedCategory.value) {
    items = items.filter((p: any) => p.category_id == selectedCategory.value)
  }
  if (props.searchQuery) {
    const q = props.searchQuery.toLowerCase()
    items = items.filter((p: any) => p.name.toLowerCase().includes(q))
  }
  return items
})

const setCategory = (id: string) => {
  selectedCategory.value = id
}

const addToCompare = (product: any) => {
  if (compareList.value.length < 3 && !compareList.value.find((p: any) => p.id === product.id)) {
    compareList.value.push(product)
  } else if (compareList.value.find((p: any) => p.id === product.id)) {
    compareList.value = compareList.value.filter((p: any) => p.id !== product.id)
  }
}
</script>

<style scoped>
.products-page {
  margin: 0 auto;
}

.shop-hero {
  min-height: 340px;
  display: grid;
  grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
  gap: var(--spacing-2xl);
  align-items: stretch;
  margin-bottom: var(--spacing-lg);
  padding: var(--spacing-2xl);
  border-radius: var(--radius-xl);
  background:
    linear-gradient(135deg, rgba(15, 23, 42, 0.92), rgba(19, 78, 74, 0.88)),
    url('https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1400&q=80') center/cover;
  color: var(--surface);
  overflow: hidden;
  position: relative;
  box-shadow: var(--shadow-2xl);
}

.shop-hero::after {
  content: "";
  position: absolute;
  inset: auto var(--spacing-xl) 0 var(--spacing-xl);
  height: 1px;
  background: rgba(255, 255, 255, 0.15);
}

.hero-copy {
  position: relative;
  z-index: 1;
  display: flex;
  flex-direction: column;
  justify-content: center;
  max-width: 700px;
}

.eyebrow {
  width: fit-content;
  padding: var(--spacing-xs) var(--spacing-sm);
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: var(--radius-full);
  background: rgba(255, 255, 255, 0.1);
  font-size: var(--text-xs);
  font-weight: var(--font-black);
  text-transform: uppercase;
  letter-spacing: 0.05em;
  backdrop-filter: blur(8px);
}

.hero-copy h1 {
  max-width: 680px;
  margin: var(--spacing-md) 0 var(--spacing-sm);
  font-size: clamp(2rem, 5vw, 3.5rem);
  line-height: 1.05;
  font-weight: var(--font-black);
  letter-spacing: -0.02em;
}

.hero-copy p {
  max-width: 560px;
  margin: 0;
  color: rgba(255, 255, 255, 0.9);
  font-size: var(--text-base);
  line-height: 1.7;
  font-weight: var(--font-medium);
}

.hero-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: var(--spacing-md);
  margin-top: var(--spacing-xl);
}

.primary-cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 48px;
  padding: var(--spacing-md) var(--spacing-lg);
  border-radius: var(--radius-lg);
  background: var(--accent-500);
  color: var(--surface);
  text-decoration: none;
  font-weight: var(--font-black);
  box-shadow: var(--shadow-lg);
  transition: all var(--transition-base);
}

.primary-cta:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-xl);
  background: var(--accent-600);
}

.trust-note {
  color: rgba(255, 255, 255, 0.8);
  font-size: var(--text-sm);
  font-weight: var(--font-semibold);
}

.hero-showcase {
  position: relative;
  z-index: 1;
  min-height: 260px;
}

.showcase-card {
  position: absolute;
  border-radius: var(--radius-lg);
  background: rgba(255, 255, 255, 0.95);
  color: var(--text-primary);
  box-shadow: var(--shadow-2xl);
  backdrop-filter: blur(12px);
}

.showcase-card.main {
  right: 0;
  bottom: var(--spacing-md);
  width: min(100%, 320px);
  padding: var(--spacing-lg);
}

.showcase-card.main strong {
  display: block;
  margin: var(--spacing-sm) 0 var(--spacing-xs);
  font-size: var(--text-xl);
  line-height: 1.2;
  font-weight: var(--font-black);
}

.showcase-card.main small {
  color: var(--primary-600);
  font-weight: var(--font-black);
  font-size: var(--text-lg);
}

.deal-label {
  color: var(--accent-700);
  font-size: var(--text-xs);
  font-weight: var(--font-black);
  text-transform: uppercase;
  letter-spacing: 0.05em;
}


.store-strip {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: var(--spacing-md);
  margin-bottom: var(--spacing-2xl);
}

.store-strip div {
  padding: var(--spacing-md);
  border: 1px solid var(--border-light);
  border-radius: var(--radius-lg);
  background: rgba(255, 255, 255, 0.9);
  box-shadow: var(--shadow-sm);
  transition: all var(--transition-fast);
}

.store-strip div:hover {
  transform: translateY(-2px);
  box-shadow: var(--shadow-md);
}

.store-strip strong,
.store-strip span {
  display: block;
}

.store-strip strong {
  color: var(--text-primary);
  font-weight: var(--font-black);
  font-size: var(--text-sm);
}

.store-strip span {
  margin-top: var(--spacing-xs);
  color: var(--text-secondary);
  font-size: var(--text-xs);
  font-weight: var(--font-semibold);
}

.products-layout {
  display: block;
}

.products-panel {
  min-width: 0;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
  gap: var(--spacing-xl);
}

.state-center {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: var(--spacing-3xl);
  text-align: center;
  background: var(--surface);
  border-radius: var(--radius-xl);
  border: 1px solid var(--border-light);
  box-shadow: var(--shadow-sm);
}

.spinner {
  width: 40px;
  height: 40px;
  border: 3px solid var(--border-light);
  border-top-color: var(--primary-500);
  border-radius: var(--radius-full);
  animation: spin 0.8s linear infinite;
  margin-bottom: var(--spacing-md);
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

@media (max-width: 960px) {
  .shop-hero,
  .products-layout {
    grid-template-columns: 1fr;
  }

  .hero-showcase {
    display: none;
  }

  .store-strip {
    grid-template-columns: 1fr;
  }

  .products-toolbar {
    flex-direction: column;
    align-items: flex-start;
  }
}

@media (max-width: 640px) {
  .shop-hero {
    padding: var(--spacing-lg);
    min-height: auto;
  }

  .hero-copy h1 {
    font-size: 2rem;
  }

  .products-grid {
    grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    gap: var(--spacing-lg);
  }

  .store-strip div {
    padding: var(--spacing-sm);
  }
}
</style>
