<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header border-0">
          <h3 class="mb-0">New Orders</h3>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col" class="sort" data-sort="name">Customer ID</th>
                <th scope="col" class="sort" data-sort="budget">Order Type</th>
                <th scope="col" class="sort" data-sort="status">Date</th>
                <th scope="col">Order ID</th>
                <th scope="col" class="sort" data-sort="completion">Pickup From</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in orders">
                <td>{{item.id}}</td>
                <td>{{item.normal_urgent}}</td>
                <td>{{item.order_date}}</td>
                <td>{{item.order_id}}</td>
                <td>{{item.pickup_place}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default{
    data(){
      return{
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'orders')
      this.$store.commit('changeCurrentMenu', 'ordersMenu')
      this.$store.dispatch('getOrders')
    },
    mounted(){
    },
    methods:{
    },
    computed: {
      orders(){
        return this.$store.getters.newOrders
      }
    },
    watch: {
    },
  }

</script>