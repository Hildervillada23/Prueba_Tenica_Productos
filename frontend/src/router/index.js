import { createRouter, createWebHistory } from 'vue-router'

const routes = [
  {
    path: '/',
    name: 'home',
    component: () => import('../views/HomeView.vue')
  },
  {
    path: '/products',
    name: 'products',
    component: () => import('../views/ProductsView.vue'),
    meta: { forceReload: true }
  },
  {
    path: '/products/new',
    name: 'product-create',
    component: () => import('../views/ProductFormView.vue')
  },
  {
    path: '/products/:id/edit',
    name: 'product-edit',
    component: () => import('../views/ProductFormView.vue')
  },
  {
    path: '/products/:id',
    name: 'product-detail',
    component: () => import('../views/ProductDetailView.vue')
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

router.beforeEach((to, from, next) => {
  console.log(`Navegando de '${from.path}' a '${to.path}'`);
  
  if ((from.name === 'product-create' || from.name === 'product-edit') && to.name === 'products') {
    console.log('Navegación después de operación CRUD, forzando recarga');
    to.params.timestamp = Date.now();
  }
  
  next();
})

export default router 