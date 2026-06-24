<template>
  <div class="cart-page">
    <div class="page-header">
      <h1>Shopping Cart</h1>
      <p class="sub" v-if="props.cart.length">{{ props.cart.length }} items in your cart</p>
    </div>

    <div v-if="props.cart.length === 0" class="state-center empty-cart">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="56" height="56">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
      </svg>
      <p class="empty-msg">Your cart is empty</p>
      <button @click="$emit('changePage', 'products')" class="shop-link">Start Shopping</button>
    </div>

    <div v-else class="cart-layout">
      <div class="cart-items-col">
        <div v-for="item in props.cart" :key="item.id" class="cart-row">
          <div class="thumb">
            <span>{{ getInitials(item.name) }}</span>
          </div>
          <div class="meta">
            <h3>{{ item.name }}</h3>
            <p class="item-price">${{ item.price.toFixed(2) }}</p>
            <div class="qty-ctrl">
              <button @click="onQty(item.id, -1)" class="qty-btn">-</button>
              <span>{{ item.quantity }}</span>
              <button @click="onQty(item.id, 1)" class="qty-btn">+</button>
            </div>
          </div>
          <div class="right">
            <span class="subtotal">${{ (item.price * item.quantity).toFixed(2) }}</span>
            <button @click="$emit('removeFromCart', item.id)" class="remove-btn">&times;</button>
          </div>
        </div>
      </div>

      <div class="summary-box">
        <h2>Order Summary</h2>
        <div class="summary-line">
          <span>Subtotal</span>
          <span>${{ subtotal.toFixed(2) }}</span>
        </div>
        <div class="summary-line">
          <span>Tax (5%)</span>
          <span>${{ tax.toFixed(2) }}</span>
        </div>
        <div class="summary-line final">
          <span>Total</span>
          <span>${{ total.toFixed(2) }}</span>
        </div>
        <button @click="$emit('changePage', 'checkout')" class="primary-btn">Checkout</button>
        <button @click="$emit('changePage', 'products')" class="ghost-btn">Continue Shopping</button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ cart: any[] }>()
const emit = defineEmits<{
  updateCartItem: [id: number, qty: number]
  removeFromCart: [id: number]
  changePage: [page: string]
}>()

const subtotal = computed(() => {
  return props.cart.reduce((sum: number, item: any) => sum + item.price * item.quantity, 0)
})
const tax = computed(() => subtotal.value * 0.05)
const total = computed(() => subtotal.value + tax.value)

const onQty = (id: number, delta: number) => {
  const item = props.cart.find((i: any) => i.id === id)
  if (item) {
    const newQty = item.quantity + delta
    if (newQty <= 0) {
      emit('removeFromCart', id)
    } else {
      emit('updateCartItem', id, newQty)
    }
  }
}

const getInitials = (name: string) => name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)
</script>

<style scoped>
.cart-page {
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

.sub {
  color: #64748b;
  font-size: 0.875rem;
  margin: 0.25rem 0 0;
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
}

.empty-msg {
  margin-top: 0.75rem;
  color: #64748b;
  font-size: 0.9375rem;
}

.shop-link {
  margin-top: 1rem;
  padding: 0.625rem 1.5rem;
  background: #0f172a;
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-weight: 500;
  cursor: pointer;
  font-size: 0.875rem;
  transition: all 0.15s;
}

.shop-link:hover {
  background: #1e293b;
}

.cart-layout {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 2rem;
  align-items: start;
}

.cart-items-col {
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.cart-row {
  display: flex;
  gap: 1rem;
  padding: 1rem;
  background: white;
  border-radius: 0.75rem;
  border: 1px solid #f1f5f9;
  align-items: center;
}

.thumb {
  width: 64px;
  height: 64px;
  border-radius: 0.625rem;
  background: linear-gradient(135deg, #e0e7ff, #f3e8ff);
  flex-shrink: 0;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: #6366f1;
  font-size: 0.75rem;
}

.meta {
  flex: 1;
  min-width: 0;
}

.meta h3 {
  font-size: 0.9375rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 0.25rem;
}

.item-price {
  color: #64748b;
  font-size: 0.8125rem;
  margin: 0 0 0.5rem;
}

.qty-ctrl {
  display: inline-flex;
  align-items: center;
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  overflow: hidden;
}

.qty-btn {
  width: 28px;
  height: 28px;
  border: none;
  background: white;
  color: #334155;
  cursor: pointer;
  font-weight: 600;
  font-size: 0.9375rem;
  display: flex;
  align-items: center;
  justify-content: center;
}

.qty-btn:hover {
  background: #f1f5f9;
}

.qty-ctrl span {
  width: 32px;
  text-align: center;
  font-weight: 600;
  font-size: 0.8125rem;
}

.right {
  display: flex;
  flex-direction: column;
  align-items: flex-end;
  gap: 0.5rem;
}

.subtotal {
  font-weight: 700;
  color: #0f172a;
  font-size: 0.9375rem;
}

.remove-btn {
  background: transparent;
  border: none;
  color: #cbd5e1;
  font-size: 1.5rem;
  cursor: pointer;
  line-height: 1;
  padding: 0 0.25rem;
  transition: color 0.15s;
}

.remove-btn:hover {
  color: #ef4444;
}

.summary-box {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  border: 1px solid #f1f5f9;
  position: sticky;
  top: 90px;
}

.summary-box h2 {
  font-size: 1.125rem;
  font-weight: 600;
  color: #0f172a;
  margin: 0 0 1rem;
}

.summary-line {
  display: flex;
  justify-content: space-between;
  padding: 0.5rem 0;
  color: #475569;
  font-size: 0.9375rem;
}

.summary-line.final {
  border-top: 1px solid #e2e8f0;
  margin-top: 0.5rem;
  padding-top: 0.75rem;
  color: #0f172a;
  font-weight: 700;
  font-size: 1.125rem;
}

.primary-btn {
  display: block;
  width: 100%;
  padding: 0.875rem;
  background: #0f172a;
  color: white;
  border: none;
  border-radius: 0.75rem;
  font-weight: 600;
  font-size: 0.9375rem;
  cursor: pointer;
  margin-top: 1.25rem;
  transition: all 0.2s;
}

.primary-btn:hover {
  background: #1e293b;
  transform: scale(1.01);
}

.ghost-btn {
  display: block;
  width: 100%;
  padding: 0.75rem;
  background: transparent;
  color: #475569;
  border: none;
  font-weight: 500;
  font-size: 0.875rem;
  cursor: pointer;
  margin-top: 0.75rem;
  text-align: center;
}

.ghost-btn:hover {
  color: #0f172a;
}
</style>
