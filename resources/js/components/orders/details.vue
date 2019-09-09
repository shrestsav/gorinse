<template>
  <div class="card">
<!--     <div class="card-header">
      <div class="col-md-2">
        <div class="nav-wrapper">
          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
              <a class="nav-link mb-sm-3 mb-md-0 active" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">ORDER DETAILS</a>
            </li>
          </ul>
        </div>
      </div>
    </div> -->
    <div class="card-body">
      <h6 class="heading-small text-muted mb-4">Order Information</h6>
      <div class="pl-lg-4">
        <div class="row" v-if="details">
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">Customer Name</label>
              <br>
              <span>{{details.customer.fname}} {{details.customer.lname}}</span>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">Assigned Driver</label>
              <br>
              <span v-if="details.driver">{{details.driver.fname}} {{details.driver.lname}}</span>
              <span v-else>Not Assigned</span>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">Order Status</label>
              <br>
              <span>{{orderStatus(details.status)}}</span>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">Order Type</label>
              <br>
              <span>{{orderType(details.type)}}</span>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">Pickup Location</label>
              <br>
              <span>{{details.pick_location_details.name}}</span>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">Pickup Date</label>
              <br>
              <span>{{details.pick_date}}</span>
            </div>
          </div>
          <div class="col-lg-3">
            <div class="form-group">
              <label class="form-control-label">Pickup Timerange</label>
              <br>
              <span>{{details.pick_timerange}}</span>
            </div>
          </div>
          <div class="col-lg-3" v-if="details.drop_location_details">
            <div class="form-group">
              <label class="form-control-label">Drop Location</label>
              <br>
              <span>{{details.drop_location_details.name}}</span>
            </div>
          </div>
          <div class="col-lg-3" v-if="details.drop_date">
            <div class="form-group">
              <label class="form-control-label">Drop Date</label>
              <br>
              <span>{{details.drop_date}}</span>
            </div>
          </div>
          <div class="col-lg-3" v-if="details.drop_timerange">
            <div class="form-group">
              <label class="form-control-label">Drop Timerange</label>
              <br>
              <span>{{details.drop_timerange}}</span>
            </div>
          </div>
        </div>
      </div>
      <hr class="my-4"/>
      <h6 class="heading-small text-muted mb-4">Invoice</h6>
      <div class="pl-lg-4" v-if="invoice">
        <div class="row">
          <div class="table-responsive" v-for="service,name in invoice.items_details">
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Service : </label>
                  <span>{{name}}</span>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Payment Type : </label>
                  <!-- <span>{{invoice.customer}} {{details.customer.lname}}</span> -->
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Delivery Type : </label>
                  <!-- <span>{{invoice.customer_details.order_type}}</span> -->
                </div>
              </div>
            </div>
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th>S.No.</th>
                  <th>Items</th>
                  <th>Quantity</th>
                  <th>Service Charge (AED)</th>
                  <th>Item Charge (AED)</th>
                  <th>Amount (AED)</th>
                  <th>Remarks</th>
                </tr>
              </thead>
              <tbody class="list">
                <tr v-for="(item,index) in service">
                  <td>{{index+1}}</td>
                  <td>{{item.item}}</td>
                  <td>{{item.quantity}}</td>
                  <td>{{item.service_charge}}</td>
                  <td>{{item.item_charge}}</td>
                  <td>{{item.total}}</td>
                  <td>{{item.remarks}}</td>
                </tr>
              </tbody>
            </table>
            <table class="">
              <tr>
                <td>Total Quantity</td>
                <td>{{invoice.invoice_details.total_quantity}}</td>     
              </tr>
              <tr>
                <td>Total Amount</td>
                <td>AED {{invoice.invoice_details.total_amount}}</td>
              </tr>
              <tr>
                <td>VAT ({{invoice.invoice_details.VAT_percent}}%)</td>
                <td>AED {{invoice.invoice_details.VAT}}</td>                    
              </tr>
              <tr>
                <td>Delivery Charge</td>
                <td>AED {{invoice.invoice_details.delivery_charge}}</td>        
              </tr>
              <tr>
                <td>Grand Total</td>
                <td>AED {{invoice.invoice_details.grand_total}}</td>
              </tr>

            </table>
          </div>
        </div>
      </div>

    </div>
  </div>
</template>

<script>

  import {settings} from '../../config/settings'

  export default{
    data(){
      return{
        orderID:'',
      }
    },
    created(){
      if(this.$route.query.orderID===undefined){
        this.$router.push({name:'jobs'});
      }
      else{
        this.orderID = this.$route.query.orderID;
      }
      this.$store.commit('changeCurrentPage', 'orderDetails')
      this.$store.commit('changeCurrentMenu', 'ordersMenu')
    },
    mounted(){
      this.$store.dispatch('getOrderDetails',this.orderID)
    },
    methods:{
      orderType(type){
        if(type==1)
          return 'Normal'
        else if(type==2)
          return 'Urgent'
      },
      orderStatus(status){
        return settings.orderStatuses[status]
      },
    },
    computed: {
      details(){
        return this.$store.getters.orderDetails.details
      },
      invoice(){
        return this.$store.getters.orderDetails.invoice
      },
    }

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
</style>