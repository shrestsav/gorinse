<template>
  <b-modal id="driverOrders" ref="driverOrders" title="Orders" hide-footer hide-header>
    <div class="card">
      <div class="card-header">
        <div class="row align-items-center">
          <div class="col-4">
            <h3 class="mb-0 text-black">Orders</h3>
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
                <date-picker @change="driverOrders(1)" input-class="form-control bg-transparent" v-model="reports.year_month" lang="en" type="month" format="YYYY-MM" valueType="format" ></date-picker>
              </div>
              <div class="col-3" v-if="reports.type=='yearly'">
                <h6 class="text-light text-uppercase ls-1 mb-1">Select Year</h6>
                <date-picker @change="driverOrders(1)" input-class="form-control bg-transparent" v-model="reports.year" lang="en" type="year" format="YYYY" valueType="format" ></date-picker>
              </div>
            </div>
          </div>
          <div class="col-3 text-right">
            <a :href="origin_url+'/reports/export?report=driverOrders&driver_id='+reports.driver_id+'&type='+reports.type+'&year='+reports.year+'&year_month='+reports.year_month" target="_blank"><button type="button" class="btn btn-success btn-sm">Export <i class="fas fa-file-excel"></i></button></a>
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
              <td>{{dateTime(item.details.PFC)}}</td>
              <td>{{dateTime(item.details.DAO)}}</td>
              <td>{{item.customer.full_name}}</td>
              <td><span v-if="item.pick_location_details && item.pick_location_details.main_area">{{item.pick_location_details.main_area.name}}</span></td>
              <td>{{orderStatus[item.status]}}</td>
              <td><template v-if="item.total_amount">AED {{item.total_amount}}</template><template v-else>Pending</template></td>
              <td>
                <template v-if="item.driver_id==reports.driver_id && item.drop_driver_id==reports.driver_id">
                  Pick and Drop
                </template>
                <template v-else="item.driver_id==reports.driver_id && item.drop_driver_id!=reports.driver_id">
                  Pick
                </template>
                <template v-else="item.driver_id!=reports.driver_id && item.drop_driver_id==reports.driver_id">
                  Drop
                </template>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="card-footer py-4" v-if="orders.data">
        <pagination :data="orders" @pagination-change-page="driverOrders"></pagination>
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
        this.reports.driver_id = id
        var today = new Date()
        this.reports.type = 'monthly'
        this.reports.year_month = today.getFullYear()+'-'+(Number(today.getMonth())+1)
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
        axios.get('/driver/orders/'+this.reports.driver_id+'?page='+page+'&type='+this.reports.type+'&year='+this.reports.year+'&year_month='+this.reports.year_month)
        .then((response) => {
          this.orders = response.data.orders
          this.orderStatus = response.data.orderStatus
          this.$refs['driverOrders'].show()
        })
      }
    },
    computed: {

    },
  }

</script>