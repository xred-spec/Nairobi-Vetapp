import './bootstrap';
import { createApp } from 'vue';
import MyComponent from './components/MyComponent.vue'; 
const app = createApp({});
app.component('my-component', MyComponent);
app.mount('#app');
