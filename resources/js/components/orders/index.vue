<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header border-0">
          <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
              <li class="nav-item" v-for="status,key in orderStatus">
                <a class="nav-link mb-sm-3 mb-md-0" :class="key=='Pending' ? 'active' : ''" :id="key" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false" @click="getOrders(key)">{{key}}<span class="status_count">2</span></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col" class="sort">S.No.</th>
                <th scope="col" class="sort">Customer</th>
                <th scope="col" class="sort">Order Type</th>
                <th scope="col" class="sort">Pickup From</th>
                <th scope="col" class="sort">Pickup Time</th>
                <th scope="col" class="sort">Assigned to</th>
                <th scope="col" class="sort">Status</th>
                <th scope="col" class="sort">Ordered</th>
                <th scope="col" class="sort">Action</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="(item,index) in orders.data" v-bind:class="{ urgent: checkPending(index) }">
                <td>{{index+1}}</td>
                <td><span v-if="item.customer">{{item.customer.fname}}</span></td>
                <td>{{item.type}}</td>
                <td>{{item.pick_location_details.name}}</td>
                <td>{{item.pick_date}}</td>
                <td>
                  <span v-if="item.status === 0">Not Assigned</span>
                  <span v-if="item.status !== 0 && item.driver">{{item.driver.fname}} {{item.driver.lname}}</span>
                </td>
                <td>
                  <span>{{ getStatus(item.status) }}</span>
                </td>
                <td>{{ dateDiff(item.created_at)}}</td>
                <td>
                  <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      <a class="dropdown-item" href="javascript:;" @click="details(item.id)"  data-toggle="modal" data-target="#orderDetails" title="Show Order Details">Details</a>
                      <a class="dropdown-item" href="javascript:;" @click="assign(index)"  data-toggle="modal" data-target="#assignOrder" title="Assign Pending Order" v-if="item.status === 0">Assign</a>
                      <a class="dropdown-item" href="javascript:;" @click="assign(index)"  title="Show Invoice" v-if="item.status === 2">Show Invoice</a>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer py-4">
          <pagination :data="orders" @pagination-change-page="getResults"></pagination>
        </div>
      </div>
    </div>
    <assign :active="active" v-if="showAssign"></assign>
    <show :active="active" v-if="showDetails"></show>
  </div>
</template>

<script>
  import assign from './assign.vue'
  import show from './show.vue'
  import {settings} from '../../config/settings'
 
  export default{
    components: {
      assign,show
    },
    data(){
      return{
        active:{
          page:1,
          order_id:'',
          status:'Pending',
        },
        showAssign: false,
        showDetails: false,
        errors:{},
        message:'',
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
      },
      getResults(page = 1) {
        this.active.page = page,
        this.$store.dispatch('getOrders',this.active)
      },
      getStatus(status) {
        return settings.orderStatuses[status]
      },
      details(id){
        this.active.order_id = id;
        this.showDetails = true;
      },
      assign(index){
        this.active.order = index;
        this.showAssign = true;
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
        console.log(passed_minute)
        if(this.orders.data[index].status==0 && passed_minute>=10){
          return true
        }
        else
          return false
      },
    },
    computed: {
      orders(){
        return this.$store.getters.orders
      },
      orderStatus(){
        return this.$store.getters.orderStatus
      },
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
      }
    },
  }

</script>

<style type="text/css" scoped>
  .urgent{
    background: #ffd7d47d;
    color: #853737;
  }
</style>