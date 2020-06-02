/*
* @Author: @vedatbozkurt
* @Date:   2020-04-09 18:42:40
* @Last Modified by:   @vedatbozkurt
* @Last Modified time: 2020-04-18 16:35:21
*/


require('./bootstrap');

import 'es6-promise/auto'
import axios from 'axios'
import Vue from 'vue'
import VueAxios from 'vue-axios'
import VueRouter from 'vue-router'
import Index from './Index'
import router from './router'
import Swal from 'sweetalert2'
import VueProgressBar from 'vue-progressbar'
import './mixins/sweetAlert'
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(require('vue-moment'));
import i18n from "./i18n";
// Set Vue globally
window.Vue = Vue

import Gate from "./Gate";
Vue.prototype.$gate = new Gate(window.user);

// Set Vue router
Vue.router = router
Vue.use(VueRouter)

Vue.use(VueAxios, axios)
Vue.use(VueProgressBar, {
  color: 'rgb(143, 255, 199)',
  failedColor: 'red',
  height: '2px'
})

window.Swal = Swal
const Toast = Swal.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 3000,
  timerProgressBar: true,
  onOpen: (toast) => {
    toast.addEventListener('mouseenter', Swal.stopTimer)
    toast.addEventListener('mouseleave', Swal.resumeTimer)
  }
})
window.Toast = Toast

axios.defaults.baseURL = `http://127.0.0.1:8000/`

// Load Index
Vue.component('index', Index)
const app = new Vue({
  el: '#app',
  router,
  i18n,
  methods:{
      setLocale(locale) {
        console.log(locale)
        this.$i18n.locale = locale
        localStorage.setItem('userlocale', locale);
      },
    },
});
