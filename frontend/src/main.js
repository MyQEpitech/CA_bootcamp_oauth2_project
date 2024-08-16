import './assets/main.css'

import { createApp } from 'vue'
import { createPinia } from 'pinia'

import App from './App.vue'
import router from './router'
import axios from 'axios'

axios.defaults.baseURL = import.meta.env.VITE_API_BASE_URL

axios.interceptors.request.use((config) => {
  const userData = localStorage.getItem('userData')
  if (userData) {
    const token = JSON.parse(userData).token
    config.headers['Authorization'] = 'Bearer ' + token
  }
  return config
})

axios.interceptors.response.use(
  (response) => {
    return response
  },
  (error) => {
    if (error.response.status === 401) {
      localStorage.removeItem('userData')
      router.push('/login')
    }
    return Promise.reject(error)
  }
)

const app = createApp(App)

app.use(createPinia())
app.use(router)

app.mount('#app')
