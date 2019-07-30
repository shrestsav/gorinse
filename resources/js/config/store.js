import Vue from 'vue'
import Vuex from 'vuex'
import db from './firestore'

Vue.use(Vuex)

export const store = new Vuex.Store({
	state:{
		currentPage:'',
		currentMenu:'',
		orders:[],
	},
	getters:{
		newOrders(state){
			return state.orders;
		}
	},
	mutations:{ 
		changeCurrentPage(state, currentPage) {
			state.currentPage = currentPage
		},
		changeCurrentMenu(state, currentMenu) {
			state.currentMenu = currentMenu
		},
		getOrders(state, orders){
			state.orders = orders
		}
	},
	actions:{
		getOrders(context){

			db.collection('orders').onSnapshot(snap => {
				let orders = [];
				snap.forEach(doc => {
					orders.push({
						id: doc.id,
						customer_id: doc.data().customer_id,
						normal_urgent: doc.data().normal_urgent,
						order_date: doc.data().order_date,
						order_id: doc.data().order_id,
						pickup_place: doc.data().pickup_place
					})
				});
				console.log(orders);
				context.commit('getOrders',orders)
			})


			// db.collection('orders').get()
			// .then(querySnapshot => {
			// 	let orders = [];
			// 	querySnapshot.forEach(doc => {
			// 		const data = {
			// 			id: doc.id,
			// 			customer_id: doc.data().customer_id,
			// 			normal_urgent: doc.data().normal_urgent,
			// 			order_date: doc.data().order_date,
			// 			order_id: doc.data().order_id,
			// 			pickup_place: doc.data().pickup_place
			// 		}
			// 		orders.push(data)
			// 	})
			// 	console.log(orders);
			// 	context.commit('getOrders',orders)
			// })
			
		}
	}
})