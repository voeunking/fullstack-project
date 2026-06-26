<template>
  <div class="compare-overlay" @click.self="$emit('close')">
    <div class="compare-modal">
      <div class="compare-header">
        <h2>Compare Products ({{ products.length }}/3)</h2>
        <button @click="$emit('close')" class="close-btn">
          <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
          </svg>
        </button>
      </div>

      <div class="compare-table">
        <div class="compare-col">
          <div class="compare-cell label">Product</div>
          <div v-for="p in products" :key="p.id" class="compare-cell">
            {{ p.name }}
          </div>
        </div>

        <div class="compare-col">
          <div class="compare-cell label">Price</div>
          <div v-for="p in products" :key="p.id" class="compare-cell">
            <span v-if="p.discount_percent > 0" class="discounted">
              ${{ (p.price * (1 - p.discount_percent / 100)).toFixed(2) }}
            </span>
            <span v-else>${{ p.price }}</span>
          </div>
        </div>

        <div class="compare-col">
          <div class="compare-cell label">RAM</div>
          <div v-for="p in products" :key="p.id" class="compare-cell">
            {{ p.ram || '-' }}
          </div>
        </div>

        <div class="compare-col">
          <div class="compare-cell label">CPU</div>
          <div v-for="p in products" :key="p.id" class="compare-cell">
            {{ p.cpu || '-' }}
          </div>
        </div>

        <div class="compare-col">
          <div class="compare-cell label">Storage</div>
          <div v-for="p in products" :key="p.id" class="compare-cell">
            {{ p.storage || '-' }}
          </div>
        </div>

        <div class="compare-col">
          <div class="compare-cell label">Stock</div>
          <div v-for="p in products" :key="p.id" class="compare-cell">
            <span :class="{ low: p.stock < 5 }">{{ p.stock }}</span>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
defineProps<{ products: any[] }>()
defineEmits<{ close: [] }>()
</script>

<style scoped>
.compare-overlay {
  position: fixed;
  inset: 0;
  background: rgba(0, 0, 0, 0.5);
  z-index: 1000;
  display: flex;
  align-items: center;
  justify-content: center;
}

.compare-modal {
  background: white;
  border-radius: 1rem;
  max-width: 800px;
  width: 90%;
  max-height: 80vh;
  overflow-y: auto;
}

.compare-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 1rem 1.25rem;
  border-bottom: 1px solid #e2e8f0;
}

.compare-header h2 {
  font-size: 1.125rem;
  font-weight: 600;
  margin: 0;
}

.close-btn {
  background: none;
  border: none;
  color: #64748b;
  cursor: pointer;
  padding: 0.25rem;
}

.compare-table {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
  gap: 1px;
  background: #e2e8f0;
}

.compare-col {
  background: white;
}

.compare-cell {
  padding: 0.75rem 1rem;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.875rem;
}

.label {
  font-weight: 600;
  background: #f8fafc;
}

.discounted {
  color: #059669;
  font-weight: 600;
}

.low {
  color: #dc2626;
  font-weight: 600;
}
</style>