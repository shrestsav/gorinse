<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-8">
          <h5 class="h3 mb-0">Coupons</h5>
        </div>
        <div class="col-4 text-right">
          <button type="button" class="btn btn-info btn-sm" @click="addCoupon()" v-if="addbtn">Add Promo Coupon</button>
        </div>
      </div>
    </div>
    <div class="card-body" v-if="newCoupon">
      <h6 class="heading-small text-muted mb-4">ADD NEW COUPON</h6>
      <div class="row">
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">COUPON CODE</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input v-model="coupon.code" :class="{'not-validated':errors.code}" type="text" class="form-control" placeholder="COUPON CODE">
            </div>
            <div class="invalid-feedback" style="display: block;" v-if="errors.code">
              {{errors.code[0]}}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">Coupon Type</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <select class="form-control" v-model="coupon.coupon_type" :class="{'not-validated':errors.type}">
                <option value="1">SINGLE VALIDITY</option>
                <option value="2">MULTIPLE VALIDITY</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: block;" v-if="errors.type">
              {{errors.type[0]}}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">Discount</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input v-model="coupon.discount" :class="{'not-validated':errors.discount}" type="number" class="form-control" placeholder="COUPON DISCOUNT">
            </div>
            <div class="invalid-feedback" style="display: block;" v-if="errors.discount">
              {{errors.discount[0]}}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">Discount Type</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <select class="form-control" v-model="coupon.type" :class="{'not-validated':errors.type}">
                <option value="1">Percentage</option>
                <option value="2">Amount</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: block;" v-if="errors.type">
              {{errors.type[0]}}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">Valid FROM - TO</label>
            <date-picker 
              range 
              v-model="coupon.valid_from_to"
              lang='en' 
              input-class="form-control"
              valueType="format" 
              format="YYYY-MM-DD HH:mm:ss" :time-picker-options="{ start: '00:00', step: '00:30', end: '23:30' }"
              type="datetime"
            ></date-picker>
            <div class="invalid-feedback" style="display: block;" v-if="errors.valid_from">
              {{errors.valid_from[0]}}
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">Status</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <select class="form-control" v-model="coupon.status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </div>
            <div class="invalid-feedback" style="display: block;" v-if="errors.status">
              {{errors.status[0]}}
            </div>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <label class="form-control-label">Description</label>
            <textarea v-model="coupon.description" :class="{'not-validated':errors.description}" class="form-control" rows="3" placeholder="BRIEF DESCRIPTION OF COUPON"></textarea>
            <div class="invalid-feedback" style="display: block;" v-if="errors.description">
              {{errors.description[0]}}
            </div>
          </div>
        </div>
      </div>
      <div class="float-right">
        <button type="button" class="btn btn-success btn-sm" @click="saveCoupon()">Save</button>
        <button type="button" class="btn btn-danger btn-sm" @click="discardCoupon()">Cancel</button>
        <!-- <button class="btn btn-outline-primary" @click="save">Save</button> -->
      </div>
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>S.No</th>
            <th>Code</th>
            <th>Total Redeems</th>
            <th>Description</th>
            <th>Discount</th>
            <th>Discount Type</th>
            <th>Coupon Type</th>
            <th>Active Interval</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item,key in coupons.data">
            <td>{{key+1}}</td>
            <td>
              <b>{{item.code}}</b>
            </td>
            <td>{{item.total_redeems}}</td>
            <td>
              <div class="form-group" v-if="editExistingCoupon(item.id)">
                <textarea v-model="coupon.description" :class="{'not-validated':errors.description}" class="form-control" rows="3" placeholder="BRIEF DESCRIPTION OF COUPON"></textarea>
                <div class="invalid-feedback" style="display: block;" v-if="errors.description">
                  {{errors.description[0]}}
                </div>
              </div>
              <div v-else>{{item.description}}</div>
            </td>
            <td>
              <div>
                {{item.discount}}
              </div>
            </td>
            <td>
              <div>
                {{couponDiscountType(item.type)}}
              </div>
            </td>
            <td>
              <div>
                {{couponType(item.coupon_type)}}
              </div>
            </td>
            <td>
              {{datetime(item.valid_from)}} - 
              {{datetime(item.valid_to)}}
            </td>
            <td>
              <select class="form-control" v-model="coupon.status" v-if="editExistingCoupon(item.id)">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
              <div v-else>
                {{status(item.status)}}
              </div>
            </td>
            <td>
              <div v-if="editExistingCoupon(item.id)">
                <button type="button" class="btn btn-success btn-sm" @click="saveEditedCoupon">Edit</button>
                <button type="button" class="btn btn-info btn-sm" @click="cancelEditCoupon()">Cancel</button>
              </div>
              <div v-else>
                <button type="button" class="btn btn-danger btn-sm" @click="deleteCoupon(item.id)"><i class="far fa-trash-alt"></i></button>
                <button type="button" class="btn btn-info btn-sm" @click="editCoupon(key)" v-if="editbtn"><i class="far fa-edit"></i></button>
                <button type="button" class="btn btn-info btn-sm" @click="getRedeemedOrders(item.code)" title="View All Coupon Redeems" v-if="item.total_redeems"><i class="far fa-eye"></i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="card-footer py-4" v-if="coupons.data">
      <pagination :data="coupons" @pagination-change-page="getCoupon"></pagination>
    </div>
    <b-modal id="redeemedOrdersModal" ref="redeemedOrdersModal" size="xl" title="Orders" hide-footer hide-header>
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-4">
              <h3 class="mb-0 text-black" v-if="redeemedOrders.length">Reedemed Orders for "{{redeemedOrders[0]['coupon']}}"</h3>
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
                <th>Customer ID</th>
                <th>Customer Name</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in redeemedOrders">
                <td>{{++key}}</td>
                <td>{{item.id}}</td>
                <td>{{dateTimeUTC(item.created_at)}}</td>
                <td>{{item.customer.id}}</td>
                <td>{{item.customer.full_name}}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </b-modal>
  </div>
</template>


<script>
  import {settings} from '../../config/settings'
  import DatePicker from 'vue2-datepicker'

  export default{
    components:{DatePicker},
    data(){
      return{
        coupon:{
          code:'',
          description:'',
          coupon_type:'',
          type:'',
          discount:'',
          status:'',
          valid_from_to:'',
        },
        addbtn:true,
        editbtn:true,
        newCoupon:false,
        redeemedOrders:[],
        modifyCoupon:{
          id:'',
          edit:false,
        },
        coupons:{},
        errors:{},
      }
    },
    created(){
      this.getCoupon()
      this.$store.commit('changeCurrentPage', 'promo')
      this.$store.commit('changeCurrentMenu', 'couponMenu')
    },
    mounted(){

    },
    methods:{
      getCoupon(page = 1){
        axios.get('/coupons?page='+page)
        .then(response => {
          this.coupons = response.data
        }); 
      },
      addCoupon(){
        this.newCoupon = true
        this.editbtn = false
      },
      couponDiscountType(type){
        return settings.couponDiscountType[type]
      },
      couponType(type){
        return settings.couponType[type]
      },
      status(status){
        return settings.status[status]
      },
      saveCoupon(){
        axios.post('/coupons',this.coupon)
        .then((response) => {
          this.$store.dispatch('getCoupons')
          this.coupon = {}
          this.newCoupon = false
          showNotify('success',response.data)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      saveEditedCoupon(){
        axios.patch('/coupons/'+this.coupon.id,this.coupon)
        .then((response) => {
          this.$store.dispatch('getCoupons')
          this.coupon = {}
          this.modifyCoupon.id = ''
          this.modifyCoupon.edit = true
          showNotify('success',response.data)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      discardCoupon(){
        this.coupon = {
          code : '',
          description: '',
          discount: 0,
          type: '',
          status: 0,
        }
        this.newCoupon = false
        this.editbtn = true
      },
      deleteCoupon(id){
        this.$swal({
          title: 'Are you sure?',
          text: "You may not undo this",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            axios.delete('/coupons/'+id)
            .then((response) => {
              this.$store.dispatch('getCoupons')
              showNotify('success',response.data)
            })
            .catch((error) => {  
              showNotify('danger',error.response.data.errors)
            })
          }
        })
      },
      editCoupon(key){
        this.coupon = this.coupons.data[key]
        this.modifyCoupon.id = this.coupons.data[key].id
        this.modifyCoupon.edit = true
        this.addbtn = false
      },
      cancelEditCoupon(){
        this.coupon = {}
        this.modifyCoupon.id = ''
        this.modifyCoupon.edit = false
        this.addbtn = true
      },
      editExistingCoupon(edit_id){
        if(this.modifyCoupon.id == edit_id && this.modifyCoupon.edit)
          return true
        else 
          return false
      },
      datetime(datetime){
        var date = new Date(datetime)
        return this.$moment(String(datetime)).format('YYYY/MM/DD hh:mm a');
      },
      getRedeemedOrders(code){
        axios.get('/coupon/orders/'+code)
        .then((response) => {
          this.redeemedOrders = response.data
          this.$refs['redeemedOrdersModal'].show()
        })
      },
      dateTimeUTC(date){
        if(date){
          var date = new Date(date+' UTC')
          return this.$moment(date).format("ddd MMM DD YYYY [at] HH:mm A")
        }
        else
          return ' - '
      },
    },
    computed: {
    },
  }

</script>