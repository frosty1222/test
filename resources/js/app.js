import './bootstrap';
import { createApp } from 'vue';
import router from './router';
import naive from './naive'  
const app = createApp({})
app.use(router,naive)
app.mount('#app')