<template>
   <AppNavbar />
  <div class="vendor-page">
   

    <div class="page-header">
      <h1>Become a Vendor</h1>
      <p class="sub">Join our marketplace and start selling your products</p>
    </div>

    <div class="vendor-content">
      <div class="info-section">
        <h2>Why Sell With Us?</h2>
        <div class="benefits-list">
          <div class="benefit-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Low commission rates</span>
          </div>
          <div class="benefit-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Fast payment processing</span>
          </div>
          <div class="benefit-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Marketing support</span>
          </div>
          <div class="benefit-item">
            <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="24" height="24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            <span>Analytics dashboard</span>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2>Apply Now</h2>
        <form @submit.prevent="submitApplication" class="vendor-form">
          <div class="form-group">
            <label>Business Name</label>
            <input v-model="form.businessName" type="text" placeholder="Your business name" required />
          </div>

          <div class="form-group">
            <label>Email</label>
            <input v-model="form.email" type="email" placeholder="your@email.com" required />
          </div>

          <div class="form-group">
            <label>Phone</label>
            <input v-model="form.phone" type="tel" placeholder="+1 (555) 123-4567" />
          </div>

          <div class="form-group">
            <label>Business Description</label>
            <textarea v-model="form.description" placeholder="Tell us about your business" rows="4"></textarea>
          </div>

          <button type="submit" class="submit-btn" :disabled="loading">
            {{ loading ? 'Submitting...' : 'Submit Application' }}
          </button>
        </form>
      </div>
    </div>

    
  </div>
  <AppFooter />
</template>

<script setup lang="ts">
import { ref } from 'vue'
import AppNavbar from '../components/AppNavbar.vue'
import AppFooter from '../components/AppFooter.vue'

const loading = ref(false)
const form = ref({
  businessName: '',
  email: '',
  phone: '',
  description: ''
})

const submitApplication = async () => {
  loading.value = true
  // TODO: Submit to backend
  setTimeout(() => {
    loading.value = false
    alert('Application submitted successfully!')
    form.value = { businessName: '', email: '', phone: '', description: '' }
  }, 1000)
}
</script>

<style scoped>
.vendor-page {
  max-width: 1200px;
  margin: 0 auto;
  padding: 2rem;
}

.page-header {
  text-align: center;
  margin-bottom: 3rem;
}

.page-header h1 {
  font-size: 2.5rem;
  font-weight: 900;
  color: var(--text-primary);
  margin-bottom: 0.5rem;
}

.sub {
  color: var(--text-secondary);
  font-size: 1.1rem;
}

.vendor-content {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 3rem;
}

@media (max-width: 768px) {
  .vendor-content {
    grid-template-columns: 1fr;
  }
}

.info-section {
  background: white;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: var(--shadow-md);
  border: 1px solid var(--border-light);
}

.info-section h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 1.5rem;
}

.benefits-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.benefit-item {
  display: flex;
  align-items: center;
  gap: 0.75rem;
  color: var(--text-secondary);
}

.benefit-item svg {
  color: var(--primary-600);
  flex-shrink: 0;
}

.form-section {
  background: white;
  border-radius: 1rem;
  padding: 2rem;
  box-shadow: var(--shadow-md);
  border: 1px solid var(--border-light);
}

.form-section h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: var(--text-primary);
  margin-bottom: 1.5rem;
}

.vendor-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}

.form-group label {
  font-weight: 700;
  color: var(--text-primary);
  font-size: 0.9rem;
}

.form-group input,
.form-group textarea {
  padding: 0.75rem;
  border: 1px solid var(--border-light);
  border-radius: 0.5rem;
  font-size: 1rem;
  transition: all var(--transition-fast);
}

.form-group input:focus,
.form-group textarea:focus {
  outline: none;
  border-color: var(--primary-500);
  box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.15);
}

.submit-btn {
  padding: 1rem;
  background: var(--primary-600);
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-weight: 700;
  font-size: 1rem;
  cursor: pointer;
  transition: all var(--transition-fast);
}

.submit-btn:hover:not(:disabled) {
  background: var(--primary-700);
}

.submit-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
