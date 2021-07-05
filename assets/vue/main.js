import Vue from 'vue'
import store from './store'
import axios from './axios.js'
import App from "./App";

// axios

Vue.config.productionTip = false

Vue.prototype.$http = axios

new Vue({
  store,
  render: h => h(App)
}).$mount('#app')
