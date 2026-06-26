<template>
  <div class="checkout-page">
    <div class="page-header">
      <h1>Checkout</h1>
      <p class="sub">Complete your order with confidence.</p>
    </div>

    <div v-if="!cart.length" class="state-center">
      <p>Your cart is empty</p>
      <router-link to="/dashboard/products" class="continue-btn">Shop Now</router-link>
    </div>

    <form v-else @submit.prevent="submitOrder" class="checkout-form">
      <div class="form-section">
        <h2>Shipping Information</h2>
        <div class="form-fields">
          <div class="field">
            <label>Full Name</label>
            <input v-model="form.name" required placeholder="John Doe" />
          </div>
          <div class="field">
            <label>Email</label>
            <input v-model="form.email" type="email" required placeholder="john@example.com" />
          </div>
          <div class="field">
            <label>Phone</label>
            <input v-model="form.phone" required placeholder="+1 234 567 890" />
          </div>
          <div class="field full">
            <label>Address</label>
            <textarea v-model="form.address" required placeholder="123 Main St, City"></textarea>
          </div>
        </div>
      </div>

      <div class="form-section">
        <h2>Payment Method</h2>
        <div class="payment-options">
          <label class="pay-option" :class="{ active: form.payment === 'cod' }">
            <input type="radio" value="cod" v-model="form.payment" />
            <span>
              <strong>Cash on Delivery</strong>
              <small>Pay when your order arrives.</small>
            </span>
          </label>
          <label class="pay-option" :class="{ active: form.payment === 'qr' }">
            <input type="radio" value="qr" v-model="form.payment" />
            <span>
              <strong>QR Code Payment</strong>
              <small>Fast digital payment confirmation.</small>
            </span>
          </label>
        </div>
      </div>

      <div class="order-summary">
        <h3>Order Summary</h3>
        <div v-for="item in cart" :key="item.id" class="summary-item">
          <span>{{ item.name }} x {{ item.quantity }}</span>
          <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
        </div>
        <div class="summary-total">
          <strong>Total: ${{ total.toFixed(2) }}</strong>
        </div>
        <button type="submit" :disabled="loading" class="place-order-btn">
          {{ loading ? 'Placing Order...' : 'Place Order' }}
        </button>
      </div>
    </form>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

const props = defineProps<{ cart: any[] }>()
const emit = defineEmits<{
  'place-order': [method: string]
}>()

const loading = ref(false)
const form = ref({
  name: '',
  email: '',
  phone: '',
  address: '',
  payment: 'cod'
})

const total = computed(() => props.cart.reduce((s: number, i: any) => s + i.price * i.quantity, 0))

const submitOrder = async () => {
  loading.value = true
  try {
    emit('place-order', form.value.payment)
  } finally {
    loading.value = false
  }
}
</script>

<style scoped>
.checkout-page {
  max-width: 860px;
  margin: 0 auto;
}

.page-header {
  margin-bottom: 2rem;
}

.page-header h1 {
  font-size: 1.75rem;
  font-weight: 900;
  margin: 0;
  color: #0f172a;
}

.sub {
  color: #64748b;
  margin: 0.25rem 0 0;
}

.state-center {
  background: white;
  padding: 3rem;
  border-radius: 0.5rem;
  text-align: center;
  border: 1px solid #e2e8f0;
}

.continue-btn {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.5rem 1.5rem;
  background: #0f766e;
  color: white;
  text-decoration: none;
  border-radius: 0.5rem;
  font-weight: 900;
}

.checkout-form {
  display: flex;
  flex-direction: column;
  gap: 1.5rem;
}

.form-section {
  background: white;
  border-radius: 0.5rem;
  padding: 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
}

.form-section h2 {
  margin: 0 0 1rem;
  font-size: 1.125rem;
  font-weight: 900;
  color: #0f172a;
}

.form-fields {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 1rem;
}

.field.full {
  grid-column: 1 / -1;
}

.field label {
  display: block;
  margin-bottom: 0.25rem;
  font-weight: 800;
  font-size: 0.875rem;
  color: #334155;
}

.field input,
.field textarea {
  width: 100%;
  padding: 0.625rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  background: #f8fafc;
}

.field input:focus,
.field textarea:focus {
  outline: none;
  border-color: #0f766e;
  background: #ffffff;
  box-shadow: 0 0 0 3px rgba(20, 184, 166, 0.12);
}

.payment-options {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.pay-option {
  display: flex;
  align-items: center;
  gap: 0.7rem;
  padding: 1rem;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  cursor: pointer;
  background: #ffffff;
}

.pay-option span {
  display: flex;
  flex-direction: column;
  gap: 0.1rem;
}

.pay-option strong {
  font-weight: 900;
  color: #0f172a;
}

.pay-option small {
  color: #64748b;
}

.pay-option.active {
  border-color: #0f766e;
  background: #ecfdf5;
  box-shadow: 0 10px 22px rgba(15, 118, 110, 0.1);
}

.order-summary {
  background: white;
  border-radius: 0.5rem;
  padding: 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 18px 35px rgba(15, 23, 42, 0.08);
}

.order-summary h3 {
  margin: 0 0 1rem;
  font-weight: 900;
  color: #0f172a;
}

.summary-item {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  font-size: 0.875rem;
}

.summary-total {
  margin-top: 1rem;
  padding-top: 1rem;
  border-top: 1px solid #e2e8f0;
  text-align: right;
  font-size: 1.125rem;
}

.place-order-btn {
  width: 100%;
  margin-top: 1.5rem;
  padding: 0.875rem;
  background: linear-gradient(135deg, #0f172a, #134e4a);
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-weight: 900;
  cursor: pointer;
  box-shadow: 0 14px 24px rgba(15, 23, 42, 0.18);
}

.place-order-btn:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

@media (max-width: 680px) {
  .form-fields {
    grid-template-columns: 1fr;
  }
}
</style>
