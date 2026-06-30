<template>
  <div class="home">
    <AppNavbar />


    <section class="hero-wrapper">
      <div class="hero">
        <div class="hero-content">
          <h1>
            Eat Organic & <br />
            <span>Healthy Food</span>
          </h1>
          <p class="lede">Save up to 50% off on your first order</p>
          <div class="subscribe-box">
            <span class="mail-icon">✉️</span>
            <input type="email" placeholder="Your email address" />
            <button class="btn-primary">Subscribe</button>
          </div>
        </div>
        <div class="hero-image">
          <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80" alt="Fresh organic groceries" />
        </div>
      </div>
    </section>

    <section class="featured-categories">
      <div class="section-head">
        <h2>Featured Categories</h2>
        <div class="nav-tabs">
          <!-- Keeps the UI look, but category data is dynamic -->
          <a href="#" class="active">All</a>
        </div>
        <div v-if="loading" style="font-size: 12px; color: var(--text-muted);">Loading...</div>
      </div>

      <div class="category-carousel" v-if="featuredCategories.length">
        <div class="cat-card" v-for="cat in featuredCategories" :key="cat.id">
          <div class="cat-icon" :class="colorForIndex(catIndex(cat.id))">
            <img
              v-if="catImageUrl(cat)"
              :src="catImageUrl(cat)"
              :alt="cat.name"
            />
            <span v-else class="cat-placeholder">{{ (cat.name || '?').slice(0, 1).toUpperCase() }}</span>
          </div>
          <h4>{{ cat.name }}</h4>
          <span>{{ categoryProductCount(cat.id) }} items</span>
        </div>
      </div>

      <div v-else class="category-carousel" aria-busy="true">
        <div class="cat-card">Loading...</div>
      </div>
    </section>

    <section class="promo-banners">
      <div class="promo-card bg-wheat">
        <div class="promo-content">
          <h3>Everyday Fresh &<br>Clean with Our<br>Products</h3>
          <button class="btn-primary small">Shop Now</button>
        </div>
        <div class="promo-img">
          <img src="https://images.unsplash.com/photo-1518977822534-7049a61ee0c2?auto=format&fit=crop&w=300&q=80" alt="Fresh Produce" />
        </div>
      </div>
      <div class="promo-card bg-lightblue">
        <div class="promo-content">
          <h3>Make your<br>Breakfast<br>Healthy and Easy</h3>
          <button class="btn-primary small">Shop Now</button>
        </div>
        <div class="promo-img">
          <img src="https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=300&q=80" alt="Healthy Juice" />
        </div>
      </div>
      <div class="promo-card bg-tan">
        <div class="promo-content">
          <h3>The best Organic<br>Products Online</h3>
          <button class="btn-primary small">Shop Now</button>
        </div>
        <div class="promo-img">
          <img src="https://images.unsplash.com/photo-1528825871115-3581a5387915?auto=format&fit=crop&w=300&q=80" alt="Organic Greens" />
        </div>
      </div>
    </section>

    <section class="popular-products">
      <div class="section-head">
        <h2>Popular Products</h2>
        <div class="nav-tabs">
          <a href="#" class="active">All</a>
        </div>
      </div>

      <div class="product-grid" v-if="popularProducts.length">
        <div class="product-card" v-for="p in popularProducts" :key="p.id">
          <div class="product-img">
            <img
              v-if="p.image"
              :src="productImageUrl(p)"
              :alt="p.name"
            />
            <img
              v-else
              src="https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=400&q=80"
              alt="Product"
            />
          </div>
          <span class="product-category">{{ p?.category?.name || 'Uncategorized' }}</span>
          <h3>{{ p.name }}</h3>
          <div class="price-row">
            <div class="price">
              <span class="current">{{ formatPrice(p.price) }}</span>
              <span class="old" v-if="p.discount_percent">{{ formatPrice(discountedOldPrice(p)) }}</span>
            </div>
            <button class="btn-add">🛒 Add</button>
          </div>
        </div>
      </div>

      <div class="product-grid" v-else>
        <div class="product-card">Loading...</div>
      </div>
    </section>

    <section class="daily-sales-section">
      <div class="sales-layout">
        <div class="sales-banner" v-if="popularProducts.length">
          <h2>Bring <br>nature into <br>your home</h2>
          <button class="btn-primary" type="button">Shop Now</button>
        </div>

        <div class="sales-content" v-if="popularProducts.length">
          <div class="section-head" style="margin-bottom: 20px;">
            <h2>Daily Sales</h2>
          </div>

          <div class="sales-grid">
            <div class="sale-card" v-for="p in popularProducts.slice(0, 4)" :key="p.id">
              <div class="product-img">
                <img v-if="p.image" :src="productImageUrl(p)" :alt="p.name" />
                <img v-else src="https://images.unsplash.com/photo-1626200419199-391ae4be7a41?auto=format&fit=crop&w=400&q=80" alt="Product" />
              </div>
              <h3>{{ p.name }}</h3>
              <div class="price">
                <span class="current">{{ formatPrice(p.price) }}</span>
              </div>
              <button class="btn-primary full" type="button">🛒 Add to Cart</button>
            </div>
          </div>
        </div>

        <div class="sales-content" v-else>
          <div class="section-head" style="margin-bottom: 20px;">
            <h2>Daily Sales</h2>
          </div>
          <div class="sales-grid">
            <div class="sale-card">Loading...</div>
          </div>
        </div>

        <!-- Static content removed (was accidentally left in and broke template tags) -->
      </div>
    </section>


    <section class="trending-stories">
      <div class="section-head">
        <h2>Trending Stories</h2>
      </div>
      <div class="stories-grid">
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=600&q=80" alt="Vegetables" />
          </div>
          <div class="story-meta">10 July 2024 • Healthy Tips</div>
          <h3>Best way to keep your vegetables fresh and crispy for longer</h3>
          <a href="#" class="read-more">Read More →</a>
        </div>
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1494390248081-4e521a5940db?auto=format&fit=crop&w=600&q=80" alt="Breakfast" />
          </div>
          <div class="story-meta">10 July 2024 • Recipe</div>
          <h3>5 Quick and healthy breakfast ideas for busy mornings</h3>
          <a href="#" class="read-more">Read More →</a>
        </div>
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1595841696677-6489ff3f8cd1?auto=format&fit=crop&w=600&q=80" alt="Farm" />
          </div>
          <div class="story-meta">10 July 2024 • Market News</div>
          <h3>Meet our local farmers: A journey to the source of your food</h3>
          <a href="#" class="read-more">Read More →</a>
        </div>
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=600&q=80" alt="Nutrition" />
          </div>
          <div class="story-meta">10 July 2024 • Nutrition</div>
          <h3>Why seasonal eating is better for your health and the planet</h3>
          <a href="#" class="read-more">Read More →</a>
        </div>
      </div>
    </section>

    <AppFooter />
  </div>
</template>

<script setup lang="ts">
import { computed, onMounted, ref } from 'vue'
import axios from 'axios'
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const categories = ref<any[]>([])
const products = ref<any[]>([])
const loading = ref(false)

const fetchAll = async () => {
  loading.value = true
  try {
    const [cRes, pRes] = await Promise.all([
      axios.get(`${backendUrl}/api/categories`),
      axios.get(`${backendUrl}/api/products`),
    ])

    categories.value = cRes.data?.data || []
    products.value = pRes.data?.data || []
  } finally {
    loading.value = false
  }
}

const popularProducts = computed(() => products.value.slice(0, 10))
const featuredCategories = computed(() => categories.value.slice(0, 8))

const categoryColors = [
  'bg-blue',
  'bg-green',
  'bg-orange',
  'bg-yellow',
  'bg-teal',
  'bg-red',
  'bg-purple',
  'bg-lightgreen',
  'bg-pink',
]

const catIndex = (id: number) => {
  const idx = categories.value.findIndex((c) => c?.id === id)
  return idx === -1 ? 0 : idx
}

const colorForIndex = (index: number) => {
  const i = Math.abs(index) % categoryColors.length
  return categoryColors[i]
}

const catImageUrl = (cat: any) => {
  // Backend stores relative path in `image` (e.g. categories/xxx.jpg)
  // and files are served from /storage.
  return cat?.image ? `${backendUrl}/storage/${cat.image}` : null
}

const categoryProductCount = (categoryId: number) => {
  return products.value.filter((p) => p?.category_id === categoryId || p?.category?.id === categoryId)
    .length
}

const productImageUrl = (p: any) => {
  // Products table stores: image path (e.g. products/xxxx.jpg) in `image`
  // Backend stores files in `storage/app/public` with disk `public`.
  if (!p?.image) return null
  const base = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')
  return `${base}/storage/${p.image}`
}

const formatPrice = (price: any) => {
  const num = Number(price)
  if (Number.isNaN(num)) return '$0'
  return `$${num.toFixed(2)}`
}

const discountedOldPrice = (p: any) => {
  const price = Number(p?.price ?? 0)
  const disc = Number(p?.discount_percent ?? 0)
  const old = disc > 0 ? price / (1 - disc / 100) : price
  return old
}

// Optional: if you decide to add discount fields later, update this section.


onMounted(fetchAll)
</script>


<style scoped>
/* ---------- Design Tokens ---------- */
.home {
  --primary: #046c4e;
  --primary-hover: #03543f;
  --primary-light: #def7ec;
  --text-main: #1f2937;
  --text-muted: #6b7280;
  --bg-main: #ffffff;
  --bg-hero: #eef7f2;
  --border-color: #f3f4f6;
  --border-dark: #e5e7eb;
  
  --font-family: 'Quicksand', 'Inter', system-ui, sans-serif;
  
  font-family: var(--font-family);
  color: var(--text-main);
  background-color: var(--bg-main);
}

.home * {
  box-sizing: border-box;
}

h1, h2, h3, h4 {
  color: #111827;
  margin: 0;
  font-weight: 700;
}

/* ---------- Typography & Base ---------- */
h2 {
  font-size: 24px;
}
.section-head {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 24px;
}
.nav-tabs {
  display: flex;
  gap: 20px;
}
.nav-tabs a {
  text-decoration: none;
  color: var(--text-muted);
  font-size: 15px;
  font-weight: 600;
  transition: color 0.2s;
}
.nav-tabs a.active, .nav-tabs a:hover {
  color: var(--primary);
}

/* ---------- Buttons ---------- */
.btn-primary {
  background-color: var(--primary);
  color: #fff;
  border: none;
  border-radius: 30px;
  padding: 12px 24px;
  font-weight: 600;
  cursor: pointer;
  transition: background-color 0.2s;
}
.btn-primary:hover {
  background-color: var(--primary-hover);
}
.btn-primary.small {
  padding: 8px 16px;
  font-size: 13px;
}
.btn-primary.full {
  width: 100%;
  border-radius: 8px;
}
.btn-add {
  background-color: var(--primary-light);
  color: var(--primary);
  border: none;
  border-radius: 6px;
  padding: 8px 16px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  display: flex;
  align-items: center;
  gap: 6px;
}
.btn-add:hover {
  background-color: #c3eedb;
}

/* ---------- Hero Section ---------- */
.hero-wrapper {
  padding: 24px;
  max-width: 1400px;
  margin: 0 auto;
}
.hero {
  background-color: var(--bg-hero);
  border-radius: 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 60px 80px;
  overflow: hidden;
}
.hero-content {
  max-width: 500px;
}
.hero-content h1 {
  font-size: 56px;
  line-height: 1.1;
  margin-bottom: 16px;
}
.hero-content h1 span {
  color: var(--primary);
}
.lede {
  color: var(--text-muted);
  font-size: 18px;
  margin-bottom: 32px;
}
.subscribe-box {
  display: flex;
  align-items: center;
  background: #fff;
  border-radius: 40px;
  padding: 6px 6px 6px 20px;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}
.mail-icon {
  margin-right: 12px;
  color: var(--text-muted);
}
.subscribe-box input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 15px;
  font-family: inherit;
}
.hero-image {
  flex-shrink: 0;
  width: 50%;
  max-width: 500px;
}
.hero-image img {
  width: 100%;
  height: auto;
  border-radius: 16px;
  object-fit: cover;
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.1);
}

/* ---------- Featured Categories ---------- */
.featured-categories {
  max-width: 1400px;
  margin: 40px auto;
  padding: 0 24px;
}
.category-carousel {
  display: flex;
  gap: 16px;
  overflow-x: auto;
  padding-bottom: 16px;
}
.cat-card {
  min-width: 130px;
  border: 1px solid var(--border-color);
  border-radius: 12px;
  padding: 20px 10px;
  text-align: center;
  background: #fff;
  transition: border-color 0.2s, box-shadow 0.2s;
  cursor: pointer;
}
.cat-card:hover {
  border-color: var(--primary-light);
  box-shadow: 0 4px 12px rgba(4, 108, 78, 0.05);
}
.cat-icon {
  width: 70px;
  height: 70px;
  border-radius: 50%;
  margin: 0 auto 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 8px;
}
.cat-icon img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}
.cat-card h4 {
  font-size: 14px;
  margin-bottom: 4px;
}
.cat-card span {
  font-size: 12px;
  color: var(--text-muted);
}

/* Sub-colors for categories */
.bg-blue { background: #e0f2fe; }
.bg-green { background: #dcfce7; }
.bg-orange { background: #ffedd5; }
.bg-yellow { background: #fef08a; }
.bg-teal { background: #ccfbf1; }
.bg-red { background: #fee2e2; }
.bg-purple { background: #f3e8ff; }
.bg-lightgreen { background: #ecfccb; }
.bg-pink { background: #fce7f3; }

/* ---------- Promo Banners ---------- */
.promo-banners {
  max-width: 1400px;
  margin: 40px auto;
  padding: 0 24px;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}
.promo-card {
  border-radius: 16px;
  padding: 30px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
}
.bg-wheat { background: #f3e8d6; }
.bg-lightblue { background: #e0f2fe; }
.bg-tan { background: #fae8c8; }

.promo-content h3 {
  font-size: 20px;
  line-height: 1.3;
  margin-bottom: 16px;
}
.promo-img {
  width: 120px;
  height: 120px;
  flex-shrink: 0;
}
.promo-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 50%;
}

/* ---------- Popular Products ---------- */
.popular-products {
  max-width: 1400px;
  margin: 60px auto;
  padding: 0 24px;
}
.product-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 20px;
}
.product-card {
  border: 1px solid var(--border-dark);
  border-radius: 16px;
  padding: 20px;
  position: relative;
  background: #fff;
  transition: box-shadow 0.2s, transform 0.2s;
}
.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 10px 25px -5px rgba(0,0,0,0.05);
}
.badge {
  position: absolute;
  top: 16px;
  left: 16px;
  padding: 4px 12px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  z-index: 10;
}
.badge.hot { background: #fca5a5; color: #991b1b; }
.badge.sale { background: #93c5fd; color: #1e3a8a; }
.badge.new { background: #86efac; color: #166534; }

.product-img {
  height: 160px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 16px;
}
.product-img img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 8px;
}
.product-category {
  font-size: 12px;
  color: var(--text-muted);
  display: block;
  margin-bottom: 6px;
}
.product-card h3 {
  font-size: 15px;
  line-height: 1.4;
  margin-bottom: 10px;
  height: 42px;
  overflow: hidden;
}
.rating {
  font-size: 12px;
  color: #fbbf24;
  margin-bottom: 16px;
}
.rating span {
  color: var(--text-muted);
  margin-left: 4px;
}
.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.price .current {
  font-size: 18px;
  font-weight: 700;
  color: var(--primary);
  display: block;
}
.price .old {
  font-size: 13px;
  color: var(--text-muted);
  text-decoration: line-through;
}

/* ---------- Daily Sales ---------- */
.daily-sales-section {
  max-width: 1400px;
  margin: 60px auto;
  padding: 0 24px;
}
.sales-layout {
  display: flex;
  gap: 24px;
}
.sales-banner {
  flex: 0 0 300px;
  background: linear-gradient(rgba(17, 54, 41, 0.8), rgba(17, 54, 41, 0.9)), url('https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80') center/cover;
  border-radius: 16px;
  padding: 40px 30px;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
}
.sales-banner h2 {
  color: #fff;
  font-size: 32px;
  line-height: 1.2;
  margin-bottom: 24px;
}
.sales-content {
  flex: 1;
}
.sales-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}
.sale-card {
  border: 1px solid var(--border-dark);
  border-radius: 16px;
  padding: 20px;
  background: #fff;
}
.sale-card h3 {
  font-size: 14px;
  margin: 10px 0;
  height: 38px;
}
.progress-bar {
  background: var(--border-color);
  height: 6px;
  border-radius: 3px;
  margin: 12px 0 8px;
  overflow: hidden;
}
.progress-bar .fill {
  background: var(--primary);
  height: 100%;
}
.sold-text {
  font-size: 12px;
  color: var(--text-muted);
  margin-bottom: 16px;
}

/* ---------- Trending Stories ---------- */
.trending-stories {
  max-width: 1400px;
  margin: 60px auto;
  padding: 0 24px;
}
.stories-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}
.story-card {
  border-radius: 16px;
  overflow: hidden;
}
.story-img {
  background: var(--bg-hero);
  height: 200px;
  border-radius: 16px;
  margin-bottom: 16px;
  overflow: hidden;
}
.story-img img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}
.story-card:hover .story-img img {
  transform: scale(1.05);
}
.story-meta {
  font-size: 12px;
  color: var(--text-muted);
  margin-bottom: 8px;
}
.story-card h3 {
  font-size: 16px;
  line-height: 1.4;
  margin-bottom: 12px;
}
.read-more {
  font-size: 14px;
  color: var(--text-muted);
  text-decoration: none;
  font-weight: 600;
  transition: color 0.2s;
}
.read-more:hover {
  color: var(--primary);
}

/* Responsive adjustments */
@media (max-width: 1200px) {
  .product-grid { grid-template-columns: repeat(4, 1fr); }
  .sales-layout { flex-direction: column; }
  .sales-banner { flex: auto; width: 100%; min-height: 200px; }
  .stories-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 900px) {
  .hero { flex-direction: column; text-align: center; padding: 40px 20px; }
  .hero-image { width: 100%; margin-top: 32px; }
  .hero-content { margin-bottom: 0; }
  .promo-banners { grid-template-columns: 1fr; }
  .product-grid { grid-template-columns: repeat(2, 1fr); }
  .sales-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .product-grid, .sales-grid, .stories-grid { grid-template-columns: 1fr; }
}
</style>