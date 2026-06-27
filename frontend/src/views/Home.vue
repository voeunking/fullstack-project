<template>
  <div class="home">

    <AppNavbar />

    <!-- HERO -->
    <section class="hero">

      <div class="hero-inner">

        <div class="hero-content">

          <div class="eyebrow">
            <span class="dot"></span>
            KING TECH STORE — EST. 2018
          </div>

          <h1>
            Tech that earns
            <span>its specs.</span>
          </h1>

          <p class="lede">
            Curated laptops, desktops, and accessories — benchmarked,
            warrantied, and shipped from our own warehouse. No marketplace
            roulette. No mystery sellers.
          </p>

          <div class="cta-row">
            <button class="primary" @click="goToProducts">
              Browse catalog
              <span class="arrow">→</span>
            </button>

            <button class="secondary" @click="goToAuth">
              Sign in
            </button>
          </div>

          <div class="stat-row">
            <div class="stat">
              <span class="stat-num">500+</span>
              <span class="stat-label">SKUs in stock</span>
            </div>
            <div class="stat">
              <span class="stat-num">10,412</span>
              <span class="stat-label">orders shipped</span>
            </div>
            <div class="stat">
              <span class="stat-num">4.8 / 5</span>
              <span class="stat-label">avg. rating</span>
            </div>
          </div>

        </div>

        <!-- SPEC CARD -->
        <div class="spec-card">

          <div class="spec-card-head">
            <span class="spec-card-title">FEATURED — THIS WEEK</span>
            <span class="spec-card-tag">IN STOCK</span>
          </div>

          <div class="spec-card-product">
            <div class="spec-thumb">💻</div>
            <div>
              <h2>Forge 14 Pro Laptop</h2>
              <p>14" / 32GB RAM / 1TB NVMe</p>
            </div>
          </div>

          <dl class="spec-list">
            <div class="spec-row">
              <dt>Processor</dt>
              <dd>12-core, up to 4.8GHz</dd>
            </div>
            <div class="spec-row">
              <dt>Display</dt>
              <dd>2.8K OLED, 120Hz</dd>
            </div>
            <div class="spec-row">
              <dt>Battery</dt>
              <dd>Up to 18 hrs</dd>
            </div>
            <div class="spec-row">
              <dt>Warranty</dt>
              <dd>3-year, parts &amp; labor</dd>
            </div>
          </dl>

          <div class="spec-card-foot">
            <div class="price">
              <span class="price-now">$1,349</span>
              <span class="price-was">$1,599</span>
            </div>
            <button class="card-btn" @click="goToProducts">
              View product
            </button>
          </div>

        </div>

      </div>

    </section>


    <!-- CATEGORIES -->
    <section class="categories">

      <div class="section-head">
        <span class="section-kicker">01 / SHOP</span>
        <h2>Shop by category</h2>
        <p>Four ways to find exactly what your setup needs.</p>
      </div>

      <div class="category-grid">

        <div class="category" @click="goToProducts">
          <span class="cat-icon">💻</span>
          <h3>Laptops</h3>
          <p>Ultraportables to workstation-class rigs.</p>
          <span class="cat-meta">128 products</span>
        </div>

        <div class="category" @click="goToProducts">
          <span class="cat-icon">🖥️</span>
          <h3>Desktops</h3>
          <p>Pre-built towers and custom build kits.</p>
          <span class="cat-meta">96 products</span>
        </div>

        <div class="category" @click="goToProducts">
          <span class="cat-icon">🎧</span>
          <h3>Accessories</h3>
          <p>Keyboards, mice, headsets, and cables.</p>
          <span class="cat-meta">214 products</span>
        </div>

        <div class="category" @click="goToProducts">
          <span class="cat-icon">📱</span>
          <h3>Devices</h3>
          <p>Tablets, smartwatches, and smart home gear.</p>
          <span class="cat-meta">87 products</span>
        </div>

      </div>

    </section>


    <!-- FEATURES -->
    <section class="features">

      <div class="section-head">
        <span class="section-kicker">02 / WHY US</span>
        <h2>Built for people who read the spec sheet</h2>
        <p>Three things we don't compromise on, on every order.</p>
      </div>

      <div class="feature-grid">

        <div class="feature">
          <span class="feature-num">01</span>
          <h3>Verified inventory</h3>
          <p>
            Every listing reflects real stock in our own warehouse —
            checked daily, never drop-shipped from a third party.
          </p>
        </div>

        <div class="feature">
          <span class="feature-num">02</span>
          <h3>Encrypted checkout</h3>
          <p>
            Payments run through PCI-compliant processing with 256-bit
            encryption, so your card details never touch our servers.
          </p>
        </div>

        <div class="feature">
          <span class="feature-num">03</span>
          <h3>2-day shipping</h3>
          <p>
            Orders placed before 3pm ship same day. Track every order
            from warehouse to doorstep in your account.
          </p>
        </div>

      </div>

    </section>


    <!-- TESTIMONIAL -->
    <section class="testimonial">
      <p class="quote">
        “Ordered a desktop build on a Tuesday, had it benchmarked and
        running by Thursday. Support actually knew the hardware.”
      </p>
      <div class="quote-author">
        <span class="author-name">D. Okafor</span>
        <span class="author-meta">Verified buyer — Desktop, Lagos</span>
      </div>
    </section>


    <!-- CTA -->
    <section class="bottom-banner">

      <div class="banner-grid">
        <span class="dot light"></span>
        <span class="dot light"></span>
        <span class="dot light"></span>
      </div>

      <h2>Your next setup is in stock.</h2>
      <p>Create an account to track orders, save builds, and check out faster.</p>

      <button @click="goToProducts">
        Start shopping
        <span class="arrow">→</span>
      </button>

    </section>

  </div>
</template>



<script setup lang="ts">

import { useRouter } from 'vue-router'
import AppNavbar from '../components/AppNavbar.vue'

const router = useRouter()

const goToProducts = () => {
  router.push('/dashboard/products')
}

const goToAuth = () => {
  const token = localStorage.getItem('token')
  router.push(token ? '/dashboard/products' : '/register')
}

</script>



<style scoped>

/* ---------- Design tokens ---------- */
.home{
  --ink: #0b0e14;
  --ink-soft: #4a5163;
  --paper: #f7f7f5;
  --panel: #ffffff;
  --line: #e3e2dd;
  --accent: #5b8def;
  --accent-dim: #eef3fd;
  --signal: #ff6a39;

  --font-display: 'Sora', 'Space Grotesk', system-ui, sans-serif;
  --font-body: 'Inter', system-ui, sans-serif;
  --font-mono: 'JetBrains Mono', 'Roboto Mono', monospace;

  min-height: 100vh;
  background: var(--paper);
  color: var(--ink);
  font-family: var(--font-body);
}

.home *{
  box-sizing: border-box;
}

.home h1,
.home h2,
.home h3{
  font-family: var(--font-display);
  letter-spacing: -0.02em;
}


/* ---------- Hero ---------- */
.hero{
  padding: 96px 24px 80px;
  border-bottom: 1px solid var(--line);
}

.hero-inner{
  max-width: 1180px;
  margin: 0 auto;
  display: grid;
  grid-template-columns: 1.15fr .85fr;
  gap: 64px;
  align-items: start;
}

@media (max-width: 900px){
  .hero-inner{
    grid-template-columns: 1fr;
  }
}

.eyebrow{
  display: inline-flex;
  align-items: center;
  gap: 8px;
  font-family: var(--font-mono);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.08em;
  color: var(--ink-soft);
}

.eyebrow .dot{
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--signal);
  display: inline-block;
}

h1{
  font-size: 56px;
  line-height: 1.05;
  font-weight: 700;
  margin: 18px 0 20px;
}

h1 span{
  color: var(--accent);
}

.lede{
  font-size: 18px;
  line-height: 1.65;
  color: var(--ink-soft);
  max-width: 520px;
  margin: 0;
}

.cta-row{
  display: flex;
  gap: 14px;
  margin-top: 32px;
}

button{
  cursor: pointer;
  font-family: var(--font-body);
  font-weight: 600;
  border: none;
}

.primary{
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 22px;
  border-radius: 10px;
  color: white;
  background: var(--ink);
  font-size: 15px;
  transition: transform .15s ease, background .15s ease;
}

.primary:hover{
  background: var(--accent);
  transform: translateY(-2px);
}

.secondary{
  padding: 14px 22px;
  border-radius: 10px;
  background: transparent;
  border: 1px solid var(--line);
  color: var(--ink);
  font-size: 15px;
  transition: border-color .15s ease, background .15s ease;
}

.secondary:hover{
  border-color: var(--ink);
  background: var(--panel);
}

.arrow{
  transition: transform .15s ease;
}

.primary:hover .arrow{
  transform: translateX(3px);
}

.stat-row{
  display: flex;
  gap: 36px;
  margin-top: 48px;
  padding-top: 28px;
  border-top: 1px solid var(--line);
}

.stat{
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.stat-num{
  font-family: var(--font-mono);
  font-size: 22px;
  font-weight: 700;
}

.stat-label{
  font-size: 13px;
  color: var(--ink-soft);
}


/* ---------- Spec card (signature element) ---------- */
.spec-card{
  background: var(--panel);
  border: 1px solid var(--line);
  border-radius: 16px;
  padding: 0;
  overflow: hidden;
}

.spec-card-head{
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 16px 22px;
  background: var(--ink);
}

.spec-card-title{
  font-family: var(--font-mono);
  font-size: 11px;
  letter-spacing: 0.08em;
  color: #cfd6e6;
}

.spec-card-tag{
  font-family: var(--font-mono);
  font-size: 11px;
  font-weight: 700;
  color: #7ee787;
}

.spec-card-product{
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 24px 22px 18px;
}

.spec-thumb{
  font-size: 34px;
  width: 56px;
  height: 56px;
  display: flex;
  align-items: center;
  justify-content: center;
  background: var(--accent-dim);
  border-radius: 12px;
  flex-shrink: 0;
}

.spec-card-product h2{
  font-size: 18px;
  margin: 0 0 4px;
}

.spec-card-product p{
  margin: 0;
  font-family: var(--font-mono);
  font-size: 12.5px;
  color: var(--ink-soft);
}

.spec-list{
  margin: 0;
  padding: 4px 22px 6px;
}

.spec-row{
  display: flex;
  justify-content: space-between;
  padding: 11px 0;
  border-top: 1px solid var(--line);
  font-size: 14px;
}

.spec-row dt{
  color: var(--ink-soft);
}

.spec-row dd{
  margin: 0;
  font-family: var(--font-mono);
  font-weight: 600;
}

.spec-card-foot{
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 14px;
  padding: 20px 22px 24px;
  border-top: 1px solid var(--line);
}

.price{
  display: flex;
  align-items: baseline;
  gap: 8px;
}

.price-now{
  font-family: var(--font-mono);
  font-size: 20px;
  font-weight: 700;
}

.price-was{
  font-family: var(--font-mono);
  font-size: 13px;
  color: var(--ink-soft);
  text-decoration: line-through;
}

.card-btn{
  padding: 11px 18px;
  border-radius: 9px;
  background: var(--accent);
  color: white;
  font-size: 14px;
  white-space: nowrap;
  transition: background .15s ease;
}

.card-btn:hover{
  background: var(--ink);
}


/* ---------- Shared section heads ---------- */
.section-head{
  max-width: 560px;
  margin: 0 0 40px;
}

.section-kicker{
  font-family: var(--font-mono);
  font-size: 12px;
  font-weight: 600;
  letter-spacing: 0.08em;
  color: var(--accent);
}

h2{
  font-size: 32px;
  font-weight: 700;
  margin: 12px 0 10px;
}

.section-head p{
  color: var(--ink-soft);
  font-size: 16px;
  margin: 0;
}


/* ---------- Categories ---------- */
.categories{
  padding: 88px 24px;
  max-width: 1180px;
  margin: 0 auto;
}

.category-grid{
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 1px;
  background: var(--line);
  border: 1px solid var(--line);
  border-radius: 16px;
  overflow: hidden;
}

@media (max-width: 800px){
  .category-grid{
    grid-template-columns: 1fr;
  }
}

.category{
  background: var(--panel);
  padding: 32px 26px;
  cursor: pointer;
  transition: background .15s ease;
}

.category:hover{
  background: var(--accent-dim);
}

.cat-icon{
  font-size: 28px;
  display: block;
  margin-bottom: 14px;
}

.category h3{
  font-size: 17px;
  margin: 0 0 6px;
}

.category p{
  color: var(--ink-soft);
  font-size: 14px;
  margin: 0 0 16px;
  line-height: 1.5;
}

.cat-meta{
  font-family: var(--font-mono);
  font-size: 11.5px;
  color: var(--accent);
  font-weight: 600;
}


/* ---------- Features ---------- */
.features{
  padding: 0 24px 88px;
  max-width: 1180px;
  margin: 0 auto;
}

.feature-grid{
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 28px;
}

@media (max-width: 800px){
  .feature-grid{
    grid-template-columns: 1fr;
  }
}

.feature{
  border-top: 2px solid var(--ink);
  padding-top: 18px;
}

.feature-num{
  font-family: var(--font-mono);
  font-size: 13px;
  color: var(--accent);
  font-weight: 700;
}

.feature h3{
  font-size: 18px;
  margin: 10px 0 8px;
}

.feature p{
  color: var(--ink-soft);
  font-size: 14.5px;
  line-height: 1.6;
  margin: 0;
}


/* ---------- Testimonial ---------- */
.testimonial{
  max-width: 720px;
  margin: 0 auto;
  padding: 0 24px 96px;
  text-align: center;
}

.quote{
  font-family: var(--font-display);
  font-size: 24px;
  line-height: 1.5;
  font-weight: 500;
  margin: 0 0 20px;
}

.quote-author{
  display: flex;
  flex-direction: column;
  gap: 2px;
}

.author-name{
  font-weight: 700;
  font-size: 14px;
}

.author-meta{
  font-family: var(--font-mono);
  font-size: 12px;
  color: var(--ink-soft);
}


/* ---------- Bottom banner ---------- */
.bottom-banner{
  position: relative;
  margin: 0 24px 64px;
  max-width: 1132px;
  margin-inline: auto;
  padding: 72px 24px;
  border-radius: 20px;
  text-align: center;
  background: var(--ink);
  color: white;
  overflow: hidden;
}

.banner-grid{
  position: absolute;
  top: 24px;
  left: 24px;
  display: flex;
  gap: 6px;
}

.banner-grid .dot.light{
  width: 6px;
  height: 6px;
  border-radius: 50%;
  background: var(--signal);
  opacity: .9;
}

.bottom-banner h2{
  font-size: 30px;
  margin: 0 0 10px;
}

.bottom-banner p{
  color: #c2c7d4;
  font-size: 15.5px;
  margin: 0 0 28px;
}

.bottom-banner button{
  display: inline-flex;
  align-items: center;
  gap: 8px;
  padding: 14px 26px;
  border-radius: 10px;
  background: var(--accent);
  color: white;
  font-size: 15px;
  transition: transform .15s ease, background .15s ease;
}

.bottom-banner button:hover{
  background: var(--signal);
  transform: translateY(-2px);
}

</style>