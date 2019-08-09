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
			axios.get('/getDrivers')
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
	          	console.log(response)
	            // this.$router.push({ name: 'staffs' });
	          })
	          .catch((error) => {
	          	console.log(error.response.data.errors)
	            // this.errors = error.response.data.errors;
	            // for (var prop in this.errors) {
	            //   showNotify('danger',this.errors[prop])
	            // }       
	          })
		},
		addDriver(context, driver){
			db.collection("drivers").add(driver)
			.then(function(docRef) {
			    console.log("Document written with ID: ", docRef.id);
			})
			.catch(function(error) {
			    console.error("Error adding document: ", error);
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