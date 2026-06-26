import { createRouter, createWebHistory } from 'vue-router'
import App from '../App.vue'

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/',
      name: 'auth',
      component: App,
    },
    {
      path: '/dashboard',
      component: () => import('../views/UserDashboard.vue'),
children: [
         {
           path: '',
           redirect: 'products',
         },
         {
           path: 'products',
           name: 'products',
           component: () => import('../views/Products.vue'),
         },
         {
           path: 'profile',
           name: 'profile',
           component: () => import('../views/Profile.vue'),
           meta: { requiresAuth: true },
         },
         {
           path: 'cart',
           name: 'cart',
           component: () => import('../views/Cart.vue'),
           meta: { requiresAuth: true },
         },
         {
           path: 'checkout',
           name: 'checkout',
           component: () => import('../views/Checkout.vue'),
           meta: { requiresAuth: true },
         },
         {
           path: 'orders',
           name: 'orders',
           component: () => import('../views/Orders.vue'),
           meta: { requiresAuth: true },
         },
       ],
    },
    {
      path: '/:pathMatch(.*)',
      redirect: '/dashboard/products',
    },
  ],
})

router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  if (to.meta.requiresAuth && !token) {
    next('/')
  } else if (to.path === '/' && token) {
    next('/dashboard/products')
  } else {
    next()
  }
})

export default router
