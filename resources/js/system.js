require('./bootstrap');
window.Vue = require('vue');
window.axios = require('axios');

import Vue from 'vue'
import {router} from './config/router'
import {store} from './config/store'
import VueNotification from "@kugatsu/vuenotification"
import headermenu from './components/headerMenu.vue'
import notification from './components/notification.vue'
import VueSweetalert2 from 'vue-sweetalert2'
import VueMoment from 'vue-moment'
import VueProgressBar from 'vue-progressbar'

// If you don't need the styles, do not connect
import 'sweetalert2/dist/sweetalert2.min.css';
Vue.use(VueSweetalert2);
Vue.use(VueMoment);
Vue.use(VueProgressBar, {
  color: '#2dce89',
  failedColor: 'red',
  thickness: '4px',
  height: '2px'
})

Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VueNotification, {
  timer: 20
});

const app = new Vue({
    el: '#app',
    router,
    store,
    components:{headermenu,notification},
    computed:{
      currentPage(){
        return this.$store.state.currentPage;
      },
      currentMenu(){
        return this.$store.state.currentMenu;
      }
    },
    mounted(){
      // Intercept all requests
      window.axios.interceptors.request.use(
        (config) => {
         // do something before sending requests
          this.$Progress.start();
          return config
        },
        (error) => {
          return Promise.reject(error);
        }
      );

      // Intercept all responses
      window.axios.interceptors.response.use(
        (response) => {
          this.$Progress.finish();
         // when success from server
          return response;
        },

        (error) => {
         // when error from server
          return Promise.reject(error);
        }
      );
    }
});
