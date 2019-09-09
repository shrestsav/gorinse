<template>
  <div class="modal fade" id="orderDetails" tabindex="-1" role="dialog" aria-labelledby="add_staffs_modal" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal- modal-fullscreen" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h6 class="modal-title" id="modal-title-default">Assign Order</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="myBtn">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body" v-if="loaded">
          <h6 class="heading-small text-muted mb-4">Order Information</h6>
          <div class="pl-lg-4">
            <div class="row">
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Customer Name</label>
                  <br>
                  <span>{{order.details.customer.fname}} {{order.details.customer.lname}}</span>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Assigned Driver</label>
                  <br>
                  <span v-if="order.details.driver">{{order.details.driver.fname}} {{order.details.driver.lname}}</span>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Order Status</label>
                  <br>
                  <span>{{orderStatus(order.details.status)}}</span>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Order Type</label>
                  <br>
                  <span>{{orderType(order.details.type)}}</span>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Pickup Location</label>
                  <br>
                  <span>{{order.details.pick_location_details.name}}</span>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Pickup Date</label>
                  <br>
                  <span>{{order.details.pick_date}}</span>
                </div>
              </div>
              <div class="col-lg-3">
                <div class="form-group">
                  <label class="form-control-label">Pickup Timerange</label>
                  <br>
                  <span>{{order.details.pick_timerange}}</span>
                </div>
              </div>
              <div class="col-lg-3" v-if="order.details.drop_location_details">
                <div class="form-group">
                  <label class="form-control-label">Drop Location</label>
                  <br>
                  <span>{{order.details.drop_location_details.name}}</span>
                </div>
              </div>
              <div class="col-lg-3" v-if="order.details.drop_date">
                <div class="form-group">
                  <label class="form-control-label">Drop Date</label>
                  <br>
                  <span>{{order.details.drop_date}}</span>
                </div>
              </div>
              <div class="col-lg-3" v-if="order.details.drop_timerange">
                <div class="form-group">
                  <label class="form-control-label">Drop Timerange</label>
                  <br>
                  <span>{{order.details.drop_timerange}}</span>
                </div>
              </div>
            </div>
          </div>
          <hr class="my-4"/>
          <h6 class="heading-small text-muted mb-4">Invoice</h6>
          <div class="pl-lg-4" v-if="order.invoice">
            <div class="row">
              <div class="table-responsive" v-for="service,name in order.invoice.items_details">
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
                      <!-- <span>{{order.invoice.customer}} {{order.details.customer.lname}}</span> -->
                    </div>
                  </div>
                  <div class="col-lg-3">
                    <div class="form-group">
                      <label class="form-control-label">Delivery Type : </label>
                      <!-- <span>{{order.invoice.customer_details.order_type}}</span> -->
                    </div>
                  </div>
                </div>
                <table class="table align-items-center table-flush">
                  <thead class="thead-light">
                    <tr>
                      <th scope="col">S.No.</th>
                      <th scope="col">Items</th>
                      <th scope="col">Quantity</th>
                      <th scope="col">Service Charge (AED)</th>
                      <th scope="col">Item Charge (AED)</th>
                      <th scope="col">Amount (AED)</th>
                      <th scope="col">Remarks</th>
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
                <table>
                  <thead>
                    <tr>
                      <th>Total Quantity</th>
                      <th>{{order.invoice.invoice_details.total_quantity}}</th>     
                    </tr>
                    <tr>
                      <th>Total Amount</th>
                      <th>AED {{order.invoice.invoice_details.total_amount}}</th>
                    </tr>
                    <tr>
                      <th>VAT ({{order.invoice.invoice_details.VAT_percent}}%)</th>
                      <th>AED {{order.invoice.invoice_details.VAT}}</th>                    
                    </tr>
                    <tr>
                      <th>Delivery Charge</th>
                      <th>AED {{order.invoice.invoice_details.delivery_charge}}</th>        
                    </tr>
                    <tr>
                      <th>Grand Total</th>
                      <th>AED {{order.invoice.invoice_details.grand_total}}</th>
                    </tr>
                  </thead>
                </table>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
<!--           <button class="btn btn-outline-success" @click="setAssign()">Create</button> -->
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import {settings} from '../../config/settings'

  export default{
    components: {

    },
    props: ['active'],
    data(){
      return{
        loaded:false,
      }
    },
    mounted(){
      
    },
    methods:{
      mount(){
        this.$store.dispatch('getOrderDetails',this.active.order_id).then(()=>{
          this.loaded = true
        })
      },
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
      order(){
        return this.$store.getters.orderDetails
      },
    },
  }

</script>