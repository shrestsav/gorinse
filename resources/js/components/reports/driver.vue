<template>
  <div class="card bg-default">
    <div class="card-header bg-transparent">
      <div class="row align-items-center">
        <div class="col-4">
          <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
          <h5 class="h3 text-white mb-0">Driver Order Report</h5>
        </div>
        <div class="col-8">
          <div class="row">
            <div class="col-3">
              <h6 class="text-light text-uppercase ls-1 mb-1">Select Driver</h6>
              <v-select
                class="form-control"   
                v-model="reports.driver" 
                :options="drivers"
                :reduce="data => data.id"
                label="full_name" 
                placeholder="Drivers"
              />
            </div>
            <div class="col-3" v-if="reports.driver">
              <h6 class="text-light text-uppercase ls-1 mb-1">View</h6>
              <select class="form-control bg-transparent" v-model="reports.orderType">
                <option value="all">All</option>
                <option value="picked">Picked Report</option>
                <option value="dropped">Dropped Report</option>
              </select>
            </div>
            <div class="col-3">
              <h6 class="text-light text-uppercase ls-1 mb-1"> &nbsp; </h6>
              <button type="button" class="btn btn-success" @click="getReport">View</button>
            </div>
            <!-- <div class="col-3" v-if="reports.type=='monthly'">
              <h6 class="text-light text-uppercase ls-1 mb-1">Select Month</h6>
              <date-picker @change="getReport" input-class="form-control bg-transparent" v-model="reports.year_month" lang="en" type="month" format="YYYY-MM" valueType="format" ></date-picker>
            </div>
            <div class="col-3" v-if="reports.type=='yearly'">
              <h6 class="text-light text-uppercase ls-1 mb-1">Select Year</h6>
              <date-picker @change="getReport" input-class="form-control bg-transparent" v-model="reports.year" lang="en" type="year" format="YYYY" valueType="format" ></date-picker>
            </div> -->
          </div>
        </div>
        <!-- <div class="col-3 text-right"> -->
          <!-- <div class="row">
            <div class="col-8 text-right">
              <h6 class="text-light text-uppercase ls-1 mb-1">Total Amount</h6>
              <h5 class="h3 text-white mb-0">AED {{grandTotal}}</h5>
            </div>
            <div class="col-4 text-right">
              <a :href="'http://go.rinse/reports/export?report=deliveredTimewise&type='+reports.type+'&year_month='+reports.year_month+'&year='+reports.year" target="_blank"><button type="button" class="btn btn-success btn-sm">Export <i class="fas fa-file-excel"></i></button></a>
            </div>
          </div> -->
        <!-- </div> -->
      </div>
    </div>
    <div class="card-body">
      <div class="chart">
        <canvas id="totalOrdersReport"></canvas>
      </div>
    </div>
  </div> 
</template>

<script>

  import DatePicker from 'vue2-datepicker'
  import vSelect from 'vue-select'
  import 'vue-select/dist/vue-select.css'

  export default {
    components: {
      DatePicker, vSelect
    },
    data () {
      return {
        reports:{
          driver     : null,
          orderType  : null,
          year       : null,
          year_month : null
        },
        drivers:[]
      }
    },
    mounted () {
      this.init()
    },
    methods: {
      init(){
        var today = new Date()
        this.reports.type = 'monthly'
        this.reports.year_month = today.getFullYear() + '-' + (Number(today.getMonth()) + 1)
        this.getDrivers()
      },
      getDrivers(){
        axios.get('/driver/all').then((response) => this.drivers = response.data)
      },
      getReport() {
        axios.post('/reports/driverOrders',this.reports)
        .then((response) => {
          console.log(response.data)
        })
        .catch((error) => {
          showNotify('danger',error.response.data.message)
        })
      },
    }
  }
</script>