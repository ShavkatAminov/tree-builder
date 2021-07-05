// axios
import axios from 'axios'

const domain = ''

const api = axios.create({
  domain
  // You can add your headers here
})

api.interceptors.response.use(null, error => {
  // You can check response status with error.response.status
  return Promise.reject(error)
})
export default api
