<template>
  <div class="cart-item">
    <div class="item-image">
      <img v-if="itemImage" :src="backendUrl + '/storage/' + itemImage" :alt="item.name" />
      <div v-else class="placeholder">{{ initials }}</div>
    </div>

    <div class="item-details">
      <h4>{{ item.name }}</h4>
      <p class="item-price">${{ item.price }}</p>

      <div class="quantity-controls">
        <button @click="updateQty(-1)" class="qty-btn">-</button>
        <span>{{ item.quantity }}</span>
        <button @click="updateQty(1)" class="qty-btn">+</button>
      </div>
    </div>

    <div class="item-total">
      ${{ (item.price * item.quantity).toFixed(2) }}
    </div>

    <button @click="$emit('remove', item.id)" class="remove-btn">
      <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
      </svg>
    </button>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ item: any }>()
const emit = defineEmits<{
  update: [id: number, qty: number]
  remove: [id: number]
}>()

const backendUrl = 'http://127.0.0.1:8000'
const initials = computed(() => props.item.name?.split(' ')?.map((n: string) => n[0])?.join('')?.toUpperCase()?.slice(0, 2) || '?')
const itemImage = computed(() => props.item.product?.image || props.item.image)

const updateQty = (delta: number) => {
  const newQty = props.item.quantity + delta
  if (newQty <= 0) {
    emit('remove', props.item.id)
  } else {
    emit('update', props.item.id, newQty)
  }
}
</script>

<style scoped>
.cart-item {
  display: flex;
  align-items: center;
  gap: 1rem;
  background: white;
  padding: 1rem;
  border-radius: 0.5rem;
  border: 1px solid #e2e8f0;
  box-shadow: 0 12px 26px rgba(15, 23, 42, 0.06);
}

.item-image {
  width: 80px;
  height: 80px;
  border-radius: 0.5rem;
  overflow: hidden;
  background: linear-gradient(135deg, rgba(20, 184, 166, 0.14), rgba(249, 115, 22, 0.12));
  flex: 0 0 auto;
}

.item-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.placeholder {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: 600;
  color: #0f766e;
}

.item-details {
  flex: 1;
}

.item-details h4 {
  margin: 0 0 0.25rem;
  font-weight: 900;
  color: #0f172a;
}

.item-price {
  color: #64748b;
  font-size: 0.875rem;
  margin: 0 0 0.5rem;
}

.quantity-controls {
  display: flex;
  align-items: center;
  gap: 0.5rem;
}

.qty-btn {
  width: 28px;
  height: 28px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
  border-radius: 0.25rem;
  cursor: pointer;
  font-weight: 900;
  color: #0f172a;
}

.qty-btn:hover {
  background: #ccfbf1;
  border-color: #99f6e4;
}

.item-total {
  font-weight: 900;
  min-width: 80px;
  text-align: right;
  color: #0f766e;
}

.remove-btn {
  background: none;
  border: none;
  color: #94a3b8;
  cursor: pointer;
  padding: 0.25rem;
}

.remove-btn:hover {
  color: #ef4444;
  background: #fef2f2;
}

@media (max-width: 620px) {
  .cart-item {
    align-items: flex-start;
    flex-wrap: wrap;
  }

  .item-details {
    min-width: calc(100% - 100px);
  }

  .item-total {
    margin-left: 96px;
    text-align: left;
  }
}
</style>
