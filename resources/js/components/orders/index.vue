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
                <th scope="col" class="sort" data-sort="name">S.No.</th>
                <th scope="col" class="sort" data-sort="name">Customer</th>
                <th scope="col" class="sort" data-sort="budget">Order Type</th>
                <th scope="col" class="sort" data-sort="status">Date</th>
                <th scope="col" class="sort" data-sort="completion">Pickup From</th>
                <th scope="col" class="sort" data-sort="completion">Pickup Time</th>
                <th scope="col" class="sort" data-sort="completion">Assigned to</th>
                <th scope="col" class="sort" data-sort="completion">Action</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="(item,index) in orders.data">
                <td>{{index+1}}</td>
                <td>{{item.customer.name}}</td>
                <td>{{item.type}}</td>
                <td>{{item.order_date}}</td>
                <td>{{item.pick_location}}</td>
                <td>{{item.pick_datetime}}</td>
                <td>
                  <span v-if="item.status === 0">Pending</span>
                  <span v-if="item.status !== 0">{{item.driver.name}}</span>
                </td>
                <td>
                  <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      <a class="dropdown-item" href="javascript:;" @click="showDetails(index)">Details</a>
                      <a class="dropdown-item" href="javascript:;" @click="assign(index)"  data-toggle="modal" data-target="#assignOrder" title="Assign Pending Order">Assign</a>
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
  </div>
</template>

<script>
  import assign from './assign.vue'
 
  export default{
    components: {
      assign
    },
    data(){
      return{
        active:{
          page:1,
          order:'',
          status:'Pending',
        },
        showAssign: false,
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
      showDetails(key){
        // alert(key);
      },
      assign(id){
        this.active.order = id;
        this.showAssign = true;
      },
    },
    computed: {
      orders: function (){
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
    watch: {
    },
  }

</script>
