<template>
  <div class="card">
    <div class="card-header">
      <div class="col-md-2">
        <div class="nav-wrapper">
          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
              <a class="nav-link mb-sm-3 mb-md-0 active" id="createOrder" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Create Order</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div v-for="(section,sec_name,index) in fields">
        <h6 class="heading-small text-muted mb-4">{{sec_name}}</h6>
        <div class="pl-lg-4">
          <div class="row">
            <div :class="'col-lg-'+item['col']" v-for="item,key in section">
              <div class="form-group">
                <label class="form-control-label" :for="'input-'+key">{{item['display_name']}}</label>
                <input 
                  v-if="item['type']==='text' || item['type']==='number'" 
                  :class="{'not-validated':errors[key]}" 
                  :type="item['type']" 
                  :id="'input-'+key" 
                  :placeholder="item['display_name']" 
                  v-model="order[key]"
                  class="form-control" 
                >
                <v-select
                  class="form-control"  
                  v-if="item['type']==='select' && key==='customer_id'" 
                  v-model="order[key]" 
                  :options="customers" 
                  :reduce="fname => fname.id" 
                  label="fname" 
                  placeholder="Customers"
                />
                <select class="form-control" v-if="item['type']==='select' && key==='order_type'" v-model="order[key]" :class="{'not-validated':errors[key]}" >
                  <option value="normal">Normal</option>
                  <option value="urgent">Urgent</option>
                </select>
                <select class="form-control" v-if="item['type']==='select' && key==='status'" v-model="order[key]" :class="{'not-validated':errors[key]}" >
                  <option :value="key" v-for="item,key in orderStatus">{{item}}</option>
                </select>
                <date-picker 
                  v-if="item['type']==='date'"  
                  v-model="order[key]"
                  lang='en' 
                  input-class="form-control"
                ></date-picker>
                <date-picker 
                  v-if="item['type']==='datetime'"  
                  v-model="order[key]"
                  lang='en' 
                  type="datetime" 
                  input-class="form-control"
                  format="YYYY-MM-DD hh:mm:ss a" :time-picker-options="{ start: '00:00', step: '00:30', end: '23:30' }"
                ></date-picker>
                <div class="invalid-feedback" style="display: block;" v-if="showErr">
                  {{errors[key]}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-4"/>
      </div>
    </div>
    <div class="card-footer text-center">
       <button class="btn btn-outline-primary" @click="save">Create</button>
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
    data(){
      return{
        errors:{},
        showErr:false,
        fields:{},
        order:{
          customer_id: '',
          order_date: '',
          order_type: '',
          pickup_location: '',
          pickup_datetime: '',
          drop_location: '',
          drop_datetime: '',
          price: '',
          vat_amount: '',
          delivery_charge: '',
          status: '',
        },
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'createOrder')
      this.$store.commit('changeCurrentMenu', 'ordersMenu')
      this.$store.dispatch('getCustomers')
      this.$store.dispatch('getOrderStatus')
    },
    mounted(){
      this.defSettings();
    },
    methods:{
      defSettings(){
        axios.get('/getFields/createOrder').then(response => this.fields = response.data)
      },
      save(){
        this.order.created_at = new Date();
        this.order.updated_at = new Date();
        if(this.validate()){
          this.errors = {};
          this.$store.dispatch('addOrder', this.order).then(() => {
            showNotify('success','Order has been created')
          })
        }
        else{
          this.showErr = true;
        }

      },
      validate(){
        if(this.order.customer_id && this.order.order_date && this.order.order_type && this.order.pickup_location && this.order.pickup_datetime && this.order.status){
          return true;
        }
        if(!this.order.customer_id){
          this.errors.customer_id = 'Select Customer';
        }
        if(!this.order.order_date){
          this.errors.order_date = 'Select Order Date';
        }
        if(!this.order.order_type){
          this.errors.order_type = 'Select Order Type';
        }
        if(!this.order.pickup_location){
          this.errors.pickup_location = 'Pickup Location Required';
        }
        if(!this.order.pickup_datetime){
          this.errors.pickup_datetime = 'Enter Pickup time';
        }
        if(!this.order.status){
          this.errors.status = 'Select Status';
        }
        return false;
      }
    },
    computed: {
      customers(){
        return this.$store.getters.customers
      },
      orderStatus(){
        return this.$store.getters.orderStatus
      }
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
</style>