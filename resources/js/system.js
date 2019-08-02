window.Vue = require('vue');
window.axios = require('axios');

import Vue from 'vue'
import {router} from './config/router'
import {store} from './config/store'
import VueNotification from "@kugatsu/vuenotification";

Vue.use(VueNotification, {
  timer: 20
});

const app = new Vue({
    el: '#app',
    router,
    store,
    computed:{
      currentPage(){
        return this.$store.state.currentPage;
      },
      currentMenu(){
        return this.$store.state.currentMenu;
      }
    },
});
