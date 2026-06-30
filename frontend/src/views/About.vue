<template>
  <AppNavbar />

  <div class="about-page">
    
    <section class="about-hero-banner">
      <div class="hero-banner-inner">
        <h1>Our Journey to Your Table</h1>
        <p>Founded on the belief that everyone deserves access to fresh, organic, nutrient-dense foods, we source locally to feed your kitchen.</p>
        <button class="btn-green-pill">Explore Our Farms</button>
      </div>
    </section>

    <section class="commitment-section">
      <div class="commitment-grid">
        <div class="commitment-content-box">
          <span class="eyebrow-tag">OUR MISSION</span>
          <h2>Commitment to the Earth, Health, and You</h2>
          <p>At Organic Market, our mission is to cultivate a healthier world by making premium organic products available to everyone. We believe that sustainable farming isn't just a method; it's a responsibility to the future of our planet.</p>
          <p>Every fruit, vegetable, and dairy asset is curated for its ecological impact and nutritional density. We don't just sell food; we support a lifestyle of transparency and vitality.</p>
          
          <div class="commitment-perks">
            <div class="perk-item">
              <span class="perk-icon">🌱</span>
              <span>100% Certified Organic</span>
            </div>
            <div class="perk-item">
              <span class="perk-icon">🚛</span>
              <span>Farm-to-Home Delivery</span>
            </div>
          </div>
        </div>
        
        <div class="commitment-image-wrapper">
          <img src="https://images.unsplash.com/photo-1595855759920-86582396756a?auto=format&fit=crop&w=800&q=80" alt="Happy farmers holding organic vegetable basket" />
          <div class="floating-stat-badge">
            <span class="stat-number">12+</span>
            <span class="stat-label">Years of Trust</span>
          </div>
        </div>
      </div>
    </section>

    <section class="timeline-section">
      <h2 class="section-center-title">How We Started</h2>
      <div class="underline-decor"></div>
      
      <div class="timeline-cards-grid">
        <div class="timeline-card">
          <span class="timeline-year">2012</span>
          <h3>The First Seed</h3>
          <p>Organic Market began as a small roadside stand in the countryside, founded by local siblings with a passion for holistic services and animal health.</p>
        </div>

        <div class="timeline-card">
          <span class="timeline-year">2018</span>
          <h3>Community Hub</h3>
          <p>We opened our first brick-and-mortar location, partnering with over 20 local sustainable farms to bring a full range of organic staple items to the city.</p>
        </div>

        <div class="timeline-card">
          <span class="timeline-year">Today</span>
          <h3>Digital Freshness</h3>
          <p>Now, we deliver the farm experience directly to your door, utilizing innovative, eco-friendly logistics to map our certified conscious trail email by email.</p>
        </div>
      </div>
    </section>

    <section class="core-values-section">
      <h2 class="section-center-title">Our Core Values</h2>
      <div class="underline-decor"></div>

      <div class="values-asymmetric-layout">
        <div class="value-card-block main-green">
          <div class="value-icon-bg">🍃</div>
          <div class="value-text-wrap">
            <h3>Sustainability</h3>
            <p>We work online with farming partners that replenish the soil and protect biodiversity for future generations.</p>
          </div>
        </div>

        <div class="values-sub-column">
          <div class="value-card-block accent-lime">
            <div class="inline-header">
              <span class="small-badge">Freshness Guarantee</span>
            </div>
            <h3>From Soil to Table in 24 Hours</h3>
          </div>

          <div class="values-twin-row">
            <div class="value-card-block light-blue">
              <span class="sub-icon">🏘️</span>
              <h3>Community</h3>
              <p>Supporting local economies and small-scale family farmers.</p>
            </div>
            <div class="value-card-block soft-blue">
              <span class="sub-icon">🔒</span>
              <h3>Transparency</h3>
              <p>Clean labels and open-source sourcing for every product.</p>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="partners-section">
      <div class="partners-header">
        <h2>Meet Our Partners</h2>
        <p>The hands and hearts behind your daily essentials.</p>
      </div>

      <div class="partners-team-grid">
        <div class="partner-member-card">
          <div class="avatar-holder">
            <img src="https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=400&q=80" alt="Sam and Susan" />
          </div>
          <h4>Sam and Susan</h4>
          <p class="partner-meta">Green Valley Orchard</p>
        </div>

        <div class="partner-member-card">
          <div class="avatar-holder">
            <img src="https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=400&q=80" alt="Elena Rodriguez" />
          </div>
          <h4>Elena Rodriguez</h4>
          <p class="partner-meta">Sunlight Leafy Greens</p>
        </div>

        <div class="partner-member-card">
          <div class="avatar-holder">
            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?auto=format&fit=crop&w=400&q=80" alt="Marcus Thorne" />
          </div>
          <h4>Marcus Thorne</h4>
          <p class="partner-meta">Thorne Family Vineyards</p>
        </div>

        <div class="partner-member-card">
          <div class="avatar-holder">
            <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?auto=format&fit=crop&w=400&q=80" alt="The Miller Sisters" />
          </div>
          <h4>The Miller Sisters</h4>
          <p class="partner-meta">Root & Flower Farm</p>
        </div>
      </div>
    </section>

    <section class="bottom-cta-strip">
      <div class="cta-strip-inner">
        <h2>Ready to taste the difference?</h2>
        <p>Join our community of conscious eaters today and get 20% off your first organic basket.</p>
        <div class="cta-buttons-wrapper">
          <button class="btn-dark-pill">Shop Now</button>
          <button class="btn-transparent-pill">Join Our Newsletter</button>
        </div>
      </div>
    </section>

  </div>

  <AppFooter />
</template>

<script setup lang="ts">
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'

</script>

<style scoped>
/* ---------- Page Layout Base Reset ---------- */
.about-page {
  max-width: 1400px;
  margin: 0 auto;
  padding: 24px;
  font-family: 'Quicksand', 'Inter', system-ui, sans-serif;
  color: #253d4e;
}

/* ---------- Full Bleed Top Jumbotron Banner Style ---------- */
.about-hero-banner {
  min-height: 400px;
  border-radius: 24px;
  background: 
    linear-gradient(to bottom, rgba(0,0,0,0.3), rgba(0,0,0,0.1)), 
    url('https://images.unsplash.com/photo-1500937386664-56d1dfef3854?auto=format&fit=crop&w=1400&q=80') center/cover;
  display: flex;
  align-items: center;
  padding: 60px;
  margin-bottom: 64px;
}

.hero-banner-inner {
  max-width: 600px;
  color: #ffffff;
}

.hero-banner-inner h1 {
  font-size: 48px;
  font-weight: 700;
  margin: 0 0 16px 0;
  line-height: 1.1;
}

.hero-banner-inner p {
  font-size: 18px;
  line-height: 1.6;
  margin: 0 0 32px 0;
  color: rgba(255, 255, 255, 0.95);
}

.btn-green-pill {
  padding: 14px 28px;
  background-color: #3bb77e;
  color: #ffffff;
  font-weight: 700;
  border: none;
  border-radius: 30px;
  cursor: pointer;
  font-size: 15px;
  transition: background-color 0.2s;
}

.btn-green-pill:hover {
  background-color: #2aa268;
}

/* ---------- Commitment Split Media Section ---------- */
.commitment-section {
  margin-bottom: 80px;
}

.commitment-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 48px;
  align-items: center;
}

.eyebrow-tag {
  font-size: 13px;
  text-transform: uppercase;
  letter-spacing: 1px;
  color: #7e7e7e;
  font-weight: 700;
  display: block;
  margin-bottom: 8px;
}

.commitment-content-box h2 {
  font-size: 36px;
  line-height: 1.2;
  font-weight: 700;
  color: #253d4e;
  margin: 0 0 20px 0;
}

.commitment-content-box p {
  font-size: 15px;
  line-height: 1.7;
  color: #7e7e7e;
  margin: 0 0 16px 0;
}

.commitment-perks {
  display: flex;
  gap: 24px;
  margin-top: 24px;
}

.perk-item {
  display: flex;
  align-items: center;
  gap: 10px;
  font-size: 14px;
  font-weight: 700;
  color: #253d4e;
}

.perk-icon {
  font-size: 18px;
}

.commitment-image-wrapper {
  position: relative;
  border-radius: 20px;
  overflow: hidden;
  height: 420px;
}

.commitment-image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.floating-stat-badge {
  position: absolute;
  bottom: 24px;
  left: 24px;
  background: #3bb77e;
  color: white;
  padding: 16px 24px;
  border-radius: 12px;
  display: flex;
  flex-direction: column;
  align-items: center;
}

.stat-number {
  font-size: 32px;
  font-weight: 800;
  line-height: 1;
}

.stat-label {
  font-size: 12px;
  font-weight: 600;
  opacity: 0.9;
}

/* ---------- Reusable Section Typography Decorators ---------- */
.section-center-title {
  text-align: center;
  font-size: 32px;
  font-weight: 700;
  color: #253d4e;
  margin: 0 0 8px 0;
}

.underline-decor {
  width: 60px;
  height: 3px;
  background-color: #3bb77e;
  margin: 0 auto 40px auto;
  border-radius: 2px;
}

/* ---------- Timeline Elements Section ---------- */
.timeline-section {
  margin-bottom: 80px;
}

.timeline-cards-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
}

.timeline-card {
  background: #ffffff;
  border: 1px solid #ececec;
  border-radius: 16px;
  padding: 32px;
  transition: transform 0.2s;
}

.timeline-card:hover {
  transform: translateY(-4px);
}

.timeline-year {
  font-size: 28px;
  font-weight: 800;
  color: #cbd5e1;
  display: block;
  margin-bottom: 12px;
}

.timeline-card h3 {
  font-size: 20px;
  font-weight: 700;
  margin: 0 0 12px 0;
  color: #253d4e;
}

.timeline-card p {
  font-size: 14px;
  line-height: 1.6;
  color: #7e7e7e;
  margin: 0;
}

/* ---------- Core Values Block Matrix Layout ---------- */
.core-values-section {
  margin-bottom: 80px;
}

.values-asymmetric-layout {
  display: grid;
  grid-template-columns: 40% 60%;
  gap: 24px;
}

.value-card-block {
  border-radius: 16px;
  padding: 32px;
}

/* Asymmetric Block Layout Colors matching Image 3 */
.value-card-block.main-green {
  background-color: #046c4e;
  color: #ffffff;
  display: flex;
  flex-direction: column;
  justify-content: flex-end;
  min-height: 380px;
}

.value-icon-bg {
  font-size: 48px;
  margin-bottom: auto;
}

.value-card-block.main-green h3 {
  font-size: 28px;
  margin: 0 0 12px 0;
}

.value-card-block.main-green p {
  font-size: 15px;
  line-height: 1.6;
  margin: 0;
  color: rgba(255,255,255,0.85);
}

.values-sub-column {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.value-card-block.accent-lime {
  background-color: #c0f285;
  color: #253d4e;
  display: flex;
  flex-direction: column;
  justify-content: center;
  min-height: 160px;
}

.small-badge {
  font-size: 11px;
  text-transform: uppercase;
  font-weight: 700;
  color: #046c4e;
}

.value-card-block.accent-lime h3 {
  font-size: 24px;
  margin: 8px 0 0 0;
  font-weight: 700;
}

.values-twin-row {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

.value-card-block.light-blue { background-color: #e0f2fe; }
.value-card-block.soft-blue  { background-color: #e0e7ff; }

.sub-icon {
  font-size: 24px;
  display: block;
  margin-bottom: 12px;
}

.values-twin-row h3 {
  font-size: 18px;
  margin: 0 0 8px 0;
}

.values-twin-row p {
  font-size: 14px;
  line-height: 1.5;
  margin: 0;
  color: #6b7280;
}

/* ---------- Meet Our Partners Team Row ---------- */
.partners-section {
  margin-bottom: 80px;
}

.partners-header {
  margin-bottom: 32px;
}

.partners-header h2 {
  font-size: 32px;
  font-weight: 700;
  margin: 0 0 4px 0;
}

.partners-header p {
  color: #7e7e7e;
  margin: 0;
}

.partners-team-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 24px;
}

.partner-member-card {
  text-align: center;
}

.avatar-holder {
  height: 280px;
  border-radius: 16px;
  overflow: hidden;
  margin-bottom: 16px;
}

.avatar-holder img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s;
}

.partner-member-card:hover .avatar-holder img {
  transform: scale(1.05);
}

.partner-member-card h4 {
  font-size: 18px;
  font-weight: 700;
  margin: 0 0 4px 0;
}

.partner-meta {
  font-size: 13px;
  color: #7e7e7e;
  margin: 0;
}

/* ---------- Lower Contoured Promo Strip Wrapper ---------- */
.bottom-cta-strip {
  background-color: #3bb77e;
  border-radius: 24px;
  padding: 60px;
  text-align: center;
  color: #ffffff;
  background-image: radial-gradient(circle at top right, rgba(255,255,255,0.15) 0%, transparent 60%);
}

.cta-strip-inner max-width {
  max-width: 650px;
  margin: 0 auto;
}

.cta-strip-inner h2 {
  font-size: 36px;
  font-weight: 700;
  margin: 0 0 12px 0;
}

.cta-strip-inner p {
  font-size: 16px;
  margin: 0 0 32px 0;
  opacity: 0.95;
}

.cta-buttons-wrapper {
  display: flex;
  justify-content: center;
  gap: 16px;
}

.btn-dark-pill {
  padding: 14px 32px;
  background-color: #253d4e;
  color: #ffffff;
  border: none;
  border-radius: 30px;
  font-weight: 700;
  cursor: pointer;
}

.btn-transparent-pill {
  padding: 14px 32px;
  background-color: transparent;
  color: #ffffff;
  border: 1px solid rgba(255,255,255,0.6);
  border-radius: 30px;
  font-weight: 700;
  cursor: pointer;
  transition: background-color 0.2s;
}

.btn-transparent-pill:hover {
  background-color: rgba(255,255,255,0.1);
}

/* ---------- Responsive Viewports Adjustments ---------- */
@media (max-width: 1024px) {
  .commitment-grid,
  .values-asymmetric-layout {
    grid-template-columns: 1fr;
  }
  .timeline-cards-grid,
  .partners-team-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 640px) {
  .timeline-cards-grid,
  .partners-team-grid,
  .values-twin-row {
    grid-template-columns: 1fr;
  }
  .about-hero-banner {
    padding: 32px 20px;
  }
  .hero-banner-inner h1 {
    font-size: 32px;
  }
  .bottom-cta-strip {
    padding: 32px 20px;
  }
  .cta-buttons-wrapper {
    flex-direction: column;
  }
}
</style>