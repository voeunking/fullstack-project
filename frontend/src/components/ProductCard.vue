<template>
  <div class="product-card">
    <div class="card-glow" aria-hidden="true"></div>

    <div class="card-image">
      <img v-if="product.image" :src="backendUrl + '/storage/' + product.image" :alt="product.name" />
      <div v-else class="placeholder-img">{{ initials }}</div>

      <div class="image-top-badges">
        <span v-if="product.discount_percent > 0" class="badge discount">New Deal -{{ product.discount_percent }}%</span>
        <span v-else-if="product.stock > 0 && product.stock < 5" class="badge stock-alert">Hurry! {{ product.stock }} left</span>
        <span v-else class="badge new-arrival">New Arrivals</span>
      </div>
    </div>

    <div class="card-body">
      <div class="card-meta">
        <span class="category">{{ product.category?.name || 'Featured' }}</span>

        <span class="rating" title="Rating">
          <span class="stars" aria-hidden="true">★★★★★</span>
          <span class="rating-num">4.8</span>
        </span>
      </div>

      <h3 class="product-title">{{ product.name }}</h3>

      <div class="price-section">
        <span v-if="product.discount_percent > 0" class="final-price">${{ finalPrice }}</span>
        <span v-else class="price" >${{ Number(product.price).toFixed(2) }}</span>

        <span v-if="product.discount_percent > 0" class="original-price">${{ Number(product.price).toFixed(2) }}</span>
      </div>

      <p class="desc">{{ product.description || 'A customer favorite with the performance and value you need.' }}</p>

      <div class="specs" v-if="product.ram || product.cpu || product.storage">
        <span v-if="product.ram">RAM: {{ product.ram }}</span>
        <span v-if="product.cpu">CPU: {{ product.cpu }}</span>
        <span v-if="product.storage">Storage: {{ product.storage }}</span>
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
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V5l12-2-12 2V5" />
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
  position: relative;
  width: 100%;
  max-width: 500px;
  background: rgba(255,255,255,0.85);
  backdrop-filter: blur(20px);
  border-radius: 28px;
  overflow: hidden;

  border: 1px solid rgba(255,255,255,0.5);

  box-shadow:
    0 20px 40px rgba(15,23,42,.12),
    inset 0 1px 0 rgba(255,255,255,.5);

  transition: all .45s cubic-bezier(.2,.8,.2,1);
}


.product-card:hover {

  transform:
    translateY(-14px)
    scale(1.02);

  box-shadow:
    0 30px 70px rgba(15,23,42,.25);
}



/* animated light */

.card-glow {

 position:absolute;

 width:220px;
 height:220px;

 top:-100px;
 right:-100px;

 background:
 linear-gradient(
  135deg,
  #6366f1,
  #06b6d4,
  #22c55e
 );

 filter:blur(60px);

 opacity:.35;

}



/* image */


.card-image {

 position:relative;

 height:260px;

 overflow:hidden;

 background:
 linear-gradient(
 135deg,
 #f8fafc,
 #eef2ff
 );

}



.card-image img {

 width:100%;
 height:100%;

 object-fit:cover;

 transition:.6s;

}



.product-card:hover img {

 transform:scale(1.15);

}




/* image dark overlay */


.card-image::after {

content:"";

position:absolute;

inset:0;

background:
linear-gradient(
to top,
rgba(0,0,0,.35),
transparent 45%
);

opacity:0;

transition:.3s;

}



.product-card:hover .card-image::after{

opacity:1;

}



/* badges */


.image-top-badges {

position:absolute;

top:18px;
left:18px;

display:flex;
flex-direction:column;
gap:10px;

z-index:3;

}



.badge {

padding:8px 14px;

border-radius:999px;

font-size:12px;

font-weight:800;

letter-spacing:.3px;

color:white;

box-shadow:
0 8px 20px rgba(0,0,0,.2);

backdrop-filter:blur(10px);

}



.discount {

background:
linear-gradient(
135deg,#ef4444,#fb7185
);

}


.stock-alert {

background:
linear-gradient(
135deg,#f59e0b,#f97316
);

}


.new-arrival {

background:
linear-gradient(
135deg,#10b981,#14b8a6
);

}



/* content */


.card-body {

padding:24px;

}




.card-meta {

display:flex;

justify-content:space-between;

align-items:center;

margin-bottom:14px;

}



.category {

background:#eef2ff;

color:#4f46e5;

padding:6px 12px;

border-radius:999px;

font-size:12px;

font-weight:800;

}




.rating {

display:flex;

align-items:center;

gap:5px;

background:#fff7ed;

padding:5px 10px;

border-radius:20px;

}



.stars {

color:#f59e0b;

letter-spacing:2px;

}



.rating-num {

font-weight:700;

font-size:13px;

}





/* title */


.product-title {

font-size:22px;

font-weight:900;

color:#0f172a;

margin:12px 0;

line-height:1.3;


display:-webkit-box;

-webkit-line-clamp:2;

-webkit-box-orient:vertical;

overflow:hidden;

}



/* price */


.price-section {

/* display:flex; */

/* align-items:center; */

/* gap:12px; */

/* margin:15px 0; */

}



.final-price,
.price {


font-size:20px;

font-weight:500;



background:
linear-gradient(
90deg,#3a68cc,#7c3aed
);

-webkit-background-clip:text;

-webkit-text-fill-color:transparent;

}



.original-price {

font-size:15px;

color:#94a3b8;

text-decoration:line-through;

}



/* description */


.desc {

font-size:14px;

color:#64748b;

line-height:1.7;

margin-bottom:18px;


display:-webkit-box;

-webkit-line-clamp:2;

-webkit-box-orient:vertical;

overflow:hidden;

}





/* specs */


.specs {

display:flex;

gap:8px;

flex-wrap:wrap;

margin-bottom:20px;

}



.specs span {

padding:8px 12px;

background:#f8fafc;

border-radius:12px;

font-size:12px;

font-weight:700;

color:#475569;

border:1px solid #e2e8f0;

}




/* buttons */


.card-actions {

display:flex;

gap:12px;

}



.add-btn {

flex:1;
/* padding:1px; */

/* height:52px; */

border:none;
border-radius:16px;


background:
linear-gradient(
135deg,
#2563eb,
#7c3aed
);


color:white;

font-weight:700;

font-size:15px;

cursor:pointer;


box-shadow:
0 12px 25px rgba(79,70,229,.35);


transition:.3s;

}



.add-btn:hover {

transform:translateY(-3px);

box-shadow:
0 18px 35px rgba(79,70,229,.45);

}




.add-btn:disabled {

background:#94a3b8;

box-shadow:none;

}



/* compare button */


.compare-btn {

width:52px;

height:42px;

border-radius:16px;

border:2px solid #e2e8f0;

background:white;

color:#64748b;


display:flex;

align-items:center;

justify-content:center;


cursor:pointer;

transition:.3s;

}



.compare-btn:hover,
.compare-btn.active {


background:#0f172a;

color:white;

border-color:#0f172a;

transform:rotate(5deg);

}




/* responsive */


@media(max-width:600px){

.product-card{

max-width:100%;

}

.card-image{

height:220px;

}

.product-title{

font-size:20px;

}

} 
</style>
