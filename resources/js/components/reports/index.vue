<template>
  <div class="vue-component-body">
    <div class="card">
      <div class="card-body">
        <div class="col-3">
          <h6 class="text-light text-uppercase ls-1 mb-1">SELECT REPORT TYPE</h6>
          <select class="form-control bg-transparent" v-model="report.active">
            <option value="total_sales_report">Total Sales Report</option>
            <option value="driver_report">Driver Report</option>
          </select>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-12">
        <totalSales v-if="report.active=='total_sales_report'"></totalSales>
        <driver v-if="report.active=='driver_report'"></driver>
      </div>
    </div>
  </div>
</template>


<script>
  import { mapState } from 'vuex'
  import totalSales from '../charts/totalSales.vue'
  import driver from './driver.vue'
  export default{
    components:{
      totalSales, driver
    },
    data(){
      return{
        report:{
          active:'driver_report'
        },
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'reports')
      this.$store.commit('changeCurrentMenu', 'reportsMenu')
    },
    mounted(){
      this.$store.dispatch('getOrdersCount')
    },
    methods:{
      goto(){
        this.$router.push({ name: 'orders'})
      }
    },
    computed: {
      ...mapState(['ordersCount'])
    },
    watch: {
    },
  }

</script>