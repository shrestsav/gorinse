<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-8">
          <h5 class="h3 mb-0">Coupons</h5>
        </div>
        <div class="col-4 text-right">
          <button type="button" class="btn btn-info btn-sm" @click="addCoupon()" v-if="module.display && addbtn">Add</button>
          <button type="button" class="btn btn-primary btn-sm" @click="toggleModule">{{module.icon}}</button>
        </div>
      </div>
    </div>
    <div class="card-body" v-if="newCoupon && module.display">
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
        <div class="col-md-2">
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
        <div class="col-md-2">
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
            <label class="form-control-label">Valid From To</label>
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
        <div class="col-md-2">
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
    <div class="table-responsive" v-if="module.display">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>S.No</th>
            <th>Code</th>
            <th>Description</th>
            <th>Discount</th>
            <th>Type</th>
            <th>Active Interval</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item,key in coupons">
            <td>{{key+1}}</td>
            <td>
              <b>{{item.code}}</b>
            </td>
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
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script>
  import {settings} from '../../config/settings'
  import { mapState } from 'vuex'
  import DatePicker from 'vue2-datepicker'

  export default{
    components:{DatePicker},
    data(){
      return{
        coupon:{
          code:'',
          description:'',
          type:'',
          discount:'',
          status:'',
          valid_from_to:'',
        },
        addbtn:true,
        editbtn:true,
        newCoupon:false,
        modifyCoupon:{
          id:'',
          edit:false,
        },
        errors:{},
      }
    },
    created(){
      this.$store.dispatch('getCoupons')
    },
    mounted(){
      console.log(this.$parent.modules)
    },
    methods:{
      //Coupon Methods
      addCoupon(){
        this.newCoupon = true
        this.editbtn = false
      },
      couponDiscountType(type){
        return settings.couponDiscountType[type]
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
        this.coupon = this.coupons[key]
        this.modifyCoupon.id = this.coupons[key].id
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
      toggleModule(){
        this.$parent.toggleModule('coupon')
      },
      datetime(datetime){
        var date = new Date(datetime)
        return this.$moment(String(datetime)).format('YYYY/MM/DD hh:mm a');
      },
    },
    computed: {
      ...mapState(['coupons']),
      module(){
        return this.$parent.modules.coupon
      },
    },
  }

</script>