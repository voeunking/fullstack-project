<template>
  <div class="checkout-page">
    <div class="page-header">
      <h1>Checkout</h1>
      <p class="sub">Complete your order securely</p>
    </div>

    <div v-if="props.cart.length === 0" class="state-center">
      <p style="color: #64748b; margin-bottom: 1rem;">Your cart is empty</p>
      <button @click="$emit('changePage', 'products')" class="shop-link">Browse Products</button>
    </div>
    <div v-else class="checkout-grid">
      <div class="form-col">
        <div class="section-card">
          <h2>Contact Information</h2>
          <div class="form-grid">
            <div class="field">
              <label>Full Name</label>
              <input v-model="form.name" type="text" required placeholder="John Doe" />
            </div>
            <div class="field">
              <label>Phone</label>
              <input v-model="form.phone" type="tel" required placeholder="+1 234 567 890" />
            </div>
            <div class="field full">
              <label>Address</label>
              <textarea v-model="form.address" rows="2" required placeholder="123 Main St, City, Country"></textarea>
            </div>
            <div class="field">
              <label>City</label>
              <input v-model="form.city" type="text" required placeholder="New York" />
            </div>
            <div class="field">
              <label>Zip Code</label>
              <input v-model="form.zip" type="text" required placeholder="10001" />
            </div>
          </div>
        </div>

        <div class="section-card">
          <h2>Payment Method</h2>
          <div class="payment-options">
            <button type="button" @click="paymentMethod = 'qr'" :class="['pay-opt', { active: paymentMethod === 'qr' }]">
              <div class="pay-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="22" height="22">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                </svg>
              </div>
              <div class="pay-info">
                <strong>QR Code</strong>
                <small>Scan to pay instantly</small>
              </div>
              <div v-if="paymentMethod === 'qr'" class="check-dot">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              </div>
            </button>

            <button type="button" @click="paymentMethod = 'cod'" :class="['pay-opt', { active: paymentMethod === 'cod' }]">
              <div class="pay-icon">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="22" height="22">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V5a2 2 0 00-2-2h-2a2 2 0 00-2 2v4h6z"/>
                </svg>
              </div>
              <div class="pay-info">
                <strong>Cash on Delivery</strong>
                <small>Pay when you receive</small>
              </div>
              <div v-if="paymentMethod === 'cod'" class="check-dot">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
              </div>
            </button>
          </div>

          <div v-if="paymentMethod === 'qr'" class="qr-block">
            <div class="qr-box">
              <h3>Scan QR to Pay</h3>
              <p class="qr-amount">Total: ${{ total.toFixed(2) }}</p>
              <svg viewBox="0 0 200 200" class="qr-svg">
                <rect x="10" y="10" width="50" height="50" fill="#0f172a"/>
                <rect x="140" y="10" width="50" height="50" fill="#0f172a"/>
                <rect x="10" y="140" width="50" height="50" fill="#0f172a"/>
                <rect x="80" y="80" width="40" height="40" fill="#0f172a"/>
                <rect x="25" y="25" width="20" height="20" fill="white"/>
                <rect x="155" y="25" width="20" height="20" fill="white"/>
                <rect x="25" y="155" width="20" height="20" fill="white"/>
                <rect x="90" y="90" width="20" height="20" fill="white"/>
              </svg>
              <small>Use your banking app to scan and complete the payment</small>
            </div>
          </div>
        </div>
      </div>

      <div class="order-summary">
        <h2>Your Order</h2>
        <div class="order-items-preview">
          <div v-for="item in cart" :key="item.id" class="preview-row">
            <span>{{ item.name }} &times;{{ item.quantity }}</span>
            <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
          </div>
        </div>
        <div class="preview-total">
          <span>Total</span>
          <strong>${{ total.toFixed(2) }}</strong>
        </div>
        <button @click="submitOrder" :disabled="submitting" class="place-btn">
          {{ submitting ? 'Processing...' : 'Place Order' }}
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps<{ cart: any[] }>()
const emit = defineEmits<{
  placeOrder: [method: string]
  changePage: [page: string]
}>()

const form = ref({ name: '', phone: '', address: '', city: '', zip: '' })
const paymentMethod = ref<'qr' | 'cod'>('qr')
const submitting = ref(false)

const subtotal = computed(() => {
  return props.cart.reduce((sum: number, item: any) => sum + item.price * item.quantity, 0)
})
const tax = computed(() => subtotal.value * 0.05)
const total = computed(() => subtotal.value + tax.value)

const submitOrder = async () => {
  submitting.value = true
  try {
    await emit('placeOrder', paymentMethod.value)
  } finally {
    submitting.value = false
  }
}
</script>

<style scoped>
.checkout-page {
  max-width: 1100px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.state-center {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem;
  text-align: center;
  background: white;
  border-radius: 1rem;
  border: 1px solid #f1f5f9;
  color: #64748b;
}

.shop-link {
  padding: 0.625rem 1.5rem;
  background: #0f172a;
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  font-size: 0.875rem;
}

.checkout-grid {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 2rem;
  align-items: start;
}

.form-col {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.section-card {
  background: white;
  padding: 1.5rem;
  border-radius: 1rem;
  border: 1px solid #f1f5f9;
}

.section-card h2 {
  font-size: 1.0625rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 1.25rem;
}

.form-grid {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.field {
  display: flex;
  flex-direction: column;
  gap: 0.375rem;
}

.field.full {
  grid-column: 1 / -1;
}

.field label {
  font-size: 0.8125rem;
  font-weight: 500;
  color: #475569;
}

.field input,
.field textarea {
  padding: 0.625rem 0.875rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.625rem;
  font-size: 0.875rem;
  background: #fafbfc;
  transition: all 0.2s;
}

.field input:focus,
.field textarea:focus {
  outline: none;
  border-color: #3b82f6;
  background: white;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.08);
}

.field textarea {
  resize: vertical;
}

.payment-options {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
  margin-bottom: 1.25rem;
}

.pay-opt {
  display: flex;
  align-items: center;
  gap: 1rem;
  padding: 1rem;
  border: 2px solid #e2e8f0;
  border-radius: 0.75rem;
  background: white;
  cursor: pointer;
  transition: all 0.2s;
  position: relative;
  text-align: left;
  width: 100%;
}

.pay-opt:hover {
  border-color: #cbd5e1;
  background: #fafbfc;
}

.pay-opt.active {
  border-color: #0f172a;
  background: #f8fafc;
}

.pay-icon {
  width: 42px;
  height: 42px;
  border-radius: 0.625rem;
  background: #f1f5f9;
  display: flex;
  align-items: center;
  justify-content: center;
  color: #475569;
}

.pay-info {
  flex: 1;
}

.pay-info strong {
  display: block;
  font-size: 0.9375rem;
  color: #0f172a;
}

.pay-info small {
  font-size: 0.8125rem;
  color: #64748b;
}

.check-dot {
  width: 20px;
  height: 20px;
  color: #0f172a;
}

.qr-block {
  margin-top: 0.75rem;
}

.qr-box {
  text-align: center;
  padding: 1.5rem;
  background: #fafbfc;
  border-radius: 0.75rem;
  border: 2px dashed #e2e8f0;
}

.qr-box h3 {
  font-size: 1rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 0.25rem;
}

.qr-amount {
  color: #3b82f6;
  font-weight: 700;
  font-size: 1.125rem;
  margin: 0 0 1rem;
}

.qr-box .qr-svg {
  width: 160px;
  height: 160px;
  background: white;
  border-radius: 0.75rem;
  padding: 0.75rem;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.04);
}

.qr-box small {
  display: block;
  margin-top: 1rem;
  font-size: 0.8125rem;
  color: #64748b;
}

.order-summary {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid #f1f5f9;
  position: sticky;
  top: 90px;
}

.order-summary h2 {
  font-size: 1.0625rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 1.25rem;
}

.order-items-preview {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  max-height: 300px;
  overflow-y: auto;
  padding-right: 0.5rem;
  margin-bottom: 1rem;
}

.preview-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.875rem;
  color: #475569;
  gap: 1rem;
}

.preview-total {
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-top: 1px solid #e2e8f0;
  padding-top: 0.75rem;
  margin-bottom: 1rem;
  font-size: 0.9375rem;
  color: #334155;
}

.preview-total strong {
  font-size: 1.25rem;
  color: #0f172a;
}

.place-btn {
  width: 100%;
  padding: 0.875rem;
  background: #0f172a;
  color: white;
  border: none;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.9375rem;
  cursor: pointer;
  transition: all 0.2s;
}

.place-btn:hover:not(:disabled) {
  background: #1e293b;
  transform: scale(1.01);
}

.place-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>
