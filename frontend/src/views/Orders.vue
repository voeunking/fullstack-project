<template>
  <div class="orders-page">
    <div class="page-header">
      <h1>My Orders</h1>
      <p class="sub">Track and manage your orders</p>
    </div>

    <div v-if="loading" class="state-center">
      <div class="spinner"></div>
      <p>Loading orders...</p>
    </div>

    <div v-else-if="orders.length === 0" class="state-center empty-orders">
      <svg fill="none" stroke="currentColor" viewBox="0 0 24 24" width="48" height="48">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
      </svg>
      <p class="empty-msg">No orders yet</p>
      <button @click="$emit('changePage', 'products')" class="shop-link">Start Shopping</button>
    </div>

    <div v-else class="orders-list">
      <div v-for="order in orders" :key="order.id" class="order-card">
        <div class="order-top">
          <div>
            <span class="order-id">Order #{{ order.id }}</span>
            <span class="order-date">{{ formatDate(order.created_at) }}</span>
          </div>
          <span :class="statusBadge(order.status)">{{ order.status }}</span>
        </div>

        <div class="order-items">
          <div v-for="item in order.items" :key="item.product_id || item.id" class="order-row">
            <div class="thumb">{{ getInitials(item.product?.name || item.name || 'P') }}</div>
            <div class="info">
              <span class="item-name">{{ item.product?.name || item.name || 'Product' }}</span>
              <span class="item-qty">Qty: {{ item.quantity }}</span>
            </div>
            <span class="item-total">${{ (item.price * item.quantity).toFixed(2) }}</span>
          </div>
        </div>

        <div class="order-bottom">
          <span class="pay-method">{{ order.payment_method === 'qr' ? 'QR Code' : 'Cash on Delivery' }}</span>
          <span class="order-total">${{ order.total.toFixed(2) }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'

defineProps<{ orders: any[] }>()
defineEmits<{
  changePage: [page: string]
  refreshProducts: []
}>()

const loading = ref(true)

onMounted(async () => {
  await new Promise(r => setTimeout(r, 400))
  loading.value = false
})

const formatDate = (d: string) => new Date(d).toLocaleDateString('en-US', {
  year: 'numeric', month: 'short', day: 'numeric', hour: '2-digit', minute: '2-digit'
})

const getInitials = (name: string) => name.split(' ').map(n => n[0]).join('').toUpperCase().slice(0, 2)

const statusBadge = (s: string) => {
  const map: any = {
    pending: 'badge badge-pending',
    processing: 'badge badge-processing',
    completed: 'badge badge-completed',
    shipped: 'badge badge-processing',
  }
  return map[s?.toLowerCase()] || 'badge'
}
</script>

<style scoped>
.orders-page {
  max-width: 900px;
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
  margin: 0.25rem 0 0;
  font-size: 0.875rem;
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

.spinner {
  width: 32px;
  height: 32px;
  border: 3px solid #e2e8f0;
  border-top-color: #0f172a;
  border-radius: 50%;
  animation: spin 0.8s linear infinite;
  margin-bottom: 1rem;
}

@keyframes spin {
  to { transform: rotate(360deg); }
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
}

.orders-list {
  display: flex;
  flex-direction: column;
  gap: 1rem;
}

.order-card {
  background: white;
  border-radius: 1rem;
  border: 1px solid #f1f5f9;
  overflow: hidden;
}

.order-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  background: #fafbfc;
  border-bottom: 1px solid #f1f5f9;
  flex-wrap: wrap;
  gap: 0.5rem;
}

.order-id {
  font-weight: 600;
  color: #0f172a;
  font-size: 0.9375rem;
  display: block;
}

.order-date {
  font-size: 0.8125rem;
  color: #64748b;
}

.badge {
  padding: 0.25rem 0.75rem;
  border-radius: 9999px;
  font-size: 0.75rem;
  font-weight: 600;
  text-transform: capitalize;
}

.badge-pending { background: #fef3c7; color: #92400e; }
.badge-processing { background: #dbeafe; color: #1e40af; }
.badge-completed { background: #d1fae5; color: #065f46; }

.order-items {
  padding: 1rem 1.25rem;
  display: flex;
  flex-direction: column;
  gap: 0.75rem;
}

.order-row {
  display: flex;
  align-items: center;
  gap: 0.75rem;
}

.thumb {
  width: 40px;
  height: 40px;
  border-radius: 0.5rem;
  background: linear-gradient(135deg, #e0e7ff, #f3e8ff);
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 700;
  color: #6366f1;
  font-size: 0.625rem;
  flex-shrink: 0;
}

.info {
  flex: 1;
  min-width: 0;
}

.item-name {
  display: block;
  font-weight: 500;
  color: #0f172a;
  font-size: 0.875rem;
}

.item-qty {
  font-size: 0.8125rem;
  color: #64748b;
}

.item-total {
  font-weight: 600;
  color: #0f172a;
  font-size: 0.875rem;
}

.order-bottom {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 0.875rem 1.25rem;
  background: #fafbfc;
  border-top: 1px solid #f1f5f9;
}

.pay-method {
  font-size: 0.8125rem;
  color: #64748b;
}

.order-total {
  font-size: 1.125rem;
  font-weight: 700;
  color: #0f172a;
}
</style>
