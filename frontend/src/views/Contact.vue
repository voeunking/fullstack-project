<template>
  <AppNavbar />
  <div class="contact-page">

    <div class="page-header">
      <span class="eyebrow">🌿 We'd love to hear from you</span>
      <h1>Get in touch</h1>
      <p class="sub">We are always ready to help you with your organic grocery needs. Reach out to us through any of the channels below.</p>
    </div>

    <div class="contact-cards-row">
      <div class="info-card">
        <div class="info-icon bg-light-green">
          <span class="emoji-icon">📍</span>
        </div>
        <h3>Office address</h3>
        <p class="card-desc">Visit our main hub for a fresh experience.</p>
        <p class="contact-detail highlight-text">Bishlag, Tolarbag, Mirpur 01,<br>Dhaka-1216, Bangladesh</p>
      </div>

      <div class="info-card">
        <div class="info-icon bg-light-yellow">
          <span class="emoji-icon">📞</span>
        </div>
        <h3>Phone number</h3>
        <p class="card-desc">Call us anytime for urgent inquiries.</p>
        <p class="contact-detail highlight-text">+880 1521 199302<br>+880 1789 456123</p>
      </div>

      <div class="info-card">
        <div class="info-icon bg-light-pink">
          <span class="emoji-icon">✉️</span>
        </div>
        <h3>Support email</h3>
        <p class="card-desc">Send us an email and we'll reply fast.</p>
        <p class="contact-detail highlight-text">support@organicmarket.com<br>contact@organicmarket.com</p>
      </div>
    </div>

    <div class="contact-content">
      <div class="contact-form-section">
        <span class="section-eyebrow">Send a message</span>
        <h2>Drop us a line</h2>
        <p class="form-instructions">Your email address will not be published. Required fields are marked *</p>

        <form @submit.prevent="submitForm" class="contact-form">

          <div class="form-row-twin">
            <div class="form-group">
              <label>Your name *</label>
              <input v-model="form.name" type="text" placeholder="John Doe" required />
            </div>

            <div class="form-group">
              <label>Email address *</label>
              <input v-model="form.email" type="email" placeholder="john@example.com" required />
            </div>
          </div>

          <div class="form-group">
            <label>Subject</label>
            <input v-model="form.subject" type="text" placeholder="How can we help?" required />
          </div>

          <div class="form-group">
            <label>Your message *</label>
            <textarea v-model="form.message" placeholder="Write your message here..." rows="6" required></textarea>
          </div>

          <button type="submit" class="submit-btn" :disabled="loading">
            {{ loading ? 'Sending…' : 'Send message →' }}
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
            <a href="#" class="open-maps-link" @click.prevent>Open in Maps →</a>
          </div>
        </div>
      </div>
    </div>

    <section class="newsletter-banner-strip">
      <div class="newsletter-inner">
        <div class="newsletter-text">
          <span class="eyebrow eyebrow--on-light">Stay stocked</span>
          <h2>Stay home & get your daily needs from our shop</h2>
          <p>Start your daily shopping with Organic Market.</p>
          <div class="subscribe-input-wrapper">
            <input type="email" placeholder="Your email address" />
            <button class="btn-subscribe-action">Subscribe</button>
          </div>
        </div>
        <div class="newsletter-media-box">
          <div class="blob-frame">
            <img src="https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=400&q=80" alt="Basket filled with organic fresh vegetables" />
          </div>
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

const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const loading = ref(false)
const form = ref({
  name: '',
  email: '',
  subject: '',
  message: ''
})

const submitForm = async () => {
  loading.value = true
  try {
    const res = await fetch(`${backendUrl}/api/contact/messages`, {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },

      body: JSON.stringify({
        name: form.value.name,
        email: form.value.email,
        subject: form.value.subject,
        message: form.value.message,
      }),
    })

    if (!res.ok) {
      const text = await res.text()
      console.error('Contact submit failed:', res.status, text)
      throw new Error(text || `Failed to send message (HTTP ${res.status})`)
    }


    alert('Message sent successfully!')
    form.value = { name: '', email: '', subject: '', message: '' }
  } catch (e: any) {
    alert(e?.message || 'Failed to send message')
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
/* ============ DESIGN TOKENS (matches Home / Products) ============ */
.contact-page {
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

  max-width: 1320px;
  margin: 0 auto;
  padding: 40px 24px 80px;
  font-family: var(--font-body);
  color: var(--ink);
  background: var(--bg-page);
}

.contact-page * { box-sizing: border-box; }

.contact-page h1,
.contact-page h2,
.contact-page h3 {
  font-family: var(--font-display);
  font-weight: 600;
  letter-spacing: -0.01em;
  color: var(--ink);
  margin: 0;
}

.eyebrow,
.section-eyebrow {
  display: inline-block;
  font-size: 13px;
  font-weight: 700;
  color: var(--forest);
  background: var(--sage-light);
  padding: 6px 14px;
  border-radius: 30px;
  margin-bottom: 16px;
}
.section-eyebrow {
  padding: 0;
  background: none;
  text-transform: uppercase;
  letter-spacing: 0.06em;
  font-size: 12px;
  color: var(--clay);
}
.eyebrow--on-light {
  background: rgba(255, 255, 255, 0.6);
}

/* ---------- Page Header ---------- */
.page-header {
  margin-bottom: 48px;
  max-width: 640px;
}

.page-header h1 {
  font-size: 42px;
  margin: 0 0 14px;
}

.sub {
  color: var(--ink-muted);
  font-size: 16px;
  line-height: 1.6;
  margin: 0;
}

/* ---------- Info Cards ---------- */
.contact-cards-row {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 20px;
  margin-bottom: 56px;
}

.info-card {
  background: var(--card-bg);
  border: 1px solid var(--border);
  border-radius: 18px;
  padding: 30px;
  transition: box-shadow 0.2s, transform 0.2s, border-color 0.2s;
}

.info-card:hover {
  box-shadow: 0 14px 28px -10px rgba(27, 67, 50, 0.1);
  transform: translateY(-2px);
  border-color: var(--sage);
}

.info-icon {
  width: 48px;
  height: 48px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 20px;
}

.bg-light-green  { background-color: var(--sage-light); }
.bg-light-yellow { background-color: #fbf0c6; }
.bg-light-pink   { background-color: var(--clay-light); }

.emoji-icon { font-size: 20px; }

.info-card h3 {
  font-size: 20px;
  margin: 0 0 8px;
}

.card-desc {
  font-size: 14px;
  color: var(--ink-muted);
  margin: 0 0 16px;
}

.highlight-text {
  font-size: 14px;
  line-height: 1.6;
  color: var(--ink);
  font-weight: 600;
  margin: 0;
}

/* ---------- Form + Map Grid ---------- */
.contact-content {
  display: grid;
  grid-template-columns: 55% 45%;
  gap: 28px;
  margin-bottom: 64px;
  align-items: stretch;
}

.contact-form-section {
  background: var(--card-bg);
  border: 1px solid var(--border);
  border-radius: 20px;
  padding: 36px;
}

.contact-form-section h2 {
  font-size: 28px;
  margin: 0 0 8px;
}

.form-instructions {
  font-size: 13px;
  color: var(--ink-muted);
  margin-bottom: 28px;
}

.contact-form {
  display: flex;
  flex-direction: column;
  gap: 22px;
}

.form-row-twin {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-weight: 700;
  color: var(--ink);
  font-size: 13px;
}

.form-group input,
.form-group textarea {
  padding: 13px 18px;
  border: 1px solid var(--border);
  border-radius: 10px;
  font-size: 15px;
  font-family: inherit;
  color: var(--ink);
  background-color: var(--bg-page);
  outline: none;
  transition: all 0.2s ease;
  resize: vertical;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: var(--forest);
  background: #fff;
  box-shadow: 0 0 0 3px rgba(27, 67, 50, 0.08);
}

.submit-btn {
  align-self: flex-start;
  padding: 15px 36px;
  background: var(--forest);
  color: #ffffff;
  border: none;
  border-radius: 10px;
  font-weight: 700;
  font-size: 15px;
  cursor: pointer;
  font-family: var(--font-body);
  transition: background-color 0.2s, transform 0.15s;
}

.submit-btn:hover { background: var(--forest-hover); transform: translateY(-1px); }
.submit-btn:disabled { opacity: 0.65; cursor: not-allowed; transform: none; }

/* ---------- Map ---------- */
.map-showcase-container {
  border-radius: 20px;
  overflow: hidden;
  position: relative;
  min-height: 420px;
  border: 1px solid var(--border);
}

.map-canvas {
  width: 100%;
  height: 100%;
  background-color: var(--bg-hero);
  background-image:
    linear-gradient(45deg, #e3ddca 25%, transparent 25%),
    linear-gradient(-45deg, #e3ddca 25%, transparent 25%),
    linear-gradient(45deg, transparent 75%, #e3ddca 75%),
    linear-gradient(-45deg, transparent 75%, #e3ddca 75%);
  background-size: 40px 40px;
  background-position: 0 0, 0 20px, 20px -20px, -20px 0px;
  position: relative;
}

.map-zoom-controls {
  position: absolute;
  top: 18px;
  right: 18px;
  display: flex;
  flex-direction: column;
  background: #ffffff;
  border-radius: 10px;
  overflow: hidden;
  box-shadow: 0 6px 16px rgba(27, 67, 50, 0.12);
}

.map-zoom-controls button {
  width: 36px;
  height: 36px;
  border: none;
  background: transparent;
  font-size: 18px;
  font-weight: bold;
  cursor: pointer;
  color: var(--ink);
}

.map-zoom-controls button:hover { background: var(--sage-light); color: var(--forest); }

.map-zoom-controls button:first-child {
  border-bottom: 1px solid var(--border);
}

.map-popup-card {
  position: absolute;
  bottom: 18px;
  left: 18px;
  right: 18px;
  background: #ffffff;
  border-radius: 14px;
  padding: 16px 20px;
  box-shadow: 0 14px 28px -8px rgba(27, 67, 50, 0.18);
  display: flex;
  align-items: center;
  gap: 14px;
}

.popup-icon-pin { font-size: 22px; }

.popup-body { flex: 1; }

.popup-body h4 {
  margin: 0 0 4px;
  font-size: 15px;
}

.popup-body p {
  margin: 0;
  font-size: 13px;
  color: var(--ink-muted);
}

.open-maps-link {
  font-size: 13px;
  font-weight: 700;
  color: var(--forest);
  text-decoration: none;
  white-space: nowrap;
}
.open-maps-link:hover { color: var(--clay); }

/* ---------- Newsletter Strip ---------- */
.newsletter-banner-strip {
  background: linear-gradient(160deg, var(--forest) 0%, #0f291d 100%);
  border-radius: 24px;
  padding: 52px 60px;
  overflow: hidden;
  margin-top: 16px;
}

.newsletter-inner {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 40px;
}

.newsletter-text { max-width: 520px; }

.newsletter-text h2 {
  font-size: 32px;
  color: #fff;
  margin: 0 0 12px;
  line-height: 1.2;
}

.newsletter-text p {
  font-size: 15px;
  color: rgba(255, 255, 255, 0.75);
  margin: 0 0 28px;
}

.subscribe-input-wrapper {
  display: flex;
  align-items: center;
  background: #ffffff;
  padding: 6px 6px 6px 20px;
  border-radius: 40px;
  max-width: 420px;
}

.subscribe-input-wrapper input {
  flex: 1;
  border: none;
  outline: none;
  font-size: 14px;
  font-family: inherit;
}

.btn-subscribe-action {
  background: var(--clay);
  color: #ffffff;
  border: none;
  border-radius: 30px;
  padding: 12px 26px;
  font-weight: 700;
  cursor: pointer;
  font-family: var(--font-body);
  transition: background 0.2s;
}
.btn-subscribe-action:hover { background: #c2603f; }

.newsletter-media-box {
  width: 220px;
  height: 200px;
  flex-shrink: 0;
}

.blob-frame {
  width: 100%;
  height: 100%;
  clip-path: path('M3,90 C3,40 35,5 95,3 C155,1 192,38 192,92 C192,146 158,185 96,187 C34,189 3,140 3,90 Z');
  overflow: hidden;
}

.blob-frame img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

/* ---------- Responsive ---------- */
@media (max-width: 1024px) {
  .contact-cards-row { grid-template-columns: 1fr; }
  .contact-content { grid-template-columns: 1fr; }
  .newsletter-inner { flex-direction: column; text-align: center; }
  .newsletter-media-box { display: none; }
  .subscribe-input-wrapper { margin: 0 auto; }
}

@media (max-width: 640px) {
  .form-row-twin { grid-template-columns: 1fr; }
  .contact-form-section { padding: 24px; }
  .newsletter-banner-strip { padding: 32px 24px; }
  .newsletter-text h2 { font-size: 24px; }
  .page-header h1 { font-size: 32px; }
}
</style>