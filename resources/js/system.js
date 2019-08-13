window.Vue = require('vue');
window.axios = require('axios');

import Vue from 'vue'
import {router} from './config/router'
import {store} from './config/store'
import VueNotification from "@kugatsu/vuenotification";
import headermenu from './components/headerMenu.vue'
Vue.component('pagination', require('laravel-vue-pagination'));
Vue.use(VueNotification, {
  timer: 20
});

const app = new Vue({
    el: '#app',
    router,
    store,
    components:{headermenu},
    computed:{
      currentPage(){
        return this.$store.state.currentPage;
      },
      currentMenu(){
        return this.$store.state.currentMenu;
      }
    },
});
