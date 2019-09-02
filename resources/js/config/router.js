import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

import dashboard from '../components/dashboard.vue'
import appDefaults from '../components/appDefaults/index.vue'
import orders from '../components/orders/index.vue'
import createOrder from '../components/orders/create.vue'
import drivers from '../components/drivers/index.vue'
import createDriver from '../components/drivers/create.vue'
import services from '../components/services/index.vue'
import createService from '../components/services/create.vue'
import categories from '../components/categories/index.vue'
import createCategory from '../components/categories/create.vue'
import items from '../components/items/index.vue'
import createItem from '../components/items/create.vue'

const routes = [
  {name:'dashboard',  path: '/', component: dashboard },

  {name:'appDefaults',  path: '/v/appDefaults', component: appDefaults },

  {name:'orders',  path: '/v/orders', component: orders },
  {name:'createOrder',  path: '/v/orders/create', component: createOrder },

  {name:'drivers',  path: '/v/drivers', component: drivers },
  {name:'createDriver',  path: '/v/drivers/create', component: createDriver },

  {name:'services',  path: '/v/services', component: services },
  {name:'createService',  path: '/v/services/create', component: createService },

  {name:'categories',  path: '/v/categories', component: categories },
  {name:'createCategory',  path: '/v/categories/create', component: createCategory },

  {name:'items',  path: '/v/items', component: items },
  {name:'createItem',  path: '/v/items/create', component: createItem },

]

export const router = new VueRouter({
	mode: 'history',
  	routes // short for `routes: routes`
})
