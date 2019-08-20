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
		services:{},
		categories:{},
		items:{},
		orderStatus:{},
		notifications:{},
		appDefaults:{},
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
		services(state){
			return state.services;
		},
		categories(state){
			return state.categories;
		},
		items(state){
			return state.items;
		},
		notifications(state){
			return state.notifications;
		},
		appDefaults(state){
			return state.appDefaults;
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
		setServices(state, services){
			state.services = services
		},
		setCategories(state, categories){
			state.categories = categories
		},
		setItems(state, items){
			state.items = items
		},
		setNotifications(state, notifications){
			state.notifications = notifications
		},
		setAppDefaults(state, appDefaults){
			state.appDefaults = appDefaults
		},
		setErrors(state, errors){
			state.errors = errors
		}
	},
	actions:{
		getAppDefaults(context){
			axios.get('/appDefaults')
	        .then(response => {
	        	context.commit('setAppDefaults',response.data)
	        });
		},
		updateAppDefaults(context, appDefaults){
			axios.post('/appDefaults',appDefaults)
	          .then((response) => {
	          	console.log(response)
	          	context.commit('setErrors',{})
	            showNotify('success','App Default has been created')
	          })
	          .catch((error) => {
      			context.commit('setErrors',error.response.data.errors)
	            for (var prop in error.response.data.errors) {
	              showNotify('danger',error.response.data.errors[prop])
	            }  
	          })
		},
		getNotifications(context){
			axios.get('/notifications')
	        .then(response => {
	        	context.commit('setNotifications',response.data)
	        });
		},
		setAllNotificationsRead(context){
			axios.get('/markAllAsRead')
	        .then(response => {
	        	context.commit('setNotifications',[])
	        	showNotify('success','All Notifications Cleared')
	        });
		},
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
		getDrivers(context){
			axios.get('/drivers')
	        .then(response => {
	        	context.commit('setDrivers',response.data)
	        });
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
		getServices(context){
			axios.get('/services')
		        .then(response => {
		          context.commit('setServices',response.data)
		        });	
		},
		addService(context, service){
			axios.post('/services',service)
	          .then((response) => {
	          	context.commit('setErrors',{})
	            showNotify('success',response.data)
	          })
	          .catch((error) => {
      			context.commit('setErrors',error.response.data.errors)
	            for (var prop in error.response.data.errors) {
	              showNotify('danger',error.response.data.errors[prop])
	            }  
	          })
		},
		getCategories(context){
			axios.get('/categories')
		        .then(response => {
		          context.commit('setCategories',response.data)
		        });	
		},
		addCategory(context, category){
			axios.post('/categories',category)
	          .then((response) => {
	          	context.commit('setErrors',{})
	            showNotify('success',response.data)
	          })
	          .catch((error) => {
      			context.commit('setErrors',error.response.data.errors)
	            for (var prop in error.response.data.errors) {
	              showNotify('danger',error.response.data.errors[prop])
	            }  
	          })
		},
		getItems(context){
			axios.get('/items')
		        .then(response => {
		          context.commit('setItems',response.data)
		        });	
		},
		addItem(context, item){
			axios.post('/items',item)
	          .then((response) => {
	          	console.log(response)
	          	context.commit('setErrors',{})
	            showNotify('success',response.data)
	          })
	          .catch((error) => {
      			context.commit('setErrors',error.response.data.errors)
	            for (var prop in error.response.data.errors) {
	              showNotify('danger',error.response.data.errors[prop])
	            }  
	          })
		},
		getCustomers(context){
			axios.get('/customers')
		        .then(response => {
		          context.commit('setCustomers',response.data)
		        });	
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