import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import dashboard from '../components/dashboard.vue'
import orders from '../components/orders/index.vue'
import createOrder from '../components/orders/create.vue'
import drivers from '../components/drivers/index.vue'
import createDriver from '../components/drivers/create.vue'
import services from '../components/services/index.vue'
import createService from '../components/services/create.vue'

const routes = [
  {name:'dashboard',  path: '/', component: dashboard },

  {name:'orders',  path: '/v/orders', component: orders },
  {name:'createOrder',  path: '/v/orders/create', component: createOrder },

  {name:'drivers',  path: '/v/drivers', component: drivers },
  {name:'createDriver',  path: '/v/drivers/create', component: createDriver },

  {name:'services',  path: '/v/services', component: services },
  {name:'createService',  path: '/v/services/create', component: createService },

]

export const router = new VueRouter({
	mode: 'history',
  	routes // short for `routes: routes`
})
