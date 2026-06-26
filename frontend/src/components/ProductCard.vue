<template>
  <div class="product-card">
    <div class="card-image">
      <img v-if="product.image" :src="backendUrl + '/storage/' + product.image" :alt="product.name" />
      <div v-else class="placeholder-img">{{ initials }}</div>
      <span v-if="product.discount_percent > 0" class="badge discount">-{{ product.discount_percent }}%</span>
      <span v-else-if="product.stock < 5" class="badge stock-alert">Low Stock</span>
    </div>

    <div class="card-body">
      <div class="card-meta">
        <span class="category">{{ product.category?.name || 'Featured' }}</span>
        <span class="rating">4.8</span>
      </div>
      <h3>{{ product.name }}</h3>

      <div class="price-section">
        <span v-if="product.discount_percent > 0" class="final-price">${{ finalPrice }}</span>
        <span v-else class="price">${{ product.price }}</span>
        <span v-if="product.discount_percent > 0" class="original-price">${{ product.price }}</span>
      </div>

      <p class="desc">{{ product.description || 'A customer favorite with the performance and value you need.' }}</p>

      <div class="specs" v-if="product.ram || product.cpu || product.storage">
        <span v-if="product.ram">RAM: {{ product.ram }}</span>
        <span v-if="product.cpu">CPU: {{ product.cpu }}</span>
        <span v-if="product.storage">Storage: {{ product.storage }}</span>
      </div>

      <div class="stock-info" v-if="product.stock < 5 && product.stock > 0">
        Only {{ product.stock }} left!
      </div>
      <div class="stock-info out" v-if="product.stock === 0">
        Out of stock
      </div>

      <div class="card-actions">
        <button
          @click="$emit('add-to-cart', product)"
          :disabled="product.stock === 0"
          class="add-btn"
        >
          {{ product.stock === 0 ? 'Sold Out' : 'Add to Cart' }}
        </button>
        <button
          @click="$emit('compare', product)"
          :class="['compare-btn', { active: isInCompare }]"
          title="Compare"
        >
          <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V5l12-2-12 2V5"/>
          </svg>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ product: any }>()
const emit = defineEmits<{
  'add-to-cart': [product: any]
  'compare': [product: any]
}>()

const backendUrl = 'http://127.0.0.1:8000'
const initials = computed(() => props.product.name.split(' ').map((n: string) => n[0]).join('').toUpperCase().slice(0, 2))
const finalPrice = computed(() => (props.product.price * (1 - props.product.discount_percent / 100)).toFixed(2))

const isInCompare = computed(() => false)
</script>

<style scoped>
.product-card {
  background: white;
  border-radius: 0.5rem;
  overflow: hidden;
  border: 1px solid rgba(226, 232, 240, 0.9);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  box-shadow: 0 14px 30px rgba(15, 23, 42, 0.06);
}

.product-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 24px 45px rgba(15, 23, 42, 0.12);
}

.card-image {
  height: 190px;
  background:
    linear-gradient(135deg, rgba(20, 184, 166, 0.14), rgba(249, 115, 22, 0.12)),
    #f8fafc;
  position: relative;
  overflow: hidden;
}

.card-image img {
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.35s ease;
}

.product-card:hover .card-image img {
  transform: scale(1.04);
}

.placeholder-img {
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  font-weight: 700;
  color: #0f766e;
}

.badge {
  position: absolute;
  top: 0.65rem;
  right: 0.65rem;
  padding: 0.32rem 0.6rem;
  border-radius: 0.25rem;
  font-size: 0.75rem;
  font-weight: 900;
  box-shadow: 0 10px 20px rgba(15, 23, 42, 0.12);
}

.discount {
  background: #f97316;
  color: #ffffff;
}

.stock-alert {
  background: #fef3c7;
  color: #d97706;
}

.card-body {
  padding: 1rem;
}

.card-meta {
  display: flex;
  align-items: center;
  justify-content: space-between;
  gap: 0.75rem;
}

.category {
  font-size: 0.75rem;
  color: #0f766e;
  text-transform: uppercase;
  letter-spacing: 0;
  font-weight: 900;
}

.rating {
  display: inline-flex;
  align-items: center;
  min-height: 24px;
  padding: 0.15rem 0.45rem;
  border-radius: 999px;
  background: #fef3c7;
  color: #92400e;
  font-size: 0.75rem;
  font-weight: 900;
}

.card-body h3 {
  min-height: 2.6rem;
  font-size: 1.05rem;
  line-height: 1.25;
  font-weight: 900;
  color: #0f172a;
  margin: 0.35rem 0;
}

.price-section {
  display: flex;
  align-items: baseline;
  gap: 0.5rem;
  margin: 0.55rem 0;
}

.price {
  font-size: 1.3rem;
  font-weight: 900;
  color: #0f172a;
}

.final-price {
  font-size: 1.3rem;
  font-weight: 900;
  color: #0f766e;
}

.original-price {
  font-size: 0.875rem;
  color: #94a3b8;
  text-decoration: line-through;
}

.desc {
  font-size: 0.8rem;
  color: #64748b;
  margin: 0.5rem 0;
  line-height: 1.4;
  min-height: 2.25rem;
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

.specs {
  display: grid;
  grid-template-columns: 1fr;
  gap: 0.35rem;
  font-size: 0.75rem;
  color: #334155;
  margin: 0.65rem 0;
}

.specs span {
  padding: 0.35rem 0.5rem;
  border-radius: 0.35rem;
  background: #f8fafc;
  font-weight: 700;
}

.stock-info {
  font-size: 0.75rem;
  color: #d97706;
  font-weight: 500;
  margin-bottom: 0.5rem;
}

.stock-info.out {
  color: #dc2626;
}

.card-actions {
  display: flex;
  gap: 0.5rem;
  margin-top: 0.9rem;
}

.add-btn {
  flex: 1;
  min-height: 42px;
  padding: 0.65rem;
  background: linear-gradient(135deg, #0f172a, #134e4a);
  color: white;
  border: none;
  border-radius: 0.5rem;
  font-weight: 900;
  cursor: pointer;
  box-shadow: 0 12px 22px rgba(15, 23, 42, 0.18);
  transition: all 0.2s ease;
}

.add-btn:hover:not(:disabled) {
  transform: translateY(-1px);
  box-shadow: 0 16px 28px rgba(15, 23, 42, 0.22);
}

.add-btn:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

.compare-btn {
  width: 42px;
  min-width: 42px;
  min-height: 42px;
  padding: 0.5rem;
  background: #f1f5f9;
  border: 1px solid #e2e8f0;
  border-radius: 0.5rem;
  color: #64748b;
  cursor: pointer;
}

.compare-btn:hover,
.compare-btn.active {
  background: #ccfbf1;
  color: #0f766e;
}
</style>
