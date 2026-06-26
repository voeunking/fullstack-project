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
  background: rgba(255, 255, 255, 0.88);
  border-radius: 0.5rem;
  padding: 1rem;
  border: 1px solid rgba(203, 213, 225, 0.82);
  height: fit-content;
  position: sticky;
  top: 92px;
  box-shadow: 0 16px 30px rgba(15, 23, 42, 0.06);
}

.filter-section h3 {
  font-size: 0.875rem;
  font-weight: 900;
  color: #0f172a;
  margin: 0;
}

.filter-section p {
  margin: 0.25rem 0 0.85rem;
  color: #64748b;
  font-size: 0.82rem;
}

.category-list {
  display: flex;
  flex-direction: column;
  gap: 0.25rem;
}

.cat-item {
  text-align: left;
  min-height: 40px;
  padding: 0.55rem 0.75rem;
  background: none;
  border: none;
  border-radius: 0.5rem;
  font-size: 0.875rem;
  color: #475569;
  cursor: pointer;
  transition: all 0.15s;
  font-weight: 800;
}

.cat-item:hover {
  background: #ecfeff;
  color: #0f766e;
}

.cat-item.active {
  background: #0f766e;
  color: white;
  box-shadow: 0 10px 20px rgba(15, 118, 110, 0.18);
}

@media (max-width: 960px) {
  .filter-sidebar {
    position: static;
  }

  .category-list {
    flex-direction: row;
    overflow-x: auto;
    padding-bottom: 0.25rem;
  }

  .cat-item {
    white-space: nowrap;
  }
}
</style>
