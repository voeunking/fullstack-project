<template>
  <div class="products-page">


    <section class="shop-hero">
      <div class="hero-content">
        <h1>Shop All Products</h1>
        <p>Discover fresh, organic products delivered to your door</p>
      </div>
      <div class="hero-image">
        <img
          src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80"
          alt="Fresh organic groceries"
        />
      </div>
    </section>

    <div class="shop-wrapper">
      <div v-if="loading" class="state-center">
        <div class="spinner"></div>
        <p>Loading products...</p>
      </div>

      <div v-else-if="products.length === 0" class="state-center">
        <p>No products available</p>
      </div>

      <div v-else>
        <div class="category-strip">
          <button
            :class="['cat-chip', { active: selectedCategory === '' }]"
            @click="selectedCategory = ''"
          >
            All Products
          </button>
          <button
            v-for="cat in categories"
            :key="cat.id"
            :class="['cat-chip', { active: selectedCategory == cat.id }]"
            @click="selectedCategory = cat.id"
          >
            {{ cat.name }}
            <span class="chip-count">{{ getCategoryProductCount(cat.id) }}</span>
          </button>
        </div>

        <div class="shop-layout">
          <aside class="sidebar-panel">
            <div class="sidebar-card">
              <h3>Filters</h3>
              <div class="filter-section">
                <h4>Categories</h4>
                <div class="sidebar-cat-list">
                  <button
                    :class="['cat-btn', { active: selectedCategory === '' }]"
                    @click="selectedCategory = ''"
                  >
                    All Products
                  </button>
                  <button
                    v-for="cat in categories"
                    :key="cat.id"
                    :class="['cat-btn', { active: selectedCategory == cat.id }]"
                    @click="selectedCategory = cat.id"
                  >
                    {{ cat.name }}
                  </button>
                </div>
              </div>

              <div class="filter-section">
                <h4>Price Range</h4>
                <div class="price-filter">
                  <div class="price-inputs">
                    <input type="number" placeholder="Min" min="0" v-model.number="minPrice" />
                    <span class="dash">-</span>
                    <input type="number" placeholder="Max" min="0" v-model.number="maxPrice" />
                  </div>
                  <button class="apply-price-btn" @click="applyPriceFilter">Apply</button>
                </div>
              </div>

              <div class="filter-section">
                <h4>Availability</h4>
                <label class="check-label">
                  <input type="checkbox" v-model="inStockOnly" />
                  <span>In Stock Only</span>
                </label>
              </div>
            </div>

            <div class="sidebar-promo">
              <span class="promo-tag">Limited Offer</span>
              <h3>Get Free Delivery on Orders Over $50</h3>
              <p>Shop now and save big on fresh organic products</p>
              <a href="#product-grid" class="promo-link">Shop Now →</a>
            </div>
          </aside>

          <main class="main-panel">
            <div id="product-grid" class="products-toolbar">
              <div class="toolbar-left">
                <h2 class="page-title">
                  {{ selectedCategory ? getCategoryName(selectedCategory) : 'All Products' }}
                </h2>
                <span class="product-count">{{ filteredResults.length }} products found</span>
                <span v-if="searchQuery" class="search-badge">
                  Search: "{{ searchQuery }}"
                </span>
              </div>
              <div class="toolbar-right">
                <div class="sort-control">
                  <label>Sort by:</label>
                  <select v-model="sortBy">
                    <option value="default">Featured</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="rating">Top Rated</option>
                    <option value="name">Name A-Z</option>
                  </select>
                </div>
              </div>
            </div>

            <div class="products-grid">
              <ProductCard
                v-for="product in sortedProducts"
                :key="product.id"
                :product="product"
                @add-to-cart="$emit('add-to-cart', $event)"
                @compare="addToCompare"
              />
            </div>

            <div v-if="sortedProducts.length === 0" class="empty-state">
              <svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
                <path d="M8 11h6"/>
              </svg>
              <h3>No products found</h3>
              <p>Try adjusting your filters or search query</p>
              <button class="clear-filters" @click="clearAllFilters">Clear All Filters</button>
            </div>
          </main>
        </div>
      </div>
    </div>

    <section class="bottom-subscribe-strip">
      <div class="subscribe-strip-content">
        <h2>Stay home & get your daily needs from our shop</h2>
        <p>Start your daily shopping with Organic Market and get free delivery on your first order over $50.</p>
        <div class="subscribe-form">
          <input type="email" placeholder="Your email address" />
          <button class="btn-subscribe">Subscribe</button>
        </div>
      </div>
    </section>

    <ProductCompare v-if="compareList.length > 0" :products="compareList" @close="compareList = []" />
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import ProductCard from '../components/ProductCard.vue'
import ProductCompare from '../components/ProductCompare.vue'
import AppNavbar from '../components/AppNavbar.vue'

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
const sortBy = ref('default')
const minPrice = ref<number | null>(null)
const maxPrice = ref<number | null>(null)
const inStockOnly = ref(false)

const getCategoryName = (id: string) => {
  const cat = props.categories.find((c: any) => String(c.id) === String(id))
  return cat?.name || 'Products'
}

const getCategoryProductCount = (categoryId: number) => {
  return props.products.filter((p: any) => p?.category_id === categoryId || p?.category?.id === categoryId).length
}

const filteredResults = computed(() => {
  let items = props.products

  if (selectedCategory.value) {
    items = items.filter((p: any) => p.category_id == selectedCategory.value)
  }

  if (props.searchQuery) {
    const q = props.searchQuery.toLowerCase()
    items = items.filter((p: any) => p.name.toLowerCase().includes(q))
  }

  if (minPrice.value !== null && minPrice.value !== undefined) {
    items = items.filter((p: any) => Number(p.price) >= minPrice.value!)
  }

  if (maxPrice.value !== null && maxPrice.value !== undefined) {
    items = items.filter((p: any) => Number(p.price) <= maxPrice.value!)
  }

  if (inStockOnly.value) {
    items = items.filter((p: any) => Number(p.stock) > 0)
  }

  return items
})

const sortedProducts = computed(() => {
  const items = [...filteredResults.value]

  switch (sortBy.value) {
    case 'price-asc':
      return items.sort((a: any, b: any) => Number(a.price) - Number(b.price))
    case 'price-desc':
      return items.sort((a: any, b: any) => Number(b.price) - Number(a.price))
    case 'rating':
      return items.sort((a: any, b: any) => Number(b.rating || 0) - Number(a.rating || 0))
    case 'name':
      return items.sort((a: any, b: any) => a.name.localeCompare(b.name))
    default:
      return items
  }
})

watch(selectedCategory, () => {
  sortBy.value = 'default'
})

const addToCompare = (product: any) => {
  const index = compareList.value.findIndex((p: any) => p.id === product.id)
  if (index > -1) {
    compareList.value.splice(index, 1)
  } else if (compareList.value.length < 3) {
    compareList.value.push(product)
  }
}

const applyPriceFilter = () => {
  if (minPrice.value !== null && maxPrice.value !== null && minPrice.value > maxPrice.value) {
    const temp = minPrice.value
    minPrice.value = maxPrice.value
    maxPrice.value = temp
  }
}

const clearAllFilters = () => {
  selectedCategory.value = ''
  minPrice.value = null
  maxPrice.value = null
  inStockOnly.value = false
  sortBy.value = 'default'
}
</script>

<style scoped>
.products-page {
  font-family: 'Quicksand', 'Inter', system-ui, sans-serif;
  color: #1f2937;
  /* background: #f8faf9; */
}

.state-center {
  display: flex;
  flex-direction: column;
  align-items: center;
  /* padding: 80px 20px; */
  text-align: center;
}

.spinner {
  width: 40px;
  height: 40px;
  border: 4px solid #e5e7eb;
  border-top-color: #046c4e;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  /* margin-bottom: 16px; */
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

.shop-hero {
  max-width: 1400px;
  margin: 24px auto 0;
  padding: 0 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 40px;
}

.hero-content {
  flex: 1;
  max-width: 500px;
}

.hero-content h1 {
  font-size: 48px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 12px 0;
  line-height: 1.1;
}

.hero-content p {
  font-size: 18px;
  color: #6b7280;
  margin: 0;
}

.hero-image {
  flex-shrink: 0;
  width: 45%;
  max-width: 500px;
}

.hero-image img {
  width: 100%;
  height: auto;
  border-radius: 24px;
  object-fit: cover;
  box-shadow: 0 20px 40px -10px rgba(0, 0, 0, 0.12);
}

.shop-wrapper {
  max-width: 1400px;
  margin: 32px auto 0;
  padding: 0 24px;
}

.category-strip {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 24px;
  padding: 4px 0;
}

.cat-chip {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  background: #fff;
  border: 1px solid #e5e7eb;
  border-radius: 30px;
  font-size: 14px;
  font-weight: 600;
  color: #4b5563;
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
}

.cat-chip:hover {
  border-color: #046c4e;
  color: #046c4e;
  background: #f6fbf9;
}

.cat-chip.active {
  background: #046c4e;
  color: #fff;
  border-color: #046c4e;
  box-shadow: 0 4px 12px rgba(4, 108, 78, 0.2);
}

.chip-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  min-width: 22px;
  height: 22px;
  padding: 0 6px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  background: rgba(255, 255, 255, 0.2);
}

.cat-chip.active .chip-count {
  background: rgba(255, 255, 255, 0.25);
}

.shop-layout {
  display: grid;
  grid-template-columns: 280px 1fr;
  gap: 28px;
  align-items: start;
}

.sidebar-panel {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.sidebar-card {
  background: #fff;
  border-radius: 16px;
  padding: 24px;
  border: 1px solid #e5e7eb;
  box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
}

.sidebar-card h3 {
  font-size: 18px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 4px 0;
}

.filter-section {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid #f3f4f6;
}

.filter-section h4 {
  font-size: 14px;
  font-weight: 700;
  color: #374151;
  margin: 0 0 12px 0;
}

.sidebar-cat-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.cat-btn {
  text-align: left;
  padding: 10px 14px;
  background: #f9fafb;
  border: 1px solid transparent;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 500;
  color: #4b5563;
  cursor: pointer;
  transition: all 0.2s;
}

.cat-btn:hover {
  background: #f6fbf9;
  border-color: #def7ec;
  color: #046c4e;
}

.cat-btn.active {
  background: #046c4e;
  color: #fff;
  border-color: #046c4e;
}

.price-filter {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.price-inputs {
  display: flex;
  align-items: center;
  gap: 8px;
}

.price-inputs input {
  width: 100%;
  padding: 10px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  font-family: inherit;
}

.price-inputs input:focus {
  border-color: #046c4e;
  box-shadow: 0 0 0 3px rgba(4, 108, 78, 0.1);
}

.dash {
  color: #9ca3af;
  font-weight: 500;
}

.apply-price-btn {
  width: 100%;
  padding: 10px;
  background: #046c4e;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s;
}

.apply-price-btn:hover {
  background: #03543f;
}

.check-label {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  font-size: 14px;
  color: #4b5563;
  user-select: none;
}

.check-label input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: #046c4e;
  cursor: pointer;
}

.sidebar-promo {
  border-radius: 16px;
  padding: 28px 24px;
  background: linear-gradient(rgba(17, 54, 41, 0.85), rgba(17, 54, 41, 0.9)),
    url('https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=400&q=80') center/cover;
  color: #ffffff;
  min-height: 240px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.promo-tag {
  font-size: 11px;
  text-transform: uppercase;
  font-weight: 700;
  color: #fef08a;
  margin-bottom: 8px;
  letter-spacing: 0.05em;
}

.sidebar-promo h3 {
  font-size: 20px;
  line-height: 1.4;
  margin: 0 0 10px 0;
  color: #fff;
}

.sidebar-promo p {
  font-size: 13px;
  color: rgba(255, 255, 255, 0.8);
  margin: 0 0 16px 0;
}

.promo-link {
  color: #fff;
  text-decoration: none;
  font-weight: 600;
  font-size: 14px;
  transition: opacity 0.2s;
}

.promo-link:hover {
  opacity: 0.8;
}

.main-panel {
  min-width: 0;
}

.products-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding: 16px 20px;
  background: #fff;
  border-radius: 14px;
  border: 1px solid #e5e7eb;
  flex-wrap: wrap;
  gap: 12px;
}

.toolbar-left {
  display: flex;
  align-items: center;
  gap: 12px;
  flex-wrap: wrap;
}

.page-title {
  font-size: 20px;
  font-weight: 700;
  color: #111827;
  margin: 0;
}

.product-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 6px 14px;
  background: #f6fbf9;
  color: #046c4e;
  font-size: 13px;
  font-weight: 700;
  border-radius: 20px;
  border: 1px solid #def7ec;
}

.search-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 12px;
  background: #fef3c7;
  color: #92400e;
  font-size: 12px;
  font-weight: 600;
  border-radius: 20px;
}

.toolbar-right {
  display: flex;
  align-items: center;
  gap: 12px;
}

.sort-control {
  display: flex;
  align-items: center;
  gap: 8px;
}

.sort-control label {
  font-size: 13px;
  color: #6b7280;
  font-weight: 500;
  white-space: nowrap;
}

.sort-control select {
  padding: 8px 32px 8px 12px;
  border: 1px solid #e5e7eb;
  border-radius: 8px;
  background: #fff;
  font-size: 14px;
  font-family: inherit;
  color: #374151;
  outline: none;
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%236b7280' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 10px center;
}

.sort-control select:focus {
  border-color: #046c4e;
  box-shadow: 0 0 0 3px rgba(4, 108, 78, 0.1);
}

.products-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 60px 20px;
  text-align: center;
  background: #fff;
  border-radius: 20px;
  border: 1px solid #e5e7eb;
  color: #6b7280;
}

.empty-state svg {
  margin-bottom: 16px;
  color: #d1d5db;
}

.empty-state h3 {
  font-size: 20px;
  font-weight: 700;
  color: #374151;
  margin: 0 0 8px 0;
}

.empty-state p {
  margin: 0 0 20px 0;
  font-size: 14px;
}

.clear-filters {
  padding: 10px 24px;
  background: #046c4e;
  color: #fff;
  border: none;
  border-radius: 8px;
  font-weight: 600;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s;
}

.clear-filters:hover {
  background: #03543f;
}

.bottom-subscribe-strip {
  margin: 64px 24px 0;
  background-color: #eef7f2;
  border-radius: 24px;
  padding: 60px;
  background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80');
  background-repeat: no-repeat;
  background-position: right bottom;
  background-size: contain;
  max-width: 1400px;
  margin-left: auto;
  margin-right: auto;
}

.subscribe-strip-content {
  max-width: 550px;
}

.subscribe-strip-content h2 {
  font-size: 32px;
  font-weight: 700;
  color: #111827;
  margin: 0 0 16px 0;
  line-height: 1.2;
}

.subscribe-strip-content p {
  font-size: 15px;
  color: #6b7280;
  margin: 0 0 32px 0;
}

.subscribe-form {
  display: flex;
  background: #ffffff;
  padding: 6px;
  border-radius: 40px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.03);
}

.subscribe-form input {
  flex: 1;
  border: none;
  outline: none;
  padding-left: 20px;
  font-size: 14px;
  font-family: inherit;
}

.btn-subscribe {
  background: #046c4e;
  color: white;
  border: none;
  border-radius: 30px;
  padding: 12px 28px;
  font-weight: 600;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-subscribe:hover {
  background: #03543f;
}

@media (max-width: 1100px) {
  .products-grid {
    grid-template-columns: repeat(2, 1fr);
  }
  .shop-layout {
    grid-template-columns: 1fr;
  }
  .sidebar-panel {
    display: none;
  }
}

@media (max-width: 900px) {
  .shop-hero {
    flex-direction: column;
    text-align: center;
    padding: 40px 0;
  }
  .hero-image {
    width: 100%;
  }
  .hero-content {
    max-width: 100%;
  }
  .hero-content h1 {
    font-size: 36px;
  }
  .bottom-subscribe-strip {
    background-image: none;
    padding: 32px;
  }
}

@media (max-width: 550px) {
  .products-grid {
    grid-template-columns: 1fr;
  }
  .products-toolbar {
    flex-direction: column;
    align-items: flex-start;
  }
  .toolbar-right {
    width: 100%;
  }
  .sort-control {
    width: 100%;
  }
  .sort-control select {
    width: 100%;
  }
  .category-strip {
    gap: 8px;
  }
  .cat-chip {
    padding: 8px 14px;
    font-size: 13px;
  }
}
</style>
