import Vue from 'vue'
import Vuex from 'vuex'
import db from './firestore'

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
		getOrders(context,status){
			// db.collection('orders').onSnapshot(snap => {
			// 	let orders = [];
			// 	snap.forEach(doc => {
			// 		var customers = db.collection("users").doc(doc.data().customer_id);
			// 		customers.get().then(function(userDoc) {
			// 		    if (userDoc.exists) {
			// 		        console.log("Document data:", userDoc.data());
			// 		        orders.push({
			// 					id: doc.id,
			// 					customer_id: doc.data().customer_id,
			// 					customer_name: userDoc.data().fname,
			// 					order_type: doc.data().order_type,
			// 					order_date: doc.data().order_date,
			// 					pickup_location: doc.data().pickup_location,
			// 					pickup_datetime: doc.data().pickup_datetime,
			// 					drop_location: doc.data().drop_location,
			// 					drop_datetime: doc.data().drop_datetime,
			// 				})
			// 		    } else {
			// 		        // doc.data() will be undefined in this case
			// 		        console.log("No such document!");
			// 		    }
			// 		}).catch(function(error) {
			// 		    console.log("Error getting document:", error);
			// 		});

					
			// 	});
			// 	// console.log(orders);
			// 	context.commit('setOrders',orders)
			// })
			// console.log(db.collection('orders').where("status", "==", 'pending').get());
			var orderRef = db.collection('orders');
			orderRef.where("status", "==", status).get()
			.then(querySnapshot => {
				let orders = [];
				querySnapshot.forEach(doc => {
			        orders.push({
						id: doc.id,
						customer: doc.data().customer,
						order_type: doc.data().order_type,
						order_date: doc.data().order_date.toDate(),
						pickup_location: doc.data().pickup_location,
						pickup_datetime: doc.data().pickup_datetime.toDate(),
						drop_location: doc.data().drop_location,
						drop_datetime: doc.data().drop_datetime.toDate(),
					}) 
				})
				// console.log(orders);
				context.commit('setOrders',orders)
			})
			
		},
		getDrivers(context){
			db.collection('drivers').onSnapshot(snap => {
				let drivers = [];
				snap.forEach(doc => {
					var datee = new Date(doc.data().d_o_b.seconds *1000);
			        drivers.push({
						id: doc.id,
						username: doc.data().username,
						fname: doc.data().fname,
						lname: doc.data().lname,
						email: doc.data().email,
						d_o_b: doc.data().d_o_b.toDate(),
						address: doc.data().address,
						contact: doc.data().contact,
					})
				});
				context.commit('setDrivers',drivers)
			})

			
		},
		getCustomers(context){
			db.collection('users').onSnapshot(snap => {
				let customers = [];
				snap.forEach(doc => {
					// console.log(doc);
					customers.push({
						id: doc.id,
						fname: doc.data().fname,
						lname: doc.data().lname
					})
				});
				console.log(customers);
				context.commit('setCustomers',customers)
			})	
		},
		addOrder(context, order){
			db.collection("orders").add(order)
			.then(function(docRef) {
			    console.log("Document written with ID: ", docRef.id);
			})
			.catch(function(error) {
			    console.error("Error adding document: ", error);
			});
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
			var orderRef = db.collection("orders").doc(assign.order_id);

			// Set the "capital" field of the city 'DC'
			orderRef.update({
				status:'2',
			    driver: assign.driver
			})
			.then(function() {
			    console.log("Document successfully updated!");
			})
			.catch(function(error) {
			    // The document probably doesn't exist.
			    console.error("Error updating document: ", error);
			});
		}
	}
})