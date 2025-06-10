import { createApp } from 'vue'
import { createPinia } from 'pinia'
import App from './App.vue'
import router from './router'
import axios from 'axios'
import { API_URL, API_TOKEN } from './config'
import './css/main.css'

// Configuración global de axios
axios.defaults.baseURL = API_URL
axios.defaults.headers.common['Content-Type'] = 'application/json'
axios.defaults.headers.common['Accept'] = 'application/json'
axios.defaults.headers.common['Authorization'] = `Bearer ${API_TOKEN}`
axios.defaults.withCredentials = false

// Configuración CORS para axios
axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'
axios.defaults.headers.common['Access-Control-Allow-Methods'] = 'GET, POST, PUT, DELETE, OPTIONS'
axios.defaults.headers.common['Access-Control-Allow-Headers'] = 'Origin, X-Requested-With, Content-Type, Accept, Authorization'

// Interceptor para debugging
axios.interceptors.request.use(request => {
  console.log('Solicitud axios:', request.method, request.url);
  return request;
}, error => {
  console.error('Error en solicitud axios:', error);
  return Promise.reject(error);
});

axios.interceptors.response.use(response => {
  console.log('Respuesta axios:', response.status, response.data);
  return response;
}, error => {
  console.error('Error en respuesta axios:', error.response || error);
  return Promise.reject(error);
});

const app = createApp(App)

app.use(createPinia())
app.use(router)

// Agregar axios como propiedad global
app.config.globalProperties.$axios = axios

app.mount('#app') 