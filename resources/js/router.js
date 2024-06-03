// resources/js/router.js
import { createRouter, createWebHistory } from 'vue-router';
import LoginView from './components/Login.vue'
import AdminVue from './layouts/Admin.vue';
import RegisterVue from './components/Register.vue';
import ListCategory from './components/category/ListCategory.vue'
import ListProduct from './components/products/ListProduct.vue'
// Define your routes
const routes = [
  {
    path: '/',
    name: 'login',
    component:LoginView
  },
  {
    path:'/register',
    name:'register',
    component:RegisterVue
  },
  {
    path:'/admin',
    name:'admin',
    component:AdminVue,
    meta: { requiresAuth: true }
  },
  {
    path:'/category',
    name:'category list',
    component:ListCategory,
    meta: { requiresAuth: true }
  },
  {
    path:'/product',
    name:'product list',
    component:ListProduct,
    meta: { requiresAuth: true }
  }
];

// Create the router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});
router.beforeEach((to, from, next) => {
  if (to.matched.some(record => record.meta.requiresAuth)) {
    const token = localStorage.getItem('token');
    if (!token) {
      next({ path: '/' });
    } else {
      next();
    }
  } else {
    next();
  }
});

export default router;
