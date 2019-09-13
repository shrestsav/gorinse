<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header border-0">
          <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
              <li class="nav-item" v-for="status,key in orderStatus">
                <a class="nav-link mb-sm-3 mb-md-0" :class="key=='Pending' ? 'active' : ''" :id="key" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false" @click="getOrders(key)">{{key}}<span class="status_count">{{count[key]}}</span></a>
              </li>
            </ul>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th>S.No.</th>
                <th>Customer</th>
                <th>Order Type</th>
                <th>Pickup From</th>
                <th>Pickup Time</th>
                <th>Picked By</th>
                <th>Dropped By</th>
                <th>Status</th>
                <th>Ordered</th>
                <th>Action</th>
              </tr>
              <tr>
                <th>
                  <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                    <i class="fas fa-search"></i>
                  </div>
                </th>
                <th>
                  <input v-model="search.customer" @change="searchOrder" type="text" placeholder="Customer Name" class="form-control">
                </th>
                <th>
                  <select v-model="search.type" @change="searchOrder" class="form-control">
                    <option value="1">Normal</option>
                    <option value="2">Urgent</option>
                  </select>
                </th>
                <th>
                  <input v-model="search.pick_location" @change="searchOrder" type="text" placeholder="Address" class="form-control">
                </th>
                <th>
                  <input v-model="search.pick_date" @change="searchOrder" type="date" placeholder="Address" class="form-control">
                </th>
                <th>
                  <input v-model="search.pick_driver" @change="searchOrder" type="text" placeholder="Driver Name" class="form-control">
                </th>
                <th>
                  <input v-model="search.drop_driver" @change="searchOrder" type="text" placeholder="Driver Name" class="form-control">
                </th>
                <th>
                  <select v-if="active.status=='Pending'" v-model="search.orderStatus" @change="searchOrder" class="form-control">
                    <option value="0">Pending</option>
                    <option value="1">Assigned</option>
                    <option value="2">Invoice Generated</option>
                    <option value="3">Invoice Confirmed</option>
                  </select>
                  <select v-if="active.status=='Received'" v-model="search.orderStatus" @change="searchOrder" class="form-control">
                    <option value="4">Received</option>
                  </select>
                </th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="(item,index) in showOrders.data" v-bind:class="{ urgent: checkPending(index) }">
                <td>{{index+1}}</td>
                <td><span v-if="item.customer">{{item.customer.full_name}}</span></td>
                <td>{{getOrderType(item.type)}}</td>
                <td v-if="item.pick_location_details">{{item.pick_location_details.name}}</td>
                <td>{{item.pick_date}}</td>
                <td>
                  <span v-if="item.status === 0">Not Assigned</span>
                  <span v-if="item.status !== 0 && item.pick_driver">{{item.pick_driver.full_name}}</span>
                </td>
                <td>
                  <span v-if="item.status < 5">Not Assigned</span>
                  <span v-if="item.status >= 5 && item.drop_driver">{{item.drop_driver.full_name}}</span>
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
                      
                      <a class="dropdown-item" href="javascript:;" @click="assign(index,'pickAssign')"  data-toggle="modal" data-target="#assignOrder" title="Assign Pending Order" v-if="item.status == 0">Assign for Pickup</a>

                      <a class="dropdown-item" href="javascript:;" @click="assign(index,'dropAssign')"  data-toggle="modal" data-target="#assignOrder" title="Assign Drop Order" v-if="item.status == 4">Assign for Delivery</a>
                    </div>
                  </div>
                </td>
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
        search:{

        },
        active:{
          order:'',
          page:1,
          order_id:'',
          status:'Pending',
        },
        count:{
          pendingOrders:0,
          receivedOrders:0,
          readyForDeliveryOrders:0,
          onHoldOrders:0,
          completedOrders:0
        },
        showAssign: true,
        showDetails: true,
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
      getOrdersCount(){
        axios.get('/getOrdersCount')
        .then(response => {
          this.count = response.data
        });
      },
      getOrders(status){
        this.active.status = status
        this.getResults()
        this.getOrdersCount()
      },
      getResults(page = 1) {
        this.active.page = page,
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
        this.active.order = index;
        this.active.type = type;
        this.showAssign = true;
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
          console.log(response.data)
          this.$store.commit('setOrders',response.data)
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
      ...mapState(['orders', 'orderStatus'])
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