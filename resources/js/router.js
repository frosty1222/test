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
    component:AdminVue
  },
  {
    path:'/category',
    name:'category list',
    component:ListCategory
  },
  {
    path:'/product',
    name:'product list',
    component:ListProduct
  }
];

// Create the router instance
const router = createRouter({
  history: createWebHistory(),
  routes
});

export default router;
