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
        <div class="showcase-card mini">
          <span>{{ products.length }}</span>
          <small>Products ready</small>
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
      <FilterSidebar :categories="categories" :selected="selectedCategory" @change="setCategory" />

      <div class="products-panel">
        <div class="products-toolbar">
          <div>
            <span class="results-count">{{ filteredProducts.length }} items</span>
            <p>{{ selectedCategory ? 'Filtered products for your category' : 'Top products selected for you' }}</p>
          </div>
          <button class="featured-chip" type="button">Best value first</button>
        </div>

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
  min-height: 320px;
  display: grid;
  grid-template-columns: minmax(0, 1.2fr) minmax(280px, 0.8fr);
  gap: 2rem;
  align-items: stretch;
  margin-bottom: 1rem;
  padding: 2.25rem;
  border-radius: 0.5rem;
  background:
    linear-gradient(135deg, rgba(15, 23, 42, 0.9), rgba(19, 78, 74, 0.86)),
    url('https://images.unsplash.com/photo-1517336714731-489689fd1ca8?auto=format&fit=crop&w=1400&q=80') center/cover;
  color: #ffffff;
  overflow: hidden;
  position: relative;
}

.shop-hero::after {
  content: "";
  position: absolute;
  inset: auto 2rem 0 2rem;
  height: 1px;
  background: rgba(255, 255, 255, 0.2);
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
  padding: 0.35rem 0.65rem;
  border: 1px solid rgba(255, 255, 255, 0.28);
  border-radius: 999px;
  background: rgba(255, 255, 255, 0.12);
  font-size: 0.78rem;
  font-weight: 800;
  text-transform: uppercase;
}

.hero-copy h1 {
  max-width: 680px;
  margin: 0.85rem 0 0.75rem;
  font-size: clamp(2rem, 5vw, 4.4rem);
  line-height: 1;
  font-weight: 900;
  letter-spacing: 0;
}

.hero-copy p {
  max-width: 560px;
  margin: 0;
  color: #dbeafe;
  font-size: 1rem;
  line-height: 1.7;
}

.hero-actions {
  display: flex;
  align-items: center;
  flex-wrap: wrap;
  gap: 1rem;
  margin-top: 1.5rem;
}

.primary-cta {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-height: 46px;
  padding: 0.75rem 1.2rem;
  border-radius: 0.5rem;
  background: #f97316;
  color: #ffffff;
  text-decoration: none;
  font-weight: 800;
  box-shadow: 0 16px 30px rgba(249, 115, 22, 0.35);
}

.trust-note {
  color: #e2e8f0;
  font-size: 0.9rem;
  font-weight: 700;
}

.hero-showcase {
  position: relative;
  z-index: 1;
  min-height: 250px;
}

.showcase-card {
  position: absolute;
  border-radius: 0.5rem;
  background: rgba(255, 255, 255, 0.94);
  color: #0f172a;
  box-shadow: 0 24px 50px rgba(0, 0, 0, 0.2);
}

.showcase-card.main {
  right: 0;
  bottom: 1rem;
  width: min(100%, 320px);
  padding: 1.5rem;
}

.showcase-card.main strong {
  display: block;
  margin: 0.6rem 0 0.35rem;
  font-size: 1.35rem;
  line-height: 1.2;
  font-weight: 900;
}

.showcase-card.main small {
  color: #0f766e;
  font-weight: 900;
  font-size: 1.1rem;
}

.deal-label {
  color: #c2410c;
  font-size: 0.76rem;
  font-weight: 900;
  text-transform: uppercase;
}

.showcase-card.mini {
  left: 0;
  top: 2rem;
  width: 150px;
  padding: 1rem;
}

.showcase-card.mini span {
  display: block;
  font-size: 2.4rem;
  line-height: 1;
  color: #0f766e;
  font-weight: 900;
}

.showcase-card.mini small {
  color: #64748b;
  font-weight: 800;
}

.store-strip {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 0.75rem;
  margin-bottom: 2rem;
}

.store-strip div {
  padding: 1rem;
  border: 1px solid rgba(203, 213, 225, 0.8);
  border-radius: 0.5rem;
  background: rgba(255, 255, 255, 0.8);
  box-shadow: 0 12px 30px rgba(15, 23, 42, 0.05);
}

.store-strip strong,
.store-strip span {
  display: block;
}

.store-strip strong {
  color: #0f172a;
  font-weight: 900;
}

.store-strip span {
  margin-top: 0.2rem;
  color: #64748b;
  font-size: 0.86rem;
}

.products-layout {
  display: grid;
  grid-template-columns: 240px 1fr;
  gap: 2rem;
}

.products-panel {
  min-width: 0;
}

.products-toolbar {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 1rem;
  margin-bottom: 1rem;
  padding: 0.9rem 1rem;
  border: 1px solid rgba(203, 213, 225, 0.8);
  border-radius: 0.5rem;
  background: rgba(255, 255, 255, 0.88);
}

.results-count {
  color: #0f172a;
  font-weight: 900;
}

.products-toolbar p {
  margin: 0.15rem 0 0;
  color: #64748b;
  font-size: 0.85rem;
}

.featured-chip {
  min-height: 38px;
  padding: 0.5rem 0.8rem;
  border: 1px solid #fed7aa;
  border-radius: 999px;
  background: #fff7ed;
  color: #c2410c;
  font-weight: 900;
  cursor: default;
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
  gap: 1.5rem;
}

.state-center {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem;
  text-align: center;
  background: white;
  border-radius: 0.5rem;
  border: 1px solid #e2e8f0;
}

.spinner {
  width: 32px;
  height: 32px;
  border: 3px solid #e2e8f0;
  border-top-color: #3b82f6;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-bottom: 1rem;
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
}

@media (max-width: 640px) {
  .shop-hero {
    padding: 1.5rem;
  }

  .hero-copy h1 {
    font-size: 2.25rem;
  }

  .products-toolbar {
    align-items: flex-start;
    flex-direction: column;
  }
}
</style>
