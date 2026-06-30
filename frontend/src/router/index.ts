import { createRouter, createWebHistory } from 'vue-router'

const tokenExists = () => !!localStorage.getItem('token')

const router = createRouter({
  history: createWebHistory(),
  routes: [
    {
      path: '/home',
      name: 'home',
      component: () => import('../views/Home.vue'),
    },
    {
      path: '/about',
      name: 'about',
      component: () => import('../views/About.vue'),
    },
    {
      path: '/vendor',
      name: 'vendor',
      component: () => import('../views/Vendor.vue'),
    },
    {
      path: '/contact',
      name: 'contact',
      component: () => import('../views/Contact.vue'),
    },
    {
      path: '/',
      name: 'root',
      component: { template: '<router-view />' },
    },
    {
      path: '/register',
      name: 'register',
      component: { template: '<router-view />' },
    },

    {
      path: '/',
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
          path: 'products/:id',
          name: 'product-detail',
          component: () => import('../views/ProductDetail.vue'),
        },
        {
          path: 'profile',
          name: 'profile',
          component: () => import('../views/Profile.vue'),

        },
        {
          path: 'cart',
          name: 'cart',
          component: () => import('../views/Cart.vue'),

        },
        {
          path: 'checkout',
          name: 'checkout',
          component: () => import('../views/Checkout.vue'),

        },
        {
          path: 'orders',
          name: 'orders',
          component: () => import('../views/Orders.vue'),
        },
        {
          path: 'wishlist',
          name: 'wishlist',
          component: () => import('../views/Wishlist.vue'),
        },
      ],
    },

    {
      path: '/:pathMatch(.*)*',
      redirect: '/home',
    },
  ],
})

router.beforeEach((to) => {
  const loggedIn = tokenExists()

  // Logged-in users shouldn't see the login/register forms again.
  if ((to.name === 'root' || to.name === 'register') && loggedIn) {
    return '/home'
  }

  // Redirect bare /dashboard to either products (logged in) or home (logged out).
  if (to.path === '/dashboard') {
    return loggedIn ? '/dashboard/products' : '/home'
  }

  // If user tries to access the dashboard directly but token is missing/invalid, bounce to home.
  if (to.path.startsWith('/dashboard') && !loggedIn) {
    return '/home'
  }

  return true
})



export default router