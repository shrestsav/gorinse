<template>
  <div class="card bg-default totalSales">
    <div class="card-header bg-transparent">
      <div class="row align-items-center">
        <div class="col-4">
          <h6 class="text-light text-uppercase ls-1 mb-1">Overview</h6>
          <h5 class="h3 text-white mb-0">Total Sales Report</h5>
        </div>
        <div class="col-5">
          <div class="row">
            <div class="col-3">
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
        <div class="col-3 text-right">
          <div class="row">
            <div class="col-8 text-right">
              <h6 class="text-light text-uppercase ls-1 mb-1">Total Amount</h6>
              <h5 class="h3 text-white mb-0">AED {{grandTotal}}</h5>
            </div>
            <div class="col-4 text-right">
              <a :href="'http://go.rinse/reports/export?report=deliveredTimewise&type='+reports.type+'&year_month='+reports.year_month+'&year='+reports.year" target="_blank"><button type="button" class="btn btn-success btn-sm">Export <i class="fas fa-file-excel"></i></button></a>
            </div>
          </div>
        </div>
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

  import Chart from 'chart.js'
  import DatePicker from 'vue2-datepicker'

  export default {
    components: {
      DatePicker
    },
    data () {
      return {
        chartData: {
          type: 'line',
          data: {},
          options: {
            responsive: true,
            maintainAspectRatio: false,
            lineTension: 1,
            scales: {
              yAxes: [{
                ticks: {
                  beginAtZero: true,
                  padding: 25,
                }
              }]
            },
            legend: {
              display: false
            },
          },
        },
        reports:{
          type:'',
          year:'',
          year_month:'',
        },
        grandTotal:0
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
      createChart(data) {
        this.chartData.data = {
          labels: data.labels,
          datasets: [
            {
              fill: false,
              borderColor: '#f87979',
              data: data.data,
              label:'AED '
            }
          ]
        }
        const ctx = document.getElementById('totalOrdersReport');
        const myChart = new Chart(ctx, {
          type: this.chartData.type,
          data: this.chartData.data,
          options: this.chartData.options,
        });
      },
      getReport() {
        axios.post('/reports/totalSales',this.reports)
        .then((response) => {
          console.log(response.data)
          this.grandTotal = response.data.grandTotal
          this.createChart(response.data)
        })
        .catch((error) => {
          showNotify('danger',error.response.data.message)
        })
      },
      // getExportUrl() {
      //   var exportUrl = '';
      //   if(this.reports.type=="monthly"){
      //     var exportUrl = "http://go.rinse/reports/export?report=deliveredTimewise&type=monthly&year_month="+this.reports.year_month
      //   }
      //   elseif(this.reports.type=="yearly"){
      //     var exportUrl = "http://go.rinse/reports/export?report=deliveredTimewise&type=monthly&year_month="+this.reports.year
      //   }
      //   return http://go.rinse/reports/export?report=deliveredTimewise&type=monthly&year_month=2019-12
      // }
    }
  }
</script>

<style scoped>
  .small {
    max-width: 600px;
    margin:  150px auto;
  }
  .mx-calendar-icon {
    color: #fff !important;
  }
</style>