<template>
  <div class="orders-page">
    <div class="page-header">
      <h1>My Orders</h1>
      <p class="sub">Track your order status from checkout to delivery.</p>
    </div>

    <div v-else-if="!orders.length" class="state-center">
      <p>No orders yet</p>
      <router-link to="/dashboard/products" class="shop-btn">Start Shopping</router-link>
    </div>

    <div v-else class="orders-list">
      <div v-for="order in orders" :key="order.id" class="order-card">
        <div class="order-header">
          <div>
            <span class="order-id">Order #{{ order.id }}</span>
            <small>{{ formatDate(order.created_at) }}</small>
          </div>
          <span :class="['status-badge', order.status]">{{ order.status }}</span>
        </div>

        <div class="order-progress" v-if="order.status !== 'cancelled'">
          <div class="progress-step" :class="{ done: stepDone(order, 1), active: stepActive(order, 1) }">
            <span class="step-dot"></span>
            <span class="step-label">Order Placed</span>
          </div>
          <div class="progress-line" :class="{ done: stepDone(order, 2) }"></div>
          <div class="progress-step" :class="{ done: stepDone(order, 2), active: stepActive(order, 2) }">
            <span class="step-dot"></span>
            <span class="step-label">Admin Confirmed</span>
          </div>
          <div class="progress-line" :class="{ done: stepDone(order, 3) }"></div>
          <div class="progress-step" :class="{ done: stepDone(order, 3), active: stepActive(order, 3) }">
            <span class="step-dot"></span>
            <span class="step-label">Delivered</span>
          </div>
        </div>

        <div v-if="order.status === 'cancelled'" class="cancel-banner">
          This order has been cancelled.
        </div>

        <div class="order-items">
          <div v-for="item in order.items" :key="item.product_id" class="order-item">
            <span>{{ item.quantity }} x {{ item.name }}</span>
            <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
          </div>
        </div>

        <div class="order-footer">
          <span>Total: <strong>${{ Number(order.total).toFixed(2) }}</strong></span>
          <span class="payment-method">{{ order.payment_method === 'qr' ? 'QR Payment' : 'Cash on Delivery' }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { useRoute } from 'vue-router'

const props = defineProps<{ orders: any[] }>()
const route = useRoute()

const formatDate = (d: string) => {
  if (!d) return ''
  return new Date(d).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })
}

const stepDone = (order: any, step: number) => {
  if (order.status === 'cancelled') return false
  if (step === 1) return true
  if (step === 2) return order.status === 'confirmed' || order.status === 'completed'
  if (step === 3) return order.status === 'completed'
  return false
}

const stepActive = (order: any, step: number) => {
  if (order.status === 'cancelled') return false
  if (step === 1) return false
  if (step === 2) return order.status === 'pending' || order.status === 'processing'
  if (step === 3) return order.status === 'confirmed'
  return false
}
</script>

<style scoped>
.orders-page {
  max-width: 840px;
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
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  padding: 4rem;
  text-align: center;
  background: white;
  border-radius: 0.5rem;
  border: 1px solid #e2e8f0;
}

.shop-btn {
  display: inline-block;
  margin-top: 1rem;
  padding: 0.5rem 1.5rem;
  background: #0f766e;
  color: white;
  text-decoration: none;
  border-radius: 0.5rem;
  font-weight: 900;
}

.orders-list {
  display: flex;
  flex-direction: column;
  gap: 1.25rem;
}

.order-card {
  background: white;
  border-radius: 0.5rem;
  padding: 1.25rem 1.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
}

.order-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  margin-bottom: 1rem;
}

.order-id {
  display: block;
  font-weight: 900;
  color: #0f172a;
}

.order-header small {
  color: #64748b;
}

.status-badge {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 900;
  text-transform: capitalize;
}

.status-badge.pending { background: #fef3c7; color: #92400e; }
.status-badge.processing { background: #dbeafe; color: #1e40af; }
.status-badge.confirmed { background: #cffafe; color: #0e7490; }
.status-badge.completed { background: #d1fae5; color: #065f46; }
.status-badge.cancelled { background: #fee2e2; color: #991b1b; }

.order-progress {
  display: flex;
  align-items: center;
  gap: 0;
  margin: 1rem 0 1.25rem;
  padding: 0.75rem 1rem;
  background: #f8fafc;
  border-radius: 0.5rem;
  border: 1px solid #e2e8f0;
}

.progress-step {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 0.35rem;
  flex: 1;
  position: relative;
}

.step-dot {
  width: 14px;
  height: 14px;
  border-radius: 50%;
  background: #cbd5e1;
  border: 2px solid #e2e8f0;
}

.progress-step.done .step-dot {
  background: #0f766e;
  border-color: #0f766e;
}

.progress-step.active .step-dot {
  background: #ffffff;
  border-color: #f97316;
  box-shadow: 0 0 0 4px rgba(249, 115, 22, 0.15);
}

.step-label {
  font-size: 0.72rem;
  font-weight: 800;
  color: #94a3b8;
  white-space: nowrap;
}

.progress-step.done .step-label {
  color: #0f172a;
}

.progress-step.active .step-label {
  color: #f97316;
}

.progress-line {
  flex: 1;
  height: 2px;
  background: #e2e8f0;
  margin: 0 0.25rem;
  margin-bottom: 1.25rem;
}

.progress-line.done {
  background: #0f766e;
}

.cancel-banner {
  margin: 0.75rem 0 1rem;
  padding: 0.75rem 1rem;
  background: #fef2f2;
  border: 1px solid #fecaca;
  border-radius: 0.5rem;
  color: #991b1b;
  font-size: 0.875rem;
  font-weight: 700;
}

.order-items {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
  margin-bottom: 0.75rem;
}

.order-item {
  display: flex;
  justify-content: space-between;
  gap: 1rem;
  font-size: 0.875rem;
}

.order-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  gap: 1rem;
  padding-top: 0.75rem;
  border-top: 1px solid #f1f5f9;
  color: #64748b;
}

.order-footer strong {
  color: #0f766e;
  font-weight: 900;
}

.payment-method {
  font-size: 0.8rem;
  font-weight: 800;
  text-transform: uppercase;
  letter-spacing: 0.05em;
  color: #64748b;
}

@media (max-width: 560px) {
  .order-header,
  .order-footer {
    flex-direction: column;
    align-items: flex-start;
  }

  .step-label {
    font-size: 0.65rem;
  }
}
</style>
