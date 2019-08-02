<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header border-0">
          <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
              <li class="nav-item" v-for="status,key in orderStatus">
                <a class="nav-link mb-sm-3 mb-md-0" :class="key=='1' ? 'active' : ''" :id="key" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false" @click="getOrders(key)">{{status}}<span class="status_count">2</span></a>
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
                <th scope="col" class="sort" data-sort="completion">Action</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in orders">
                <td>{{++key}}</td>
                <td>{{item.customer.fname}} {{item.customer.lname}}</td>
                <td>{{item.order_type}}</td>
                <td>{{item.order_date}}</td>
                <td>{{item.pickup_location}}</td>
                <td>{{item.pickup_datetime}}</td>
                <td>
                  <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      <a class="dropdown-item" href="javascript:;" @click="showDetails(key-1)">Details</a>
                      <a class="dropdown-item" href="javascript:;" @click="assign(key-1)"  data-toggle="modal" data-target="#assignOrder" title="Assign Pending Order">Assign</a>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <assign :orderKey="activeOrder" v-if="showAssign"></assign>
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
        activeOrder: '',
        showAssign: false,
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'orders')
      this.$store.commit('changeCurrentMenu', 'ordersMenu')
      // get pending orders on page load
      this.$store.dispatch('getOrders','1')
      this.$store.dispatch('getOrderStatus')
    },
    mounted(){
    },
    methods:{
      getOrders(status){
        this.$store.dispatch('getOrders',status)
      },
      showDetails(key){
        // alert(key);
      },
      assign(key){
        this.activeOrder = key;
        this.showAssign = true;
      },
    },
    computed: {
      orders(){
        return this.$store.getters.orders
      },
      orderStatus(){
        return this.$store.getters.orderStatus
      }
    },
    watch: {
    },
  }

</script>
