<template>
  <div class="row">
    <div class="col-xl-12">
      <div class="card">
        <div class="card-header">
          <h6 class="surtitle">Orders</h6>
          <!-- <h5 class="surtitle">Status</h5> -->
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-xl-3">
              <DoughnutChart chartFor="pendingOrders"></DoughnutChart>
            </div>
            <div class="col-xl-3">
              <DoughnutChart chartFor="receivedOrders"></DoughnutChart>
            </div>
            <div class="col-xl-3">
              <DoughnutChart chartFor="readyForDeliveryOrders"></DoughnutChart>
            </div>
            <div class="col-xl-3">
              <DoughnutChart chartFor="onHoldOrders"></DoughnutChart>
            </div>
          <!--   <div class="col-xl-4">
              <DoughnutChart chartFor="completedOrders"></DoughnutChart>
            </div> -->
          </div>
          <div>
            <LineChart></LineChart>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>


<script>
  import DoughnutChart from './charts/doughnut.vue'
  import LineChart from './charts/LineChart.vue'
  export default{
    components:{
      DoughnutChart, LineChart
    },
    data(){
      return{
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'dashboard')
      this.$store.commit('changeCurrentMenu', 'dashboardMenu')
    },
    mounted(){

    },
    methods:{
    },
    computed: {
    },
    watch: {
    },
  }

</script>

<style>
  .mx-datepicker{
    width: unset;
    display: unset;
  }
  .mx-datepicker-popup{
    top: 0 !important;
  }
  .not-validated{
    border-color: #fb6340;
  }
  .form-control .vs__dropdown-toggle {
    border: 0px !important;
  }
  .table td, .table th {
    padding: 0.8rem;
  }
  .nav-pills .nav-item:not(:last-child) {
    padding-right: unset;
  }
  .nav-pills .nav-link {
    border-radius: unset;
  }
  .nav-wrapper{
    padding: unset;
  }
  .status_count{
    padding: 2px 7px;
    border-radius: 17px;
    background: #F44336;
    position: relative;
    left: 25px;
    color: white;
  }
  .notification_count{
    padding: 1px 7px 6px 7px;
    border-radius: 17px;
    background: #7d8a92;
    position: relative;
    top: -7px;
  }
  .modal-fullscreen{
    max-width: unset;
    width: 90%;
  }
  body{
    zoom:90%;
  }
  .modal-backdrop {
      width: 100%;
      height: 100%;
  }
  .banner_images{
    width: 274px; 
    overflow: hidden;
  }

</style>