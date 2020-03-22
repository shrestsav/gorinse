<template>
  <b-modal id="driverOrders" ref="driverOrders" title="Orders" hide-footer hide-header>
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-4">
            <h6 class="text-black text-uppercase ls-1 mb-1">Driver</h6>
            <h5 class="h3 text-black mb-0">{{driver.full_name}}</h5>
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
              <div class="col-3">
                <template v-if="reports.type=='monthly'">
                  <h6 class="text-light text-uppercase ls-1 mb-1">Select Month</h6>
                  <date-picker 
                    @change="driverOrders(1)" 
                    input-class="form-control bg-transparent" 
                    v-model="reports.year_month" 
                    lang="en" 
                    type="month" 
                    format="YYYY-MM" 
                    valueType="format">
                  </date-picker>
                </template>
                <template v-if="reports.type=='yearly'">
                  <h6 class="text-light text-uppercase ls-1 mb-1">Select Year</h6>
                  <date-picker 
                    @change="driverOrders(1)" 
                    input-class="form-control bg-transparent" 
                    v-model="reports.year" 
                    lang="en" 
                    type="year" 
                    format="YYYY" 
                    valueType="format"> 
                  </date-picker>
                </template>
              </div>
              <div class="col-3">
                <h6 class="text-light text-uppercase ls-1 mb-1">Job</h6>
                <select class="form-control bg-transparent" v-model="reports.job_type" @change="driverOrders(1)" >
                  <option value="any">All</option>
                  <option value="pick">Pick</option>
                  <option value="drop">Drop</option>
                  <option value="pick_drop">Pick and Drop</option>
                </select>
              </div>
            </div>
          </div>
          <div class="col-3 text-right">
            <a :href="origin_url+'/reports/export?report=driverOrders&driver_id='+reports.driver_id+'&type='+reports.type+'&year='+reports.year+'&year_month='+reports.year_month+'&job_type='+reports.job_type" target="_blank"><button type="button" class="btn btn-success btn-sm">Export <i class="fas fa-file-excel"></i></button></a>
          </div>
        </div>
      </div>
      <div class="table-responsive">
        <table class="table align-items-center table-flush">
          <thead class="thead-light">
            <tr>
              <th>S.No.</th>
              <th>Order No</th>
              <th>Ordered Date</th>
              <th>Pick Date</th>
              <th>Drop Date</th>
              <th>Client</th>
              <th>Area</th>
              <th>Status</th>
              <th>Amount</th>
              <th>Type</th>
            </tr>
          </thead>
          <tbody class="list">
            <tr v-for="item,key in orders.data">
              <td>{{++key}}</td>
              <td>{{item.id}}</td>
              <td>{{dateTime(item.created_at)}}</td>
              <td><template v-if="item.details">{{dateTime(item.details.PFC)}}</template></td>
              <td><template v-if="item.details">{{dateTime(item.details.DAO)}}</template></td>
              <td>{{item.customer.full_name}}</td>
              <td>
                <span v-if="item.pick_location_details && item.pick_location_details.main_area">{{item.pick_location_details.main_area.name}}</span>
              </td>
              <td>{{orderStatus[item.status]}}</td>
              <td>
                <template v-if="item.total_amount">AED {{item.total_amount}}</template>
                <template v-else>Pending</template>
              </td>
              <td>
                <template v-if="item.driver_id==reports.driver_id && item.drop_driver_id==reports.driver_id">
                  Pick and Drop
                </template>
                <template v-else-if="item.driver_id==reports.driver_id && item.drop_driver_id!=reports.driver_id">
                  Pick
                </template>
                <template v-else-if="item.driver_id!=reports.driver_id && item.drop_driver_id==reports.driver_id">
                  Drop
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer py-4" v-if="orders.data">
        <div class="row">
          <div class="col-8">
            <pagination :data="orders" @pagination-change-page="driverOrders"></pagination>
          </div>
          <div class="col-2">
            <h6 class="text-black text-uppercase ls-1 mb-1">Total Order</h6>
            <h5 class="h3 text-black mb-0">{{orders.total}}</h5>
          </div>
          <div class="col-2">
            <h6 class="text-black text-uppercase ls-1 mb-1">Total Amount</h6>
            <h5 class="h3 text-black mb-0">AED {{totalAmount}}</h5>
          </div>
        </div>
      </div>
    </div>
  </b-modal>
</template>

<script>
  import DatePicker from 'vue2-datepicker'
  export default{
    components: {
      DatePicker
    },
    data(){
      return{
        reports:{
          driver_id:'',
          page:'',
          type:'',
          job_type:'',
          year:'',
          year_month:'',
        },
        driver:{},
        drivers:[],
        orders:[],
        orderStatus:[],
        origin_url: window.location.origin
      }
    },
    methods:{
      init(id){
        var today = new Date()
        this.reports = {
          driver_id:id,
          page:'',
          year:'',
          type: 'monthly',
          job_type: 'any',
          year_month: today.getFullYear()+'-'+(Number(today.getMonth())+1),
        }
        this.driverOrders()
      },
      hideModal() {
        this.$refs['driverOders'].hide()
        this.reports.driver_id = ''
      },
      dateTime(date){
        if(date){
          var date = new Date(date+' UTC')
          return this.$moment(date).format("ddd MMM DD YYYY [at] HH:mm A")
        }
        else
          return ' - '
      },
      driverOrders( page = 1 ){
        axios.get('/driver/orders/'+this.reports.driver_id+'?page='+page+'&type='+this.reports.type+'&year='+this.reports.year+'&year_month='+this.reports.year_month+'&job_type='+this.reports.job_type)
        .then((response) => {
          this.orders = response.data.orders
          this.orderStatus = response.data.orderStatus
          this.driver = response.data.driver
          this.$refs['driverOrders'].show()
        })
      },
    },
    computed: {
      totalAmount(){
        var data = this.orders.data
        var filtered = data.filter((item) => {
          return item.total_amount
        })
        var total = filtered.reduce((currentTotal, item) => {
          return item.total_amount + currentTotal
        }, 0)
       
        return total
      }
    },
  }

</script>