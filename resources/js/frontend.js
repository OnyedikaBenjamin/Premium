import Vue from "vue";

require('./bootstrap');

window.Vue = require('vue').default;

import { BootstrapVue } from 'bootstrap-vue'
import VCalendar from 'v-calendar';
import Vuex from 'vuex'
import Snackbar from 'node-snackbar/src/js/snackbar.js';
import Vuelidate from 'vuelidate';
import Multiselect from 'vue-multiselect'
import VueCoreVideoPlayer from 'vue-core-video-player'

import './request.js'
import router from './router.js';
import store from './store/index';
import 'node-snackbar/src/sass/snackbar.sass';
import 'nouislider/dist/nouislider.css'

import MasterLayout from "./layouts/MasterLayout";

Vue.use(VCalendar, { componentPrefix: 'vc' });
Vue.use(Vuex);
Vue.use(Snackbar);
Vue.use(BootstrapVue)
Vue.use(Vuelidate);
Vue.use(VueCoreVideoPlayer)

Vue.mixin(require('./trans'))

Vue.component('multi-select', Multiselect)
Vue.component('login-component', require('./views/Auth/Login.vue').default)
Vue.component('service-list', require('./components/Global/ServiceList.vue').default)
Vue.component('breadcrumb', require('./components/Global/BreadCrumb.vue').default)
Vue.component('rating', require('./components/Global/Rating.vue').default)
Vue.component('InfiniteLoading', require('vue-infinite-loading'));
let element = document.getElementById('app');
if (element !== null) {
  window.vm = new Vue({
    router,
    store,
    data: {
      baseUrl: baseUrl,
    },
    created() {
      const userInfo = localStorage.getItem('user')
      if (userInfo) {
          const userData = JSON.parse(userInfo)
          this.$store.commit('setUserData', userData)
      }
      axios.interceptors.response.use(
          response => response,
          error => {
              if (error.response.status === 401) {
                  this.$store.dispatch('logout')
              }
              return Promise.reject(error)
          }
      )
      this.$store.dispatch('dashboardData');
      this.$store.dispatch('categoryData')
      this.$store.dispatch('providerData')  
      },
    render: h => h(MasterLayout)
  }).$mount('#app');
}
