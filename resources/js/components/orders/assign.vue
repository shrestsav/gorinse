<template>
  <div class="modal fade" id="assignOrder" tabindex="-1" role="dialog" aria-labelledby="add_staffs_modal" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h6 class="modal-title" id="modal-title-default">Assign Order</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="closeAssign">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="row" v-if="assign.type=='pickAssign'">
            <div class="col-md-6">
              <h4 class="mb-0">
                Pickup Date
              </h4>
              <span>{{assign.pick_date}}</span>
              <!-- <date-picker 
                v-model="assign.pick_date"
                lang='en' 
                input-class="form-control"
                valueType="format"
                readonly 
              ></date-picker> -->
            </div>
            <div class="col-md-6">
              <h4 class="mb-0">
                Pickup Time
              </h4>
              <span>{{assign.pick_timerange}}</span>
              <!-- <select class="form-control" v-model="assign.pick_timerange" readonly>
                <option disabled selected value>Select Time</option>
                <option v-for="timerange in orderTime" :value="timerange">{{timerange}}</option>
              </select> -->
            </div>
          </div>
          <div class="row" v-if="assign.type=='dropAssign'">
            <div class="col-md-6">
              <h4 class="mb-0">
                Drop Date
              </h4>
              <date-picker 
                v-model="assign.drop_date"
                lang='en' 
                input-class="form-control"
                valueType="format" 
              ></date-picker>
            </div>
            <div class="col-md-6">
              <h4 class="mb-0">
                Drop Time
              </h4>
              <select class="form-control" v-model="assign.drop_timerange">
                <option disabled selected value>Select Time</option>
                <option v-for="timerange in orderTime" :value="timerange">{{timerange}}</option>
              </select>
            </div>
          </div>
          <div class="row mt-5">
            <div class="col-md-12">
              <h4 class="mb-1">
                <a href="javascript:;">Assign to</a>
              </h4>
              <v-select
                class="form-control"  
                v-model="assign.driver_id" 
                :options="drivers" 
                :reduce="fname => fname.id"
                label="fname" 
                placeholder="Drivers"
              />
            </div>
          </div>
          <div class="invalid-feedback" style="display: block;" v-if="errors.driver_id">
            {{errors.driver_id[0]}}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          <button class="btn btn-outline-success" @click="setAssign()">Assign</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import vSelect from 'vue-select'
  import 'vue-select/dist/vue-select.css'
  import DatePicker from 'vue2-datepicker'
    
  export default{
    components: {
      vSelect,DatePicker
    },
    props: ['active'],
    data(){
      return{
        assign:{
          pick_date:'',
          pick_timerange:'',
          order_id:'',
          type:''
        },
        showErr:false,
        errors:{}
      }
    },
    mounted(){
      this.$store.dispatch('getDrivers')
      this.$store.dispatch('getOrderTime')
    },
    computed: {
      order(){
        return this.$store.getters.orders.data[this.active.order]
      },
      drivers(){
        return this.$store.getters.drivers
      },
      orderTime(){
        return this.$store.getters.orderTime
      },
    },
    methods:{
      mount(){
        this.assign.pick_date = this.order.pick_date
        this.assign.pick_timerange = this.order.pick_timerange
        this.assign.order_id = this.order.id
        this.assign.type = this.active.type
      },
      setAssign(){
        axios.post('/assignOrder',this.assign)
        .then((response) => {
          this.$refs.closeAssign.click()
          this.assign = {}
          this.$store.dispatch('getOrders',this.active)
          showNotify('success',response.data)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          } 
        })
      }
    },
}

</script>