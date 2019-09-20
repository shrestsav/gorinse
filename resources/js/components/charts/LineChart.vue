<template>
  <div class="card bg-default">
    <div class="card-header bg-transparent">
      <div class="row align-items-center">
        <div class="col-4">
          <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
          <h5 class="h3 text-white mb-0">Order Graph</h5>
        </div>
        <div class="col-8">
          <div class="row">
            <div class="col-2">
              <h6 class="text-light text-uppercase ls-1 mb-1">View</h6>
              <select class="form-control bg-transparent" v-model="reports.type">
                <option value="monthly">Month</option>
                <option value="yearly">Year</option>
              </select>
            </div>
            <div class="col-3" v-if="reports.type=='monthly'">
              <h6 class="text-light text-uppercase ls-1 mb-1">Select Month</h6>
              <date-picker @change="getReport" input-class="form-control bg-transparent" v-model="reports.year_month" lang="en" type="month" format="YYYY-MM" valueType="format" ></date-picker>
            </div>
            <div class="col-3" v-if="reports.type=='yearly'">
              <h6 class="text-light text-uppercase ls-1 mb-1">Select Year</h6>
              <date-picker @change="getReport" input-class="form-control bg-transparent" v-model="reports.year" lang="en" type="year" format="YYYY" valueType="format" ></date-picker>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div class="chart">
        <line-chart :chart-data="datacollection" ></line-chart>
      </div>
    </div>
  </div> 
</template>

<script>
  import LineChart from './LineChart.js'
  import DatePicker from 'vue2-datepicker'

  export default {
    components: {
      LineChart, DatePicker
    },
    data () {
      return {
        datacollection: {},
        reports:{
          type:'',
          year:'',
          year_month:'',
        }
      }
    },
    mounted () {
      this.init()
    },
    methods: {
      init(){
        var today = new Date()
        this.reports.type = 'monthly'
        this.reports.year_month = today.getFullYear()+'-'+(Number(today.getMonth())+1)
        this.getReport()
      },
      fillData(data) {
       console.log(data) 
        this.datacollection = {
          labels: data.labels,
          datasets: [
            {
              fill: false,
              borderColor: '#f87979',
              data: data.data
            }
          ]
        }
      },
      getReport() {
        axios.post('/reports/totalOrders',this.reports)
        .then((response) => {
          this.fillData(response.data)
        })
        .catch((error) => {
          showNotify('danger',error.response.data.message)
        })
      }
    }
  }
</script>

<style scoped>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
  .mx-input-append .mx-calendar-icon {
    color: #fff !important;
  }
</style>