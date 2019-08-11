import Vue from 'vue'
import Vuex from 'vuex'

Vue.use(Vuex)

export const store = new Vuex.Store({
	state:{
		currentPage:'',
		currentMenu:'',
		orders:[],
		drivers:[],
		customers:{},
		orderStatus:{},
		errors:{},
	},
	getters:{
		orders(state){
			return state.orders;
		},
		drivers(state){
			return state.drivers;
		},
		customers(state){
			return state.customers;
		},
		orderStatus(state){
			return state.orderStatus;
		},
		errors(state){
			return state.errors;
		}
	},
	mutations:{ 
		changeCurrentPage(state, currentPage) {
			state.currentPage = currentPage
		},
		changeCurrentMenu(state, currentMenu) {
			state.currentMenu = currentMenu
		},
		setOrders(state, orders){
			state.orders = orders
		},
		setDrivers(state, drivers){
			state.drivers = drivers
		},
		setCustomers(state, customers){
			state.customers = customers
		},
		setOrderStatus(state, orderStatus){
			state.orderStatus = orderStatus
		},
		setErrors(state, errors){
			state.errors = errors
		}
	},
	actions:{
		getOrderStatus(context){
			axios.get('/getSettings/orderStatus')
	        .then(response => {
	        	context.commit('setOrderStatus',response.data)
	        });
		},
		getOrders(context,orderObj){
			axios.get('/getOrders/'+orderObj.status+'?page=' + orderObj.page)
	        .then(response => {
	        	context.commit('setOrders',response.data)
	        });
		},
		getDrivers(context){
			axios.get('/drivers')
	        .then(response => {
	        	context.commit('setDrivers',response.data)
	        });
		},
		getCustomers(context){
			axios.get('/getCustomers')
		        .then(response => {
		          context.commit('setCustomers',response.data)
		        });	
		},
		addOrder(context, order){
			axios.post('/orders',order)
	          .then((response) => {
	          	context.commit('setErrors',{})
	            showNotify('success','Order has been created')
	          })
	          .catch((error) => {
      			context.commit('setErrors',error.response.data.errors)
	            for (var prop in error.response.data.errors) {
	              showNotify('danger',error.response.data.errors[prop])
	            }  
	          })
		},
		addDriver(context, driver){
			axios.post('/drivers',driver)
	          .then((response) => {
	          	context.commit('setErrors',{})
	            showNotify('success','Driver has been created')
	          })
	          .catch((error) => {
	          	context.commit('setErrors',error.response.data.errors)
	            for (var prop in error.response.data.errors) {
	              showNotify('danger',error.response.data.errors[prop])
	            }       
	          })
		},
		assignOrder(context, assign){
			// return Promise.reject(new Error('error from action "Test"!'))
			axios.post('/assignOrder',assign)
	          .then((response) => {
	          })
	          .catch((error) => {
	          })
		}
	}
})