<template>
  <div class="vue-component-body">
    <div class="row align-items-center py-4">
      <div class="col text-right">
        <a href="javascript:;" class="btn btn-sm btn-danger" @click="deleteOrders" v-if="pick.orderIds.length">Delete</a>
        <a href="javascript:;" class="btn btn-sm btn-neutral" @click="pick.orders = !pick.orders" v-if="!pick.orders">Select</a>
        <a href="javascript:;" class="btn btn-sm btn-info" @click="pick.orders = !pick.orders" v-if="pick.orders">Cancel</a>
      </div>
    </div>
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header border-0">
            <div class="nav-wrapper">
              <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                <li class="nav-item" v-for="status,key in orderStatus">
                  <a class="nav-link mb-sm-3 mb-md-0" :class="key=='Pending' ? 'active' : ''" :id="key" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false" @click="getOrders(key)">{{key}}<span class="status_count" v-if="ordersCount[key]">{{ordersCount[key]}}</span></a>
                </li>
              </ul>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th></th>
                  <th>S.No.</th>
                  <th>Order ID</th>
                  <th>Customer</th>
                  <th>Order Type</th>
                  <th>Pickup From</th>
                  <th>Pickup Time</th>
                  <th>Picked By</th>
                  <th v-if="active.status!='Pending' && active.status!='Received'">Dropped By</th>
                  <th>Status</th>
                  <th>Ordered</th>
                </tr>
                <tr>
                  <th>
                    <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                      <i class="fas fa-search"></i>
                    </div></th>
                  <th>
                  </th>
                  <th>
                    <input v-model="search.orderID" @change="searchOrder" type="text" placeholder="ID" class="form-control searchRow">
                  </th>
                  <th>
                    <input v-model="search.customer" @change="searchOrder" type="text" placeholder="Customer Name" class="form-control searchRow">
                  </th>
                  <th>
                    <select v-model="search.type" @change="searchOrder" class="form-control searchRow">
                      <option value="1">Normal</option>
                      <option value="2">Urgent</option>
                    </select>
                  </th>
                  <th>
                    <input v-model="search.pick_location" @change="searchOrder" type="text" placeholder="Address" class="form-control searchRow">
                  </th>
                  <th>
                    <input v-model="search.pick_date" @change="searchOrder" type="date" placeholder="Address" class="form-control searchRow">
                  </th>
                  <th>
                    <input v-model="search.pick_driver" @change="searchOrder" type="text" placeholder="Driver Name" class="form-control searchRow">
                  </th>
                  <th v-if="active.status!='Pending' && active.status!='Received'">
                    <input v-model="search.drop_driver" @change="searchOrder" type="text" placeholder="Driver Name" class="form-control searchRow">
                  </th>
                  <th>
                    <select v-if="active.status=='Pending'" v-model="search.orderStatus" @change="searchOrder" class="form-control searchRow">
                      <option value="0">Pending</option>
                      <option value="1">Assigned</option>
                      <option value="2">Invoice Generated</option>
                      <option value="3">Invoice Confirmed</option>
                    </select>
                    <select v-if="active.status=='Received'" v-model="search.orderStatus" @change="searchOrder" class="form-control searchRow">
                      <option value="4">Received</option>
                    </select>
                  </th>
                  <th></th>
                </tr>
              </thead>
              <tbody class="list">
                <tr v-for="(item,index) in showOrders.data" v-bind:class="{ urgent: checkPending(index) }">
                  <td>
                    <div class="dropdown">
                      <a class="btn btn-sm btn-icon-only text-light" href="javascript:;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-ellipsis-v"></i>
                      </a>
                      <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                        
                        <a class="dropdown-item" href="javascript:;" @click="details(item.id)"  data-toggle="modal" data-target="#orderDetails" title="Show Order Details">Details</a>
                        
                        <a class="dropdown-item" href="javascript:;" @click="assign(index,'pickAssign')"  data-toggle="modal" data-target="#assignOrder" title="Assign Pending Order" v-if="item.status == 0">Assign for Pickup</a>

                        <a class="dropdown-item" href="javascript:;" @click="assign(index,'dropAssign')"  data-toggle="modal" data-target="#assignOrder" title="Assign Drop Order" v-if="item.status == 4">Assign for Delivery</a>
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="custom-control custom-checkbox" v-if="pick.orders">
                      <input class="custom-control-input" :id="'check_'+index" type="checkbox" @change="pickMultipleOrders(item.id,$event)">
                      <label class="custom-control-label" :for="'check_'+index"></label>
                    </div>
                    <span v-else>{{index+1}}</span>
                  </td>
                  <td>{{item.id}}</td>
                  <td><span v-if="item.customer">{{item.customer.full_name}}</span></td>
                  <td>{{getOrderType(item.type)}}</td>
                  <td v-if="item.pick_location_details">{{item.pick_location_details.name}}</td>
                  <td>{{item.pick_date}}</td>
                  <td>
                    <span v-if="item.status === 0">Not Assigned</span>
                    <span v-if="item.status !== 0 && item.pick_driver">{{item.pick_driver.full_name}}</span>
                  </td>
                  <td v-if="active.status!='Pending' && active.status!='Received'">
                    <span v-if="item.status < 5">Not Assigned</span>
                    <span v-if="item.status >= 5 && item.drop_driver">{{item.drop_driver.full_name}}</span>
                  </td>
                  <td>
                    <span>{{ getStatus(item.status) }}</span>
                  </td>
                  <td>{{ dateDiff(item.created_at)}}</td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="card-footer py-4">
            <pagination :data="showOrders" @pagination-change-page="getResults"></pagination>
          </div>
        </div>
      </div>
      <assign :active="active" v-if="showAssign" ref="assign"></assign>
    </div>
  </div>
</template>

<script>
  import assign from './assign.vue'
  import {settings} from '../../config/settings'
  import { mapState } from 'vuex'
  import DatePicker from 'vue2-datepicker'
 
  export default{
    components: {
      assign, DatePicker
    },
    data(){
      return{
        search:{},
        active:{
          order:'',
          page:1,
          order_id:'',
          status:'Pending',
        },
        showAssign: true,
        showDetails: true,
        pick:{
          orders: false,
          orderIds:[],
        },
        errors:{},
        message:'',
        showOrders:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'orders')
      this.$store.commit('changeCurrentMenu', 'ordersMenu')
      // get pending orders on page load
      this.getOrders('Pending')
      this.$store.dispatch('getOrderStatus')
    },
    mounted(){
      
    },
    methods:{
      getOrders(status){
        this.active.status = status
        this.getResults()
        this.$store.dispatch('getOrdersCount')
      },
      getResults(page = 1) {
        this.active.page = page
        this.$store.dispatch('getOrders',this.active)
      },
      getStatus(status) {
        return settings.orderStatuses[status]
      },
      getOrderType(type) {
        return settings.orderType[type]
      },
      details(id){
        this.$router.push({ name: 'orderDetails', query:{ orderID:id } })
      },
      assign(index,type){
        this.active.order = index
        this.active.type = type
        this.showAssign = true
        this.$refs.assign.mount()
      },      
      dateDiff(date){
        var date = new Date(date+' UTC')
        return this.$moment(date).fromNow() // a
      },
      checkPending(index){
        const date = this.orders.data[index].created_at
        const createdAt = this.$moment(new Date(date+' UTC'))
        const currentTime = this.$moment(Date.now()); 
        const passed_minute = Math.abs(createdAt.diff(currentTime, 'minutes'))
        if(this.orders.data[index].status==0 && passed_minute>=10)
          return true
        else
          return false
      },
      searchOrder(){
        axios.post('/orders/search/'+this.active.status,this.search)
        .then((response) => {
          this.$store.commit('setOrders',response.data)
        })
      },
      triggerPickOrders(){
        this.pick.orders = !this.pick.orders
      },
      pickMultipleOrders(id,event){
        if(event.target.checked){
          if(!this.pick.orderIds.includes(id))
            this.pick.orderIds.push(id)
        }
        else if(!event.target.checked){
          if(this.pick.orderIds.includes(id)){
            var index = this.pick.orderIds.indexOf(id)
            this.pick.orderIds.splice(index, 1)
          }
        }
      },
      deleteOrders(){
        this.$swal({
          title: 'Are you sure?',
          text: "Orders will be permanently deleted",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        })
        .then((result) => {
          if (result.value) {
            axios.post('/deleteMultipleOrders',this.pick)
            .then((response) => {
              this.getOrders('Pending')
              showNotify('success',response.data.message)
            })
            .catch((error) => {
              showNotify('danger',error.response.data.message)
            })
          }
        })
      }
    },
    computed: {
      pending(){
        return this.orderStatus['Pending'];
      },
      received(){
        return this.orderStatus['Received'];
      },
      ready_for_delivery(){
        return this.orderStatus['Ready for Delivery'];
      },
      on_hold(){
        return this.orderStatus['On Hold'];
      },
      completed(){
        return this.orderStatus['Completed'];
      },
      ...mapState(['orders', 'orderStatus' ,'ordersCount'])
    },
    watch: {
      orders(value) {
        this.showOrders = value
      }
    }
  }

</script>

<style type="text/css" scoped>
  .urgent{
    background: #ffd7d47d;
    color: #853737;
  }
</style>