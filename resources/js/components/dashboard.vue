<template>
  <div class="vue-component-body">
    <div class="row">
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Total Pending</h5>
                <span class="h2 font-weight-bold mb-0">350,897</span>
              </div>
              <div class="col-auto">
                <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                  <i class="ni ni-active-40"></i>
                </div>
              </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
              <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
              <span class="text-nowrap">Since last month</span>
            </p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">On Work</h5>
                <span class="h2 font-weight-bold mb-0">2,356</span>
              </div>
              <div class="col-auto">
                <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                  <i class="ni ni-chart-pie-35"></i>
                </div>
              </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
              <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
              <span class="text-nowrap">Since last month</span>
            </p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Ready To Deliver</h5>
                <span class="h2 font-weight-bold mb-0">924</span>
              </div>
              <div class="col-auto">
                <div class="icon icon-shape bg-gradient-green text-white rounded-circle shadow">
                  <i class="ni ni-money-coins"></i>
                </div>
              </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
              <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
              <span class="text-nowrap">Since last month</span>
            </p>
          </div>
        </div>
      </div>
      <div class="col-xl-3 col-md-6">
        <div class="card card-stats">
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col">
                <h5 class="card-title text-uppercase text-muted mb-0">Delivered</h5>
                <span class="h2 font-weight-bold mb-0">49,65%</span>
              </div>
              <div class="col-auto">
                <div class="icon icon-shape bg-gradient-info text-white rounded-circle shadow">
                  <i class="ni ni-chart-bar-32"></i>
                </div>
              </div>
            </div>
            <p class="mt-3 mb-0 text-sm">
              <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
              <span class="text-nowrap">Since last month</span>
            </p>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-xl-3">
        <div class="card">
          <div class="card-body">
            <DoughnutChart chartFor="pendingOrders"></DoughnutChart>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
        <div class="card">
          <div class="card-body">
            <DoughnutChart chartFor="receivedOrders"></DoughnutChart>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
        <div class="card">
          <div class="card-body">
            <DoughnutChart chartFor="readyForDeliveryOrders"></DoughnutChart>
          </div>
        </div>
      </div>
      <div class="col-xl-3">
        <div class="card">
          <div class="card-body">
            <DoughnutChart chartFor="onHoldOrders"></DoughnutChart>
          </div>
        </div>
      </div>
<!--       <div class="col-xl-12">
        <div class="card">
          <div class="card-header">
            <h6 class="surtitle">Order Rate</h6>
          </div>
          <div class="card-body">
            <LineChart chartFor="pendingOrders"></LineChart>
          </div>
        </div>
      </div> -->
      <div class="col-xl-8">
        <div class="card bg-default">
          <div class="card-header bg-transparent">
            <div class="row align-items-center">
              <div class="col">
                <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
                <h5 class="h3 text-white mb-0">Order Graph</h5>
              </div>
            </div>
          </div>
          <div class="card-body">
            <div class="chart">
              <LineChart chartFor="pendingOrders"></LineChart>
            </div>
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