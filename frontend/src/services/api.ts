import { createFetch } from '@vueuse/core'
import { token as useToken } from '@/stores/auth'

export const api = createFetch({
 // baseUrl: 'http://localhost:8000/api/',
baseUrl: 'https://vetbackend.shop/api',
  options: {
    async beforeFetch({ options }) {
      const tokenStore = useToken()       
      const userToken = tokenStore.getToken()  
      
      console.log('TOKEN:', userToken)
      options.headers = {
        ...options.headers,
        Accept: 'application/json',
        'Content-Type': 'application/json',
        ...(userToken ? { Authorization: `Bearer ${userToken}` } : {})
      }

      return { options }
    },

    onFetchError({ error }) {
      console.error('API Error:', error)
      return { error }
    }
  }
});
