<template>
  <div class="home">
    <AppNavbar />

    <!-- ============ HERO ============ -->
    <section class="hero-wrapper">
      <div class="hero">
        <div class="hero-content">
          <span class="eyebrow">🌿 Farm to table, every week</span>
          <h1>
            Eat food that<br />
            still tastes like <span>itself.</span>
          </h1>
          <p class="lede">
            Organic produce, picked this week, delivered to your door.
            Save up to 50% on your first order.
          </p>
          <div class="subscribe-box">
            <input type="email" placeholder="Your email address" />
            <button class="btn-primary">Get my discount</button>
          </div>
          <p class="hero-note">No spam. Unsubscribe anytime.</p>
        </div>
        <div class="hero-image">
          <div class="blob-frame">
            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80" alt="Fresh organic groceries" />
          </div>
          <div class="hero-chip hero-chip--top">100% Organic</div>
          <div class="hero-chip hero-chip--bottom">🚚 Free delivery over $50</div>
        </div>
      </div>
    </section>

    <!-- ============ FEATURED CATEGORIES ============ -->
    <section class="featured-categories">
      <div class="section-head">
        <div>
          <span class="section-eyebrow">Shop by category</span>
          <h2>Find what you need</h2>
        </div>
        <div v-if="loading" class="loading-tag">Loading…</div>
      </div>

      <div class="category-carousel" v-if="featuredCategories.length">
        <div class="cat-card" v-for="cat in featuredCategories" :key="cat.id">
          <div class="cat-blob" :class="colorForIndex(catIndex(cat.id))">
            <img v-if="catImageUrl(cat)" :src="catImageUrl(cat)" :alt="cat.name" />
            <span v-else class="cat-placeholder">{{ (cat.name || '?').slice(0, 1).toUpperCase() }}</span>
          </div>
          <h4>{{ cat.name }}</h4>
          <span>{{ categoryProductCount(cat.id) }} items</span>
        </div>
      </div>

      <div v-else class="category-carousel" aria-busy="true">
        <div class="cat-card cat-card--skeleton" v-for="n in 6" :key="n"></div>
      </div>
    </section>

    <!-- ============ PROMO BANNERS ============ -->
    <section class="promo-banners">
      <div class="promo-card bg-wheat">
        <div class="promo-content">
          <span class="promo-tag">Daily essentials</span>
          <h3>Everyday fresh, everyday clean</h3>
          <button class="btn-outline">Shop now →</button>
        </div>
        <div class="promo-img">
          <img src="https://images.unsplash.com/photo-1518977822534-7049a61ee0c2?auto=format&fit=crop&w=300&q=80" alt="Fresh Produce" />
        </div>
      </div>
      <div class="promo-card bg-lightblue">
        <div class="promo-content">
          <span class="promo-tag">Morning routine</span>
          <h3>Breakfast, made healthy and easy</h3>
          <button class="btn-outline">Shop now →</button>
        </div>
        <div class="promo-img">
          <img src="https://images.unsplash.com/photo-1600271886742-f049cd451bba?auto=format&fit=crop&w=300&q=80" alt="Healthy Juice" />
        </div>
      </div>
      <div class="promo-card bg-tan">
        <div class="promo-content">
          <span class="promo-tag">Best sellers</span>
          <h3>The organic products people reorder</h3>
          <button class="btn-outline">Shop now →</button>
        </div>
        <div class="promo-img">
          <img src="https://images.unsplash.com/photo-1528825871115-3581a5387915?auto=format&fit=crop&w=300&q=80" alt="Organic Greens" />
        </div>
      </div>
    </section>

    <!-- ============ POPULAR PRODUCTS ============ -->
    <section class="popular-products">
      <div class="section-head">
        <div>
          <span class="section-eyebrow">Customer favorites</span>
          <h2>Popular this week</h2>
        </div>
        <a href="#" class="view-all">View all →</a>
      </div>

      <div class="product-grid" v-if="popularProducts.length">
        <div class="product-card" v-for="p in popularProducts" :key="p.id">
          <span class="badge sale" v-if="p.discount_percent">-{{ p.discount_percent }}%</span>
          <div class="product-img">
            <img
              :src="p.image ? productImageUrl(p) : 'https://images.unsplash.com/photo-1586201375761-83865001e31c?auto=format&fit=crop&w=400&q=80'"
              :alt="p.name"
            />
          </div>
          <span class="product-category">{{ p?.category?.name || 'Uncategorized' }}</span>
          <h3>{{ p.name }}</h3>
          <div class="price-row">
            <div class="price">
              <span class="current">{{ formatPrice(p.price) }}</span>
              <span class="old" v-if="p.discount_percent">{{ formatPrice(discountedOldPrice(p)) }}</span>
            </div>
            <button class="btn-add" aria-label="Add to cart">+</button>
          </div>
        </div>
      </div>

      <div class="product-grid" v-else>
        <div class="product-card product-card--skeleton" v-for="n in 5" :key="n"></div>
      </div>
    </section>

    <!-- ============ DAILY SALES ============ -->
    <section class="daily-sales-section">
      <div class="sales-layout">
        <div class="sales-banner">
          <span class="promo-tag promo-tag--light">Limited time</span>
          <h2>Bring nature into your home</h2>
          <button class="btn-light">Shop the sale</button>
        </div>

        <div class="sales-content">
          <div class="section-head" style="margin-bottom: 20px;">
            <div>
              <span class="section-eyebrow">Today only</span>
              <h2>Daily sales</h2>
            </div>
          </div>

          <div class="sales-grid" v-if="popularProducts.length">
            <div class="sale-card" v-for="p in popularProducts.slice(0, 4)" :key="p.id">
              <div class="product-img">
                <img
                  :src="p.image ? productImageUrl(p) : 'https://images.unsplash.com/photo-1626200419199-391ae4be7a41?auto=format&fit=crop&w=400&q=80'"
                  :alt="p.name"
                />
              </div>
              <h3>{{ p.name }}</h3>
              <div class="price">
                <span class="current">{{ formatPrice(p.price) }}</span>
              </div>
              <button class="btn-primary full" type="button">Add to cart</button>
            </div>
          </div>

          <div class="sales-grid" v-else>
            <div class="sale-card sale-card--skeleton" v-for="n in 4" :key="n"></div>
          </div>
        </div>
      </div>
    </section>

    <!-- ============ TRENDING STORIES ============ -->
    <section class="trending-stories">
      <div class="section-head">
        <div>
          <span class="section-eyebrow">From the journal</span>
          <h2>Trending stories</h2>
        </div>
      </div>
      <div class="stories-grid">
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1566385101042-1a0aa0c1268c?auto=format&fit=crop&w=600&q=80" alt="Vegetables" />
          </div>
          <div class="story-meta">10 July 2024 · Healthy tips</div>
          <h3>The best way to keep vegetables fresh and crisp for longer</h3>
          <a href="#" class="read-more">Read more →</a>
        </div>
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1494390248081-4e521a5940db?auto=format&fit=crop&w=600&q=80" alt="Breakfast" />
          </div>
          <div class="story-meta">10 July 2024 · Recipe</div>
          <h3>5 quick and healthy breakfast ideas for busy mornings</h3>
          <a href="#" class="read-more">Read more →</a>
        </div>
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1595841696677-6489ff3f8cd1?auto=format&fit=crop&w=600&q=80" alt="Farm" />
          </div>
          <div class="story-meta">10 July 2024 · Market news</div>
          <h3>Meet our local farmers: a journey to the source of your food</h3>
          <a href="#" class="read-more">Read more →</a>
        </div>
        <div class="story-card">
          <div class="story-img">
            <img src="https://images.unsplash.com/photo-1490645935967-10de6ba17061?auto=format&fit=crop&w=600&q=80" alt="Nutrition" />
          </div>
          <div class="story-meta">10 July 2024 · Nutrition</div>
          <h3>Why seasonal eating is better for your health and the planet</h3>
          <a href="#" class="read-more">Read more →</a>
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
  return cat?.image ? `${backendUrl}/storage/${cat.image}` : null
}

const categoryProductCount = (categoryId: number) => {
  return products.value.filter((p) => p?.category_id === categoryId || p?.category?.id === categoryId)
    .length
}

const productImageUrl = (p: any) => {
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

onMounted(fetchAll)
</script>

<style scoped>
/* ============ DESIGN TOKENS ============ */
.home {
  --bg-page: #faf8f3;
  --bg-hero: #f0ede3;
  --ink: #2d2a26;
  --ink-muted: #79736a;
  --forest: #1b4332;
  --forest-hover: #14352678;
  --forest-hover-solid: #143526;
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
  background-color: var(--bg-page);
}

.home * {
  box-sizing: border-box;
}

h1, h2, h3, h4 {
  color: var(--ink);
  margin: 0;
  font-family: var(--font-display);
  font-weight: 600;
  letter-spacing: -0.01em;
}

h2 { font-size: 28px; }

.section-eyebrow {
  display: block;
  font-size: 12px;
  font-weight: 700;
  letter-spacing: 0.08em;
  text-transform: uppercase;
  color: var(--clay);
  margin-bottom: 6px;
}

.section-head {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 28px;
  gap: 16px;
}

.loading-tag {
  font-size: 12px;
  color: var(--ink-muted);
}

.view-all {
  text-decoration: none;
  color: var(--forest);
  font-weight: 700;
  font-size: 14px;
  white-space: nowrap;
}
.view-all:hover { color: var(--clay); }

/* ============ BUTTONS ============ */
.btn-primary {
  background-color: var(--forest);
  color: #fff;
  border: none;
  border-radius: 10px;
  padding: 13px 26px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  transition: background-color 0.2s, transform 0.15s;
  font-family: var(--font-body);
}
.btn-primary:hover { background-color: var(--forest-hover-solid); transform: translateY(-1px); }
.btn-primary.full { width: 100%; }

.btn-outline {
  background: transparent;
  color: var(--ink);
  border: 1.5px solid var(--ink);
  border-radius: 10px;
  padding: 9px 18px;
  font-weight: 700;
  font-size: 13px;
  cursor: pointer;
  transition: all 0.2s;
  font-family: var(--font-body);
}
.btn-outline:hover { background: var(--ink); color: #fff; }

.btn-light {
  background: #fff;
  color: var(--forest);
  border: none;
  border-radius: 10px;
  padding: 12px 22px;
  font-weight: 700;
  font-size: 14px;
  cursor: pointer;
  font-family: var(--font-body);
}
.btn-light:hover { background: var(--sage-light); }

.btn-add {
  background-color: var(--forest);
  color: #fff;
  border: none;
  border-radius: 50%;
  width: 36px;
  height: 36px;
  font-size: 20px;
  font-weight: 400;
  line-height: 1;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-shrink: 0;
  transition: background-color 0.2s;
}
.btn-add:hover { background-color: var(--clay); }

/* ============ HERO ============ */
.hero-wrapper {
  padding: 24px;
  max-width: 1320px;
  margin: 0 auto;
}
.hero {
  background-color: var(--bg-hero);
  border-radius: 28px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 64px 72px;
  gap: 40px;
  overflow: hidden;
}
.eyebrow {
  display: inline-block;
  font-size: 13px;
  font-weight: 700;
  color: var(--forest);
  background: var(--sage-light);
  padding: 6px 14px;
  border-radius: 30px;
  margin-bottom: 20px;
}
.hero-content { max-width: 480px; }
.hero-content h1 {
  font-size: 52px;
  line-height: 1.08;
  margin-bottom: 18px;
}
.hero-content h1 span { color: var(--clay); font-style: italic; }
.lede {
  color: var(--ink-muted);
  font-size: 17px;
  line-height: 1.5;
  margin: 0 0 28px;
}
.subscribe-box {
  display: flex;
  gap: 8px;
}
.subscribe-box input {
  flex: 1;
  border: 1.5px solid var(--border);
  background: #fff;
  border-radius: 10px;
  padding: 14px 18px;
  font-size: 14px;
  font-family: inherit;
  outline: none;
}
.subscribe-box input:focus { border-color: var(--forest); }
.hero-note {
  font-size: 12px;
  color: var(--ink-muted);
  margin: 10px 0 0;
}

.hero-image {
  flex-shrink: 0;
  width: 46%;
  max-width: 460px;
  position: relative;
}
.blob-frame {
  width: 100%;
  aspect-ratio: 1 / 1;
  clip-path: path('M30,180 C10,100 60,15 160,10 C260,5 330,60 330,170 C330,280 260,330 165,335 C70,340 50,260 30,180 Z');
  overflow: hidden;
}
.blob-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}
.hero-chip {
  position: absolute;
  background: #fff;
  border-radius: 12px;
  padding: 10px 16px;
  font-size: 13px;
  font-weight: 700;
  box-shadow: 0 8px 20px -6px rgba(27, 67, 50, 0.25);
}
.hero-chip--top { top: 4%; right: 0; color: var(--forest); }
.hero-chip--bottom { bottom: 6%; left: -8%; color: var(--clay); }

/* ============ FEATURED CATEGORIES ============ */
.featured-categories {
  max-width: 1320px;
  margin: 64px auto 0;
  padding: 0 24px;
}
.category-carousel {
  display: flex;
  gap: 18px;
  overflow-x: auto;
  padding-bottom: 8px;
}
.cat-card {
  min-width: 124px;
  text-align: center;
  cursor: pointer;
}
.cat-blob {
  width: 84px;
  height: 84px;
  margin: 0 auto 14px;
  display: flex;
  align-items: center;
  justify-content: center;
  overflow: hidden;
  padding: 6px;
  clip-path: path('M5,42 C5,15 22,2 44,2 C68,2 84,18 84,42 C84,67 66,82 43,82 C20,82 5,68 5,42 Z');
  transition: transform 0.2s;
}
.cat-card:hover .cat-blob { transform: scale(1.06); }
.cat-blob img { width: 100%; height: 100%; object-fit: cover; }
.cat-placeholder { font-family: var(--font-display); font-size: 26px; font-weight: 700; color: var(--ink); }
.cat-card h4 { font-size: 14px; font-weight: 700; margin-bottom: 4px; }
.cat-card span { font-size: 12px; color: var(--ink-muted); }

.cat-card--skeleton {
  height: 130px;
  background: var(--card-bg);
  border-radius: 16px;
  border: 1px solid var(--border);
}

.bg-blue { background: #dceefb; }
.bg-green { background: var(--sage-light); }
.bg-orange { background: var(--clay-light); }
.bg-yellow { background: #fbf0c6; }
.bg-teal { background: #d3f0e7; }
.bg-red { background: #fbdfdc; }
.bg-purple { background: #ece3f7; }
.bg-lightgreen { background: #eaf2d7; }
.bg-pink { background: #f9e2ed; }

/* ============ PROMO BANNERS ============ */
.promo-banners {
  max-width: 1320px;
  margin: 56px auto;
  padding: 0 24px;
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
}
.promo-card {
  border-radius: 20px;
  padding: 32px;
  display: flex;
  align-items: center;
  justify-content: space-between;
  overflow: hidden;
  gap: 12px;
}
.bg-wheat { background: #f1e9d9; }
.bg-lightblue { background: #e4eff5; }
.bg-tan { background: #f6e6c8; }

.promo-tag {
  display: inline-block;
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  color: var(--ink-muted);
  margin-bottom: 10px;
}
.promo-tag--light { color: rgba(255,255,255,0.75); }
.promo-content h3 {
  font-size: 19px;
  line-height: 1.3;
  margin-bottom: 18px;
}
.promo-img {
  width: 96px;
  height: 96px;
  flex-shrink: 0;
  clip-path: path('M3,48 C3,18 23,3 49,3 C77,3 95,21 95,49 C95,77 75,95 48,95 C22,95 3,76 3,48 Z');
}
.promo-img img { width: 100%; height: 100%; object-fit: cover; }

/* ============ POPULAR PRODUCTS ============ */
.popular-products {
  max-width: 1320px;
  margin: 64px auto;
  padding: 0 24px;
}
.product-grid {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 18px;
}
.product-card {
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 18px;
  position: relative;
  background: var(--card-bg);
  transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
}
.product-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 14px 28px -10px rgba(27, 67, 50, 0.12);
  border-color: var(--sage);
}
.product-card--skeleton { height: 270px; }

.badge {
  position: absolute;
  top: 14px;
  left: 14px;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 11px;
  font-weight: 700;
  z-index: 10;
}
.badge.sale { background: var(--clay); color: #fff; }

.product-img {
  height: 140px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 14px;
}
.product-img img {
  width: 100%;
  height: 100%;
  object-fit: contain;
  border-radius: 8px;
}
.product-category {
  font-size: 11px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.04em;
  color: var(--sage);
  display: block;
  margin-bottom: 6px;
}
.product-card h3 {
  font-size: 15px;
  font-weight: 600;
  line-height: 1.4;
  margin-bottom: 14px;
  height: 42px;
  overflow: hidden;
}
.price-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
}
.price .current {
  font-size: 17px;
  font-weight: 700;
  color: var(--forest);
  font-family: var(--font-display);
  display: block;
}
.price .old {
  font-size: 13px;
  color: var(--ink-muted);
  text-decoration: line-through;
}

/* ============ DAILY SALES ============ */
.daily-sales-section {
  max-width: 1320px;
  margin: 56px auto;
  padding: 0 24px;
}
.sales-layout {
  display: flex;
  gap: 20px;
}
.sales-banner {
  flex: 0 0 280px;
  background: linear-gradient(160deg, var(--forest) 0%, #0f291d 100%);
  border-radius: 20px;
  padding: 40px 30px;
  color: #fff;
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: flex-start;
}
.sales-banner h2 {
  color: #fff;
  font-size: 28px;
  line-height: 1.25;
  margin: 0 0 24px;
}
.sales-content { flex: 1; }
.sales-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 18px;
}
.sale-card {
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 18px;
  background: var(--card-bg);
}
.sale-card--skeleton { min-height: 230px; }
.sale-card h3 {
  font-size: 14px;
  font-weight: 600;
  margin: 10px 0 8px;
  height: 38px;
}

/* ============ TRENDING STORIES ============ */
.trending-stories {
  max-width: 1320px;
  margin: 64px auto 80px;
  padding: 0 24px;
}
.stories-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 22px;
}
.story-card { border-radius: 16px; }
.story-img {
  background: var(--bg-hero);
  height: 190px;
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
.story-card:hover .story-img img { transform: scale(1.05); }
.story-meta {
  font-size: 12px;
  font-weight: 600;
  color: var(--clay);
  margin-bottom: 8px;
}
.story-card h3 {
  font-size: 16px;
  font-weight: 600;
  line-height: 1.4;
  margin-bottom: 12px;
}
.read-more {
  font-size: 14px;
  color: var(--ink);
  text-decoration: none;
  font-weight: 700;
  transition: color 0.2s;
}
.read-more:hover { color: var(--clay); }

/* ============ RESPONSIVE ============ */
@media (max-width: 1200px) {
  .product-grid { grid-template-columns: repeat(4, 1fr); }
  .sales-layout { flex-direction: column; }
  .sales-banner { flex: auto; width: 100%; min-height: 160px; }
  .stories-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 900px) {
  .hero { flex-direction: column; text-align: center; padding: 44px 24px; }
  .hero-content { margin-bottom: 0; max-width: 100%; }
  .hero-image { width: 70%; margin-top: 32px; }
  .hero-chip--bottom { left: 4%; }
  .promo-banners { grid-template-columns: 1fr; }
  .product-grid { grid-template-columns: repeat(2, 1fr); }
  .sales-grid { grid-template-columns: repeat(2, 1fr); }
}
@media (max-width: 600px) {
  .product-grid, .sales-grid, .stories-grid { grid-template-columns: 1fr; }
  .hero-content h1 { font-size: 38px; }
  .subscribe-box { flex-direction: column; }
}
</style>