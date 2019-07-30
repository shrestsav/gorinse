import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import dashboard from '../components/dashboard.vue'
import orders from '../components/orders/index.vue'
import createOrder from '../components/orders/create.vue'

const routes = [
  {name:'dashboard',  path: '/', component: dashboard },

  {name:'orders',  path: '/v/orders', component: orders },
  {name:'createOrder',  path: '/v/orders/create', component: createOrder },

]

export const router = new VueRouter({
	mode: 'history',
  	routes // short for `routes: routes`
})
