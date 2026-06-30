<template>
  <div class="product-card" @click="viewDetail">
    <div class="card-image-wrap">
      <img 
        v-if="product.image" 
        :src="backendUrl + '/storage/' + product.image" 
        :alt="product.name" 
        class="product-image"
      />
      <div v-else class="placeholder-img">{{ initials }}</div>
      
      <div class="image-top-badges">
        <span v-if="product.discount_percent > 0" class="badge sale">SALE</span>
        <span v-else-if="product.stock > 0 && product.stock < 5" class="badge hot">HOT</span>
      </div>
    </div>

    <div class="card-body">
      <span class="category">{{ product.category?.name || 'Vegetables' }}</span>
      
      <h3 class="product-title">{{ product.name }}</h3>
      
      <div class="rating-row">
        <div class="stars-outer">
          <span class="stars-inner">★★★★★</span>
        </div>
        <span class="rating-num">({{ Number(product.rating || 4.0).toFixed(1) }})</span>
      </div>

      <div class="vendor-text">
        By <span class="vendor-name">{{ product.vendor || 'Organic Bliss' }}</span>
      </div>

      <div class="card-footer">
        <div class="price-section">
          <span v-if="product.discount_percent > 0" class="final-price">${{ finalPrice }}</span>
          <span v-else class="price">${{ Number(product.price).toFixed(2) }}</span>
          
          <span v-if="product.discount_percent > 0" class="original-price">${{ Number(product.price).toFixed(2) }}</span>
        </div>

        <button
          @click.stop="handleAddToCart"
          :disabled="product.stock === 0"
          class="add-btn"
        >
          <svg class="cart-icon" width="14" height="14" fill="currentColor" viewBox="0 0 24 24">
            <path d="M7 18c-1.1 0-1.99.9-1.99 2S5.9 22 7 22s2-.9 2-2-.9-2-2-2zM1 2v2h2l3.6 7.59-1.35 2.45c-.16.28-.25.61-.25.96 0 1.1.9 2 2 2h12v-2H7.42c-.14 0-.25-.11-.25-.25l.03-.12.9-1.63h7.45c.75 0 1.41-.41 1.75-1.03l3.58-6.49c.08-.14.12-.31.12-.48 0-.55-.45-1-1-1H5.21l-.94-2H1zm16 16c-1.1 0-1.99.9-1.99 2s.89 2 1.99 2 2-.9 2-2-.9-2-2-2z"/>
          </svg>
          {{ product.stock === 0 ? 'Sold' : 'Add' }}
        </button>

        <button
          @click.stop="viewDetail"
          class="detail-btn"
        >
          <svg class="eye-icon" width="14" height="14" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
            <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
          </svg>
          Detail
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import { useRouter } from 'vue-router'


const props = defineProps<{ product: any }>()
const emit = defineEmits<{
  'add-to-cart': [product: any]
}>()

const router = useRouter()
const backendUrl = (import.meta.env.VITE_BACKEND_URL || 'http://127.0.0.1:8000').replace(/\/$/, '')

const initials = computed(() => props.product.name?.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2) || 'P')
const finalPrice = computed(() => (props.product.price * (1 - props.product.discount_percent / 100)).toFixed(2))

const handleAddToCart = () => {
  if (props.product.stock === 0) return
  emit('add-to-cart', props.product)
}

const viewDetail = () => {
  if (!props.product?.id) return
  router.push({ name: 'product-detail', params: { id: props.product.id } })
}
</script>

<style scoped>
/* Main Grid/Card Layout */
.product-card {
  font-family: 'Lato', 'Inter', sans-serif;
  position: relative;
  background: #ffffff;
  border: 1px solid #ececec;
  border-radius: 15px;
  overflow: hidden;
  transition: all 0.25s ease-in-out;
  display: flex;
  flex-direction: column;
  cursor: pointer;
  box-sizing: border-box;
}

.product-card:hover {
  border-color: #BCE3C9;
  box-shadow: 20px 20px 54px rgba(0, 0, 0, 0.03);
  transform: translateY(-5px);
}

/* Image Wrappers */
.card-image-wrap {
  position: relative;
  padding: 25px 25px 10px 25px;
  display: flex;
  justify-content: center;
  align-items: center;
  height: 180px;
  background-color: #fff;
}

.product-image {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
  transition: transform 0.3s ease;
}

.product-card:hover .product-image {
  transform: scale(1.05);
}

.placeholder-img {
  width: 100px;
  height: 100px;
  background: #f4f6fa;
  color: #7e7e7e;
  display: flex;
  align-items: center;
  justify-content: center;
  font-weight: bold;
  border-radius: 50%;
}

/* Badges styling matching 'HOT' & 'SALE' */
.image-top-badges {
  position: absolute;
  top: 0;
  left: 0;
  z-index: 2;
}

.badge {
  display: inline-block;
  padding: 6px 12px 6px 15px;
  font-size: 11px;
  font-weight: 500;
  color: #fff;
  border-bottom-right-radius: 20px;
  text-transform: capitalize;
}

.badge.hot {
  background-color: #FD6E6E;
}

.badge.sale {
  background-color: #f74b81;
}

/* Content Area layout */
.card-body {
  padding: 0 20px 20px 20px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.category {
  font-size: 12px;
  color: #adadad;
  font-weight: 400;
  margin-bottom: 4px;
  text-transform: capitalize;
}

.product-title {
  font-size: 16px;
  font-weight: 700;
  color: #253D4E;
  margin: 0 0 8px 0;
  line-height: 1.3;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
  min-height: 42px;
}

/* Rating setup */
.rating-row {
  display: flex;
  align-items: center;
  gap: 5px;
  margin-bottom: 6px;
}

.stars-inner {
  color: #ffb300;
  font-size: 12px;
  letter-spacing: 1px;
}

.rating-num {
  font-size: 12px;
  color: #b6b6b6;
}

/* Brand Meta */
.vendor-text {
  font-size: 12px;
  color: #B6B6B6;
  margin-bottom: 15px;
}

.vendor-name {
  color: #3BB77E;
}

/* Footer layout (Price & Button aligned horizontally) */
.card-footer {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-top: auto;
  gap: 10px;
  flex-wrap: wrap;
}

.price-section {
  display: flex;
  align-items: flex-end;
  gap: 6px;
  flex-shrink: 0;
}

.price, .final-price {
  font-size: 18px;
  font-weight: 700;
  color: #3BB77E;
}

.original-price {
  font-size: 13px;
  color: #adadad;
  text-decoration: line-through;
  margin-bottom: 2px;
}

/* The Flat Modern 'Add' Button */
.add-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 16px;
  background-color: #DEF9EC;
  color: #3BB77E;
  border: none;
  border-radius: 4px;
  font-size: 14px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.add-btn:hover {
  background-color: #3BB77E;
  color: #ffffff;
}

.add-btn:disabled {
  background-color: #f4f6fa;
  color: #b6b6b6;
  cursor: not-allowed;
}

.detail-btn {
  display: flex;
  align-items: center;
  gap: 6px;
  padding: 8px 14px;
  background-color: #ffffff;
  color: #4b5563;
  border: 1px solid #e5e7eb;
  border-radius: 4px;
  font-size: 13px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s ease;
  white-space: nowrap;
}

.detail-btn:hover {
  background-color: #f6fbf9;
  border-color: #046c4e;
  color: #046c4e;
}

.eye-icon {
  display: inline-block;
}

.cart-icon {
  display: inline-block;
}
</style>