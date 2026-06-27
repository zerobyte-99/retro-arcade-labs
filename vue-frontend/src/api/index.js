// Vue.js API client
import axios from 'axios'

const api = axios.create({
    baseURL: '/api',
    headers: { 'Content-Type': 'application/json' }
})

// Add auth token from localStorage
api.interceptors.request.use(config => {
    const user = JSON.parse(localStorage.getItem('user') || '{}')
    if (user.token) {
        config.headers.Authorization = 'Bearer ' + user.token
    }
    return config
})

export default {
    login: (username, password) => api.post('/auth/login.php', { username, password }),
    getProducts: () => api.get('/products/list.php'),
    searchProducts: (q) => api.get('/products/search.php?q=' + encodeURIComponent(q)),
    getProduct: (id) => api.get('/products/list.php').then(r => r.data.products.find(p => p.id == id)),
    getCart: () => api.get('/cart/'),
    addToCart: (productId) => api.post('/cart/add', { product_id: productId }),
    createOrder: (data) => api.post('/orders/create', data),
    getOrders: () => api.get('/orders/'),
    getUser: () => api.get('/users/me.php'),
    updateProfile: (data) => api.put('/users/profile.php', data),
    createTicket: (data) => api.post('/tickets', data),
    getTickets: () => api.get('/tickets/'),
    imageFetch: (url) => api.get('/tools/image-fetch.php?url=' + encodeURIComponent(url)),
    webhookTest: (data) => api.post('/tools/webhook-test', data),
    reportGenerate: (type) => api.get('/tools/report.php?type=' + encodeURIComponent(type)),
}
