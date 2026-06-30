<template>
  <AppNavbar />
  <div class="contact-page">
    
    <div class="page-header">
      <h1>Get in touch</h1>
      <p class="sub">We are always ready to help you with your organic grocery needs. Reach out to us through any of the channels below.</p>
    </div>

    <div class="contact-cards-row">
      <div class="info-card">
        <div class="info-icon bg-light-green">
          <span class="emoji-icon">📍</span>
        </div>
        <h3>Office Address</h3>
        <p class="card-desc">Visit our main hub for a fresh experience.</p>
        <p class="contact-detail highlight-text">Bishlag, Tolarbag, Mirpur 01,<br>Dhaka-1216, Bangladesh</p>
      </div>

      <div class="info-card">
        <div class="info-icon bg-light-yellow">
          <span class="emoji-icon">📞</span>
        </div>
        <h3>Phone Number</h3>
        <p class="card-desc">Call us anytime for urgent inquiries.</p>
        <p class="contact-detail highlight-text">+880 1521 199302<br>+880 1789 456123</p>
      </div>

      <div class="info-card">
        <div class="info-icon bg-light-pink">
          <span class="emoji-icon">✉️</span>
        </div>
        <h3>Support Email</h3>
        <p class="card-desc">Send us an email and we'll reply fast.</p>
        <p class="contact-detail highlight-text">support@organicmarket.com<br>contact@organicmarket.com</p>
      </div>
    </div>

    <div class="contact-content">
      <div class="contact-form-section">
        <h2>Drop us a line</h2>
        <p class="form-instructions">Your email address will not be published. Required fields are marked *</p>
        
        <form @submit.prevent="submitForm" class="contact-form">
          <div class="form-row-twin">
            <div class="form-group">
              <label>Your Name *</label>
              <input v-model="form.name" type="text" placeholder="John Doe" required />
            </div>

            <div class="form-group">
              <label>Email Address *</label>
              <input v-model="form.email" type="email" placeholder="john@example.com" required />
            </div>
          </div>

          <div class="form-group">
            <label>Subject</label>
            <input v-model="form.subject" type="text" placeholder="How can we help?" required />
          </div>

          <div class="form-group">
            <label>Your Message *</label>
            <textarea v-model="form.message" placeholder="Write your message here..." rows="6" required></textarea>
          </div>

          <button type="submit" class="submit-btn" :disabled="loading">
            {{ loading ? 'Sending...' : 'Send Message ➔' }}
          </button>
        </form>
      </div>

      <div class="map-showcase-container">
        <div class="map-canvas">
          <div class="map-zoom-controls">
            <button type="button">+</button>
            <button type="button">−</button>
          </div>
          
          <div class="map-popup-card">
            <div class="popup-icon-pin">📍</div>
            <div class="popup-body">
              <h4>Organic Market HQ</h4>
              <p>Bishlag, Tolarbag, Mirpur 01, Dhaka</p>
            </div>
            <a href="#" class="open-maps-link" @click.prevent>Open in Maps</a>
          </div>
        </div>
      </div>
    </div>

    <section class="newsletter-banner-strip">
      <div class="newsletter-inner">
        <div class="newsletter-text">
          <h2>Stay home & get your daily needs from our shop</h2>
          <p>Start Your Daily Shopping with Organic Market</p>
          <div class="subscribe-input-wrapper">
            <span class="envelope-inline-icon">✉️</span>
            <input type="email" placeholder="Your email address" />
            <button class="btn-subscribe-action">Subscribe</button>
          </div>
        </div>
        <div class="newsletter-media-box">
          <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80" alt="Basket filled with organic fresh vegetables" />
        </div>
      </div>
    </section>

  </div>
  <AppFooter />
</template>

<script setup lang="ts">
import { ref } from 'vue'
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'

const loading = ref(false)
const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const submitForm = async () => {
  loading.value = true
  setTimeout(() => {
    loading.value = false
    alert('Message sent successfully!')
    form.value = { name: '', email: '', subject: '', message: '' }
  }, 1000)
}
</script>

<style scoped>
/* ---------- Global Base Settings & Layout ---------- */
.contact-page {
  max-width: 1400px;
  margin: 0 auto;
  padding: 40px 24px;
  font-family: 'Quicksand', 'Inter', system-ui, sans-serif;
  color: #253d4e; /* Deep color matching standard organic design */
}

/* ---------- Page Header Section ---------- */
.page-header {
  margin-bottom: 48px;
}

.page-header h1 {
  font-size: 40px;
  font-weight: 700;
  color: #253d4e;
  margin: 0 0 12px 0;
}

.sub {
  color: #7e7e7e;
  font-size: 16px;
  line-height: 1.6;
  max-width: 600px;
  margin: 0;
}

/* ---------- 3-Column Header Cards Grid ---------- */
.contact-cards-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 48px;
}

.info-card {
  background: #ffffff;
  border: 1px solid #ececec;
  border-radius: 16px;
  padding: 32px;
  transition: all 0.2s ease;
}

.info-card:hover {
  box-shadow: 0 10px 20px rgba(0,0,0,0.03);
}

.info-icon {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}

/* Matching the explicit background tokens from image */
.bg-light-green  { background-color: #def7ec; }
.bg-light-yellow { background-color: #fef08a; }
.bg-light-pink   { background-color: #fce7f3; }

.emoji-icon {
  font-size: 20px;
}

.info-card h3 {
  font-size: 24px;
  font-weight: 700;
  color: #253d4e;
  margin: 0 0 8px 0;
}

.card-desc {
  font-size: 14px;
  color: #7e7e7e;
  margin: 0 0 16px 0;
}

.highlight-text {
  font-size: 15px;
  line-height: 1.6;
  color: #253d4e;
  font-weight: 600;
  margin: 0;
}

/* ---------- Interactive Bottom Grid Setup ---------- */
.contact-content {
  display: grid;
  grid-template-columns: 55% 45%;
  gap: 32px;
  margin-bottom: 64px;
  align-items: stretch;
}

/* Contact Message Form Module */
.contact-form-section {
  background: #ffffff;
}

.contact-form-section h2 {
  font-size: 32px;
  font-weight: 700;
  color: #253d4e;
  margin: 0 0 8px 0;
}

.form-instructions {
  font-size: 14px;
  color: #7e7e7e;
  margin-bottom: 32px;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.form-row-twin {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 20px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-weight: 600;
  color: #253d4e;
  font-size: 14px;
}

.form-group input,
.form-group textarea {
  padding: 14px 20px;
  border: 1px solid #ececec;
  border-radius: 10px;
  font-size: 15px;
  color: #253d4e;
  background-color: #ffffff;
  outline: none;
  transition: all 0.2s ease;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #bbf7d0;
  box-shadow: 0 4px 12px rgba(4, 108, 78, 0.03);
}

.submit-btn {
  align-self: flex-start;
  padding: 16px 40px;
  background: #253d4e;
  color: #ffffff;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.2s;
}

.submit-btn:hover {
  background: #046c4e;
}

/* Clean Map Wrapper Visuals Layout */
.map-showcase-container {
  border-radius: 20px;
  overflow: hidden;
  position: relative;
  min-height: 450px;
}

.map-canvas {
  width: 100%;
  height: 100%;
  background-color: #cad2d3;
  /* Premium isometric abstract topographic backdrop style mimicking map layout grid lines */
  background-image: 
    linear-gradient(45deg, #cbd5e1 25%, transparent 25%), 
    linear-gradient(-45deg, #cbd5e1 25%, transparent 25%), 
    linear-gradient(45deg, transparent 75%, #cbd5e1 75%), 
    linear-gradient(-45deg, transparent 75%, #cbd5e1 75%);
  background-size: 40px 40px;
  background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
  position: relative;
}

/* Floating Elements Inside Map Mockup Component */
.map-zoom-controls {
  position: absolute;
  top: 20px;
  right: 20px;
  display: flex;
  flex-direction: column;
  background: #ffffff;
  border-radius: 6px;
  box-shadow: 0 4px 10px rgba(0,0,0,0.1);
}

.map-zoom-controls button {
  width: 36px;
  height: 36px;
  border: none;
  background: transparent;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  color: #253d4e;
}

.map-zoom-controls button:first-child {
  border-bottom: 1px solid #ececec;
}

.map-popup-card {
  position: absolute;
  bottom: 20px;
  left: 20px;
  right: 20px;
  background: #ffffff;
  border-radius: 12px;
  padding: 16px 20px;
  box-shadow: 0 10px 25px rgba(0,0,0,0.08);
  display: flex;
  align-items: center;
  gap: 16px;
}

.popup-icon-pin {
  font-size: 24px;
}

.popup-body {
  flex: 1;
}

.popup-body h4 {
  margin: 0 0 4px 0;
  font-size: 16px;
  font-weight: 700;
  color: #253d4e;
}

.popup-body p {
  margin: 0;
  font-size: 13px;
  color: #7e7e7e;
}

.open-maps-link {
  font-size: 13px;
  font-weight: 700;
  color: #046c4e;
  text-decoration: none;
}

/* ---------- Global Bottom Organic Subscription Banner Bar ---------- */
.newsletter-banner-strip {
  background-color: #d8f1e5;
  border-radius: 24px;
  padding: 48px 60px;
  overflow: hidden;
  margin-top: 40px;
}

.newsletter-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 40px;
}

.newsletter-text {
  max-width: 550px;
}

.newsletter-text h2 {
  font-size: 36px;
  font-weight: 700;
  color: #253d4e;
  margin: 0 0 12px 0;
  line-height: 1.2;
}

.newsletter-text p {
  font-size: 16px;
  color: #7e7e7e;
  margin: 0 0 32px 0;
}

.subscribe-input-wrapper {
  display: flex;
  align-items: center;
  background: #ffffff;
  padding: 6px 6px 6px 20px;
  border-radius: 40px;
  max-width: 450px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}

.envelope-inline-icon {
  margin-right: 12px;
  color: #7e7e7e;
}

.subscribe-input-wrapper input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 15px;
}

.btn-subscribe-action {
  background: #046c4e;
  color: #ffffff;
  border: none;
  border-radius: 30px;
  padding: 12px 28px;
  font-weight: 600;
  cursor: pointer;
}

.newsletter-media-box {
  width: 240px;
  height: 200px;
  flex-shrink: 0;
}

.newsletter-media-box img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  border-radius: 12px;
}

/* ---------- Responsive Optimization Queries ---------- */
@media (max-width: 1024px) {
  .contact-cards-row { grid-template-columns: 1fr; }
  .contact-content { grid-template-columns: 1fr; }
  .newsletter-inner { flex-direction: column; text-align: center; }
  .newsletter-media-box { display: none; }
  .subscribe-input-wrapper { margin: 0 auto; }
}

@media (max-width: 640px) {
  .form-row-twin { grid-template-columns: 1fr; }
  .newsletter-banner-strip { padding: 32px 20px; }
  .newsletter-text h2 { font-size: 26px; }
}
</style>