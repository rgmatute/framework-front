// import Vue from 'vue'
import axios from 'axios'
import { Cookies } from 'quasar'

/* const axiosInstance = axios.create({
  baseURL: 'http://localhost/repository/api/v1'
}) */

// Vue.prototype.$axios = axios

export default ({ Vue }) => {
  axios.defaults.headers.common.Accept = 'application/json'
  axios.defaults.headers.common['Content-Type'] = 'application/json'
  // axios.defaults.headers.common['Access-Control-Allow-Origin'] = '*'
  // axios.defaults.headers.common['Access-Control-Allow-Methods'] = 'GET, POST, PUT, PATCH, DELETE, OPTIONS'
  // if (Cookies.has('token')) {
  axios.defaults.headers.common.Authorization = Cookies.has('token') ? Cookies.get('token') : ''
  // }
  // axios.defaults.baseURL = 'http://localhost/repository/api/v1'
  axios.defaults.baseURL = 'https://app-repository.azurewebsites.net/api/v1'
  axios.defaults.maxRedirects = 1

  Vue.prototype.$axios = axios
}
// export { axiosInstance }
