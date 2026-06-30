<template>
  <div class="products-page">

    <section class="shop-hero">
      <div class="hero-content">
        <span class="eyebrow">🌿 The full harvest</span>
        <h1>Shop all products</h1>
        <p>Fresh, organic groceries — sorted, filtered, and delivered to your door.</p>
      </div>
      <div class="hero-image">
        <div class="blob-frame">
          <img
            src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80"
            alt="Fresh organic groceries"
          />
        </div>
      </div>
    </section>

    <div class="shop-wrapper">
      <div v-if="loading" class="state-center">
        <div class="spinner"></div>
        <p>Loading products…</p>
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
                <h4>Price range</h4>
                <div class="price-filter">
                  <div class="price-inputs">
                    <input type="number" placeholder="Min" min="0" v-model.number="minPrice" />
                    <span class="dash">–</span>
                    <input type="number" placeholder="Max" min="0" v-model.number="maxPrice" />
                  </div>
                  <button class="apply-price-btn" @click="applyPriceFilter">Apply</button>
                </div>
              </div>

              <div class="filter-section">
                <h4>Availability</h4>
                <label class="check-label">
                  <input type="checkbox" v-model="inStockOnly" />
                  <span>In stock only</span>
                </label>
              </div>
            </div>

            <div class="sidebar-promo">
              <span class="promo-tag">Limited offer</span>
              <h3>Free delivery on orders over $50</h3>
              <p>Shop now and save big on fresh organic products.</p>
              <a href="#product-grid" class="promo-link">Shop now →</a>
            </div>
          </aside>

          <main class="main-panel">
            <div id="product-grid" class="products-toolbar">
              <div class="toolbar-left">
                <h2 class="page-title">
                  {{ selectedCategory ? getCategoryName(selectedCategory) : 'All Products' }}
                </h2>
                <span class="product-count">{{ filteredResults.length }} found</span>
                <span v-if="searchQuery" class="search-badge">
                  Search: "{{ searchQuery }}"
                </span>
              </div>
              <div class="toolbar-right">
                <div class="sort-control">
                  <label>Sort by</label>
                  <select v-model="sortBy">
                    <option value="default">Featured</option>
                    <option value="price-asc">Price: Low to High</option>
                    <option value="price-desc">Price: High to Low</option>
                    <option value="rating">Top Rated</option>
                    <option value="name">Name A–Z</option>
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
              <svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <circle cx="11" cy="11" r="8"/>
                <path d="M21 21l-4.35-4.35"/>
                <path d="M8 11h6"/>
              </svg>
              <h3>No products found</h3>
              <p>Try adjusting your filters or search query.</p>
              <button class="clear-filters" @click="clearAllFilters">Clear all filters</button>
            </div>
          </main>
        </div>
      </div>
    </div>

    <section class="bottom-subscribe-strip">
      <div class="subscribe-strip-content">
        <span class="eyebrow eyebrow--dark">Stay stocked</span>
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
/* ============ DESIGN TOKENS (matches Home) ============ */
.products-page {
  /* --bg-page: #faf8f3; */
  --bg-hero: #f0ede3;
  --ink: #2d2a26;
  --ink-muted: #79736a;
  --forest: #1b4332;
  --forest-hover: #143526;
  --sage: #8fae84;
  --sage-light: #e7efe2;
  --clay: #d97757;
  --clay-light: #fbe7df;
  --border: #e8e3d8;
  --card-bg: #ffffff;

  --font-display: 'Fraunces', Georgia, serif;
  --font-body: 'Plus Jakarta Sans', 'Inter', system-ui, sans-serif;

  font-family: var(--font-body);
  color: var(--ink);
  background: var(--bg-page);
}

.products-page * { box-sizing: border-box; }

.products-page h1,
.products-page h2,
.products-page h3 {
  font-family: var(--font-display);
  font-weight: 600;
  letter-spacing: -0.01em;
  color: var(--ink);
}

.eyebrow {
  display: inline-block;
  font-size: 13px;
  font-weight: 700;
  color: var(--forest);
  background: var(--sage-light);
  padding: 6px 14px;
  border-radius: 30px;
  margin-bottom: 16px;
}
.eyebrow--dark {
  background: rgba(27, 67, 50, 0.08);
}

/* ============ LOADING / EMPTY ============ */
.state-center {
  display: flex;
  flex-direction: column;
  align-items: center;
  padding: 100px 20px;
  text-align: center;
  color: var(--ink-muted);
}

.spinner {
  width: 38px;
  height: 38px;
  border: 4px solid var(--border);
  border-top-color: var(--forest);
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin-bottom: 16px;
}

@keyframes spin {
  to { transform: rotate(360deg); }
}

/* ============ HERO ============ */
.shop-hero {
  max-width: 1320px;
  margin: 24px auto 0;
  padding: 24px 24px 0;
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 40px;
}

.hero-content { flex: 1; max-width: 480px; }

.hero-content h1 {
  font-size: 44px;
  margin: 0 0 12px;
  line-height: 1.1;
}

.hero-content p {
  font-size: 17px;
  color: var(--ink-muted);
  margin: 0;
  line-height: 1.5;
}

.hero-image {
  flex-shrink: 0;
  width: 38%;
  max-width: 360px;
}

.blob-frame {
  width: 100%;
  aspect-ratio: 1 / 1;
  clip-path: path('M30,180 C10,100 60,15 160,10 C260,5 330,60 330,170 C330,280 260,330 165,335 C70,340 50,260 30,180 Z');
  overflow: hidden;
  box-shadow: 0 20px 40px -10px rgba(27, 67, 50, 0.2);
}
.blob-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* ============ WRAPPER / CATEGORY STRIP ============ */
.shop-wrapper {
  max-width: 1320px;
  margin: 40px auto 0;
  padding: 0 24px;
}

.category-strip {
  display: flex;
  flex-wrap: wrap;
  gap: 10px;
  margin-bottom: 28px;
  padding: 4px 0;
}

.cat-chip {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 10px 18px;
  background: var(--card-bg);
  border: 1px solid var(--border);
  border-radius: 30px;
  font-size: 14px;
  font-weight: 600;
  color: var(--ink-muted);
  cursor: pointer;
  transition: all 0.2s;
  white-space: nowrap;
  font-family: var(--font-body);
}

.cat-chip:hover {
  border-color: var(--sage);
  color: var(--forest);
  background: var(--sage-light);
}

.cat-chip.active {
  background: var(--forest);
  color: #fff;
  border-color: var(--forest);
  box-shadow: 0 4px 12px rgba(27, 67, 50, 0.2);
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
  background: rgba(0, 0, 0, 0.06);
}

.cat-chip.active .chip-count {
  background: rgba(255, 255, 255, 0.25);
}

/* ============ LAYOUT ============ */
.shop-layout {
  display: grid;
  grid-template-columns: 272px 1fr;
  gap: 28px;
  align-items: start;
}

.sidebar-panel {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.sidebar-card {
  background: var(--card-bg);
  border-radius: 18px;
  padding: 24px;
  border: 1px solid var(--border);
}

.sidebar-card h3 {
  font-size: 18px;
  margin: 0;
}

.filter-section {
  margin-top: 20px;
  padding-top: 20px;
  border-top: 1px solid var(--border);
}

.filter-section h4 {
  font-size: 12px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: var(--ink-muted);
  margin: 0 0 12px;
  font-family: var(--font-body);
}

.sidebar-cat-list {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.cat-btn {
  text-align: left;
  padding: 10px 14px;
  background: var(--bg-page);
  border: 1px solid transparent;
  border-radius: 10px;
  font-size: 14px;
  font-weight: 500;
  color: var(--ink-muted);
  cursor: pointer;
  transition: all 0.2s;
  font-family: var(--font-body);
}

.cat-btn:hover {
  background: var(--sage-light);
  border-color: var(--sage-light);
  color: var(--forest);
}

.cat-btn.active {
  background: var(--forest);
  color: #fff;
  border-color: var(--forest);
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
  border: 1px solid var(--border);
  border-radius: 8px;
  font-size: 14px;
  outline: none;
  font-family: inherit;
  background: var(--bg-page);
}

.price-inputs input:focus {
  border-color: var(--forest);
  background: #fff;
}

.dash { color: var(--ink-muted); font-weight: 500; }

.apply-price-btn {
  width: 100%;
  padding: 11px;
  background: var(--forest);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s;
  font-family: var(--font-body);
}

.apply-price-btn:hover { background: var(--forest-hover); }

.check-label {
  display: flex;
  align-items: center;
  gap: 10px;
  cursor: pointer;
  font-size: 14px;
  color: var(--ink-muted);
  user-select: none;
}

.check-label input[type="checkbox"] {
  width: 18px;
  height: 18px;
  accent-color: var(--forest);
  cursor: pointer;
}

.sidebar-promo {
  border-radius: 18px;
  padding: 28px 24px;
  background: linear-gradient(160deg, var(--forest) 0%, #0f291d 100%);
  color: #ffffff;
  min-height: 220px;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
}

.sidebar-promo .promo-tag {
  font-size: 11px;
  text-transform: uppercase;
  font-weight: 700;
  color: var(--clay-light);
  margin-bottom: 8px;
  letter-spacing: 0.06em;
}

.sidebar-promo h3 {
  font-size: 19px;
  line-height: 1.35;
  margin: 0 0 10px;
  color: #fff;
}

.sidebar-promo p {
  font-size: 13px;
  color: rgba(255, 255, 255, 0.78);
  margin: 0 0 16px;
  line-height: 1.5;
}

.promo-link {
  color: #fff;
  text-decoration: none;
  font-weight: 700;
  font-size: 14px;
  transition: opacity 0.2s;
}

.promo-link:hover { opacity: 0.8; }

.main-panel { min-width: 0; }

.products-toolbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
  padding: 16px 20px;
  background: var(--card-bg);
  border-radius: 16px;
  border: 1px solid var(--border);
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
  font-size: 19px;
  margin: 0;
}

.product-count {
  display: inline-flex;
  align-items: center;
  justify-content: center;
  padding: 6px 14px;
  background: var(--sage-light);
  color: var(--forest);
  font-size: 13px;
  font-weight: 700;
  border-radius: 20px;
}

.search-badge {
  display: inline-flex;
  align-items: center;
  padding: 4px 12px;
  background: var(--clay-light);
  color: var(--clay);
  font-size: 12px;
  font-weight: 700;
  border-radius: 20px;
}

.toolbar-right { display: flex; align-items: center; gap: 12px; }

.sort-control { display: flex; align-items: center; gap: 8px; }

.sort-control label {
  font-size: 13px;
  color: var(--ink-muted);
  font-weight: 600;
  white-space: nowrap;
}

.sort-control select {
  padding: 9px 32px 9px 14px;
  border: 1px solid var(--border);
  border-radius: 10px;
  background: var(--bg-page);
  font-size: 14px;
  font-family: inherit;
  color: var(--ink);
  outline: none;
  cursor: pointer;
  appearance: none;
  background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='12' viewBox='0 0 24 24' fill='none' stroke='%2379736a' stroke-width='2'%3E%3Cpath d='M6 9l6 6 6-6'/%3E%3C/svg%3E");
  background-repeat: no-repeat;
  background-position: right 12px center;
}

.sort-control select:focus { border-color: var(--forest); }

.products-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 22px;
}

.empty-state {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 70px 20px;
  text-align: center;
  background: var(--card-bg);
  border-radius: 20px;
  border: 1px solid var(--border);
  color: var(--ink-muted);
}

.empty-state svg { margin-bottom: 16px; color: var(--sage); }

.empty-state h3 {
  font-size: 19px;
  margin: 0 0 8px;
  color: var(--ink);
}

.empty-state p { margin: 0 0 22px; font-size: 14px; }

.clear-filters {
  padding: 11px 26px;
  background: var(--forest);
  color: #fff;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: background 0.2s;
  font-family: var(--font-body);
}

.clear-filters:hover { background: var(--forest-hover); }

/* ============ BOTTOM SUBSCRIBE ============ */
.bottom-subscribe-strip {
  margin: 72px 24px 0;
  background-color: var(--bg-hero);
  border-radius: 28px;
  padding: 64px;
  background-image: url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80');
  background-repeat: no-repeat;
  background-position: right bottom;
  background-size: contain;
  max-width: 1320px;
  margin-left: auto;
  margin-right: auto;
}

.subscribe-strip-content { max-width: 530px; }

.subscribe-strip-content h2 {
  font-size: 30px;
  margin: 0 0 16px;
  line-height: 1.2;
}

.subscribe-strip-content p {
  font-size: 15px;
  color: var(--ink-muted);
  margin: 0 0 28px;
  line-height: 1.5;
}

.subscribe-form {
  display: flex;
  background: #ffffff;
  padding: 6px;
  border-radius: 40px;
  box-shadow: 0 4px 14px rgba(27, 67, 50, 0.08);
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
  background: var(--forest);
  color: white;
  border: none;
  border-radius: 30px;
  padding: 13px 28px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
  font-family: var(--font-body);
}

.btn-subscribe:hover { background: var(--forest-hover); }

/* ============ RESPONSIVE ============ */
@media (max-width: 1100px) {
  .products-grid { grid-template-columns: repeat(2, 1fr); }
  .shop-layout { grid-template-columns: 1fr; }
  .sidebar-panel { display: none; }
}

@media (max-width: 900px) {
  .shop-hero { flex-direction: column; text-align: center; padding: 40px 24px 0; }
  .hero-image { width: 60%; }
  .hero-content { max-width: 100%; }
  .hero-content h1 { font-size: 34px; }
  .bottom-subscribe-strip { background-image: none; padding: 36px; }
}

@media (max-width: 550px) {
  .products-grid { grid-template-columns: 1fr; }
  .products-toolbar { flex-direction: column; align-items: flex-start; }
  .toolbar-right { width: 100%; }
  .sort-control { width: 100%; }
  .sort-control select { width: 100%; }
  .category-strip { gap: 8px; }
  .cat-chip { padding: 8px 14px; font-size: 13px; }
}
</style>