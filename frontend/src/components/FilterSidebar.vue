<template>
  <aside class="filter-sidebar">
    <div class="filter-section">
      <h3>Categories</h3>
      <p>Find the right product faster.</p>
      <div class="category-list">
        <button
          :class="['cat-item', { active: selected === '' }]"
          @click="$emit('change', '')"
        >
          All Products
        </button>
        <button
          v-for="cat in categories"
          :key="cat.id"
          :class="['cat-item', { active: selected == cat.id }]"
          @click="$emit('change', cat.id)"
        >
          {{ cat.name }}
        </button>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
const props = defineProps<{
  categories: any[]
  selected: string
}>()

const emit = defineEmits<{
  change: [id: string]
}>()
</script>

<style scoped>
.filter-sidebar {
  background: var(--surface);
  border-radius: var(--radius-xl);
  padding: var(--spacing-lg);
  border: 1px solid var(--border-light);
  margin-top: var(--spacing-lg);
  margin-bottom: var(--spacing-xl);
  box-shadow: var(--shadow-md);
}

.filter-section h3 {
  font-size: var(--text-base);
  font-weight: var(--font-black);
  color: var(--text-primary);
  margin: 0;
}

.filter-section p {
  margin: var(--spacing-xs) 0 var(--spacing-md);
  color: var(--text-secondary);
  font-size: var(--text-sm);
}

.category-list {
  display: flex;
  flex-wrap: wrap;
  gap: var(--spacing-sm);
}

.cat-item {
  text-align: left;
  min-height: 44px;
  padding: var(--spacing-sm) var(--spacing-md);
  background: var(--slate-50);
  border: 1px solid var(--border-light);
  border-radius: var(--radius-lg);
  font-size: var(--text-sm);
  color: var(--text-secondary);
  cursor: pointer;
  transition: all var(--transition-fast);
  font-weight: var(--font-bold);
}

.cat-item:hover {
  background: var(--primary-50);
  color: var(--primary-700);
  border-color: var(--primary-200);
}

.cat-item.active {
  background: var(--primary-600);
  color: var(--surface);
  border-color: var(--primary-600);
  box-shadow: var(--shadow-md);
}

@media (max-width: 640px) {
  .filter-sidebar {
    padding: var(--spacing-md);
    margin-top: var(--spacing-lg);
  }

  .category-list {
    gap: var(--spacing-xs);
  }

  .cat-item {
    min-height: 40px;
    padding: var(--spacing-xs) var(--spacing-sm);
    font-size: var(--text-xs);
  }
}
</style>
