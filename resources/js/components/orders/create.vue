<template>
  <div class="vue-component-body">
    <div class="row">
      <div class="col">
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="mb-0">Create New Order</h3>
              </div>
              <div class="col-4 text-right">
                <a href="#!" class="btn btn-sm btn-primary">Go Back</a>
              </div>
            </div>
          </div>
          <div class="card-body">
            <form autocomplete="off">
            <h6 class="heading-small text-muted mb-4">Order Information</h6>
            <div class="pl-lg-4">
              <div class="row">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label class="form-control-label">Select Customer</label>
                    <v-select
                      class="form-control"   
                      v-model="order.customer_id" 
                      :options="showCustomers"
                      :reduce="fname => fname.id"
                      label="fname" 
                      placeholder="Customers"
                    />
                    <div class="invalid-feedback" style="display: block;" v-if="errors.customer_id">
                      {{errors.customer_id[0]}}
                    </div>
                  </div>
                </div>

                <template v-if="customerSelected">
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label">Type</label>
                      <select class="form-control" v-model="order.type" :class="{'not-validated':errors.type}">
                        <option value="1">Normal</option>
                        <option value="2">Urgent</option>
                      </select>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.type">
                        {{errors.type[0]}}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label">Pickup Location</label>
                      <select class="form-control" v-model="order.pick_location" :class="{'not-validated':errors.pick_location}">
                        <option selected value disabled>Choose Customer Location</option>
                        <option v-for="address in showAddress" :value="address.id">{{address.name}}</option>
                      </select>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.pick_location">
                        {{errors.pick_location[0]}}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label class="form-control-label">Pickup Date</label>
                      <date-picker 
                        v-model="order.pick_date"
                        lang='en' 
                        input-class="form-control"
                        valueType="format" 
                      ></date-picker>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.pick_date">
                        {{errors.pick_date[0]}}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label class="form-control-label">Pickup Time</label>
                      <v-select
                        class="form-control"  
                        v-model="order.pick_timerange" 
                        :options="activeTime"
                        placeholder="Select Time"
                      />
                      <div class="invalid-feedback" style="display: block;" v-if="errors.pick_timerange">
                        {{errors.pick_timerange[0]}}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-4">
                    <div class="form-group">
                      <label class="form-control-label">Drop Location</label>
                      <select class="form-control" v-model="order.drop_location" :class="{'not-validated':errors.drop_location}">
                        <option selected value disabled>Choose Customer Location</option>
                        <option v-for="address in showAddress" :value="address.id">{{address.name}}</option>
                      </select>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.drop_location">
                        {{errors.drop_location[0]}}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label class="form-control-label">Drop Date</label>
                      <date-picker 
                        v-model="order.drop_date"
                        lang='en' 
                        input-class="form-control"
                        valueType="format" 
                      ></date-picker>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.drop_date">
                        {{errors.drop_date[0]}}
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2">
                    <div class="form-group">
                      <label class="form-control-label">Drop Time</label>
                      <v-select
                        class="form-control"  
                        v-model="order.drop_timerange" 
                        :options="activeTime"
                        placeholder="Select Time"
                      />
                      <div class="invalid-feedback" style="display: block;" v-if="errors.drop_timerange">
                        {{errors.drop_timerange[0]}}
                      </div>
                    </div>
                  </div>
                </template>
              </div>
            </div>
            </form>
          </div>
          <div class="card-footer text-center">
             <button class="btn btn-outline-primary" @click="save">Create</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  import vSelect from 'vue-select'
  import 'vue-select/dist/vue-select.css'
  import DatePicker from 'vue2-datepicker'
  import { mapState } from 'vuex'

  export default{
    components: {
      vSelect,DatePicker
    },
    data(){
      return{
        order:{},
        showCustomers:[],
        showAddress:[],
        customerSelected:false,
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'createOrder')
      this.$store.commit('changeCurrentMenu', 'ordersMenu')
      this.load()
    },
    mounted(){
      
    },
    methods:{
      save(){
        axios.post('/orders',this.order)
        .then((response) => {
          this.errors = {}
          this.order = {}
          showNotify('success','Order has been created')
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      load(){
        this.$store.dispatch('getCustomers')
        this.$store.dispatch('getAppDefaults')
      }
    },
    computed: {
      activeTime(){
        return this.appDefaults.order_time
      },
      selectedCustomer(){
        return this.order.customer_id
      },
      ...mapState(['customers', 'address', 'appDefaults'])
    },
    watch: {
      selectedCustomer: function (customer_id) {
        this.customerSelected = true;
        this.order.pick_location = '';
        this.order.drop_location = '';
        this.$store.dispatch('getAddress',customer_id)
      },
      customers(val){
        this.showCustomers = val
      },
      address(val){
        this.showAddress = val
      }
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