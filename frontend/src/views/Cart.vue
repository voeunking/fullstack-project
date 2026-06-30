<template>
  <div class="cart-page">
    <div class="page-header">
      <h1>Shopping Cart</h1>
      <p class="sub" v-if="cart.length">{{ cart.length }} items in your cart</p>
    </div>

    <div v-if="!cart.length" class="state-center">
      <p>Your cart is empty</p>
      <router-link to="/dashboard/products" class="continue-btn">Continue Shopping</router-link>
    </div>

    <div v-else class="cart-layout">
      <div class="cart-items">
        <CartItem
          v-for="item in cart"
          :key="item.id"
          :item="item"
          @update="updateItem"
          @remove="removeItem"
        />
      </div>

      <div class="cart-summary">
        <h2>Order Summary</h2>
        <p class="summary-note">Your items are saved locally until you checkout.</p>
        <div class="summary-row">
          <span>Subtotal</span>
          <span>${{ subtotal.toFixed(2) }}</span>
        </div>
        <div class="summary-row">
          <span>Tax (5%)</span>
          <span>${{ tax.toFixed(2) }}</span>
        </div>
        <div class="summary-row total">
          <span>Total</span>
          <span>${{ total.toFixed(2) }}</span>
        </div>
        <router-link to="/checkout" class="checkout-btn">Proceed to Checkout</router-link>
        <div class="secure-row">Secure checkout | Flexible payment</div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import CartItem from '../components/CartItem.vue'

const props = defineProps<{ cart: any[] }>()
const emit = defineEmits<{
  'update-cart': [id: number, qty: number]
  'remove-from-cart': [id: number]
}>()

const subtotal = computed(() => props.cart.reduce((s: number, i: any) => s + i.price * i.quantity, 0))
const tax = computed(() => subtotal.value * 0.05)
const total = computed(() => subtotal.value + tax.value)

const updateItem = (id: number, qty: number) => {
  emit('update-cart', id, qty)
}

const removeItem = (id: number) => {
  emit('remove-from-cart', id)
}
</script>

<style scoped>
.cart-page {
  max-width: 1000px;
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
  font-weight: 800;
}

.cart-layout {
  display: grid;
  grid-template-columns: 1fr 300px;
  gap: 2rem;
}

.cart-items {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.cart-summary {
  background: white;
  border-radius: 0.5rem;
  padding: 1.5rem;
  border: 1px solid #e2e8f0;
  height: fit-content;
  box-shadow: 0 18px 35px rgba(15, 23, 42, 0.08);
  position: sticky;
  top: 96px;
}

.cart-summary h2 {
  font-size: 1.125rem;
  font-weight: 900;
  margin: 0;
  color: #0f172a;
}

.summary-note {
  margin: 0.25rem 0 1rem;
  color: #64748b;
  font-size: 0.85rem;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  color: #475569;
}

.summary-row.total {
  border-top: 1px solid #e2e8f0;
  margin-top: 0.5rem;
  padding-top: 1rem;
  font-weight: 600;
  color: #0f172a;
  font-size: 1.125rem;
}

.checkout-btn {
  display: block;
  width: 100%;
  text-align: center;
  margin-top: 1.5rem;
  padding: 0.75rem;
  background: linear-gradient(135deg, #0f172a, #134e4a);
  color: white;
  text-decoration: none;
  border-radius: 0.5rem;
  font-weight: 900;
  box-shadow: 0 14px 24px rgba(15, 23, 42, 0.18);
}

.secure-row {
  margin-top: 0.9rem;
  color: #0f766e;
  font-size: 0.82rem;
  font-weight: 800;
  text-align: center;
}

@media (max-width: 840px) {
  .cart-layout {
    grid-template-columns: 1fr;
  }

  .cart-summary {
    position: static;
  }
}
</style>
