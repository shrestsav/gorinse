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
    <div class="table-responsive" v-if="module.display">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>S.No</th>
            <th>Code</th>
            <th>Description</th>
            <th>Discount</th>
            <th>Type</th>
            <th>Status</th>
            <th>Action</th>
          </tr>
        </thead>
        <tbody>
          <!-- For adding new Coupons -->
          <tr v-if="newCoupon">
            <td></td>
            <td>  
              <div class="form-group">
                <input v-model="coupon.code" :class="{'not-validated':errors.code}" type="text" class="form-control" placeholder="COUPON CODE">
                <div class="invalid-feedback" style="display: block;" v-if="errors.code">
                  {{errors.code[0]}}
                </div>
              </div>
            </td>
            <td>
              <div class="form-group">
                <textarea v-model="coupon.description" :class="{'not-validated':errors.description}" class="form-control" rows="3" placeholder="BRIEF DESCRIPTION OF COUPON"></textarea>
                <div class="invalid-feedback" style="display: block;" v-if="errors.description">
                  {{errors.description[0]}}
                </div>
              </div>
            </td>
            <td>
              <div class="form-group">
                <input v-model="coupon.discount" :class="{'not-validated':errors.discount}" type="number" class="form-control" placeholder="COUPON DISCOUNT">
                <div class="invalid-feedback" style="display: block;" v-if="errors.discount">
                  {{errors.discount[0]}}
                </div>
              </div>
            </td>
            <td>
              <select class="form-control" v-model="coupon.type">
                <option value="1">Percentage</option>
                <option value="2">Amount</option>
              </select>
            </td>
            <td>
              <select class="form-control" v-model="coupon.status">
                <option value="1">Active</option>
                <option value="0">Inactive</option>
              </select>
            </td>
            <td>
              <button type="button" class="btn btn-success btn-sm" @click="saveCoupon()">+</button>
              <button type="button" class="btn btn-danger btn-sm" @click="discardCoupon()">-</button>
            </td>
          </tr>
          <!-- For Old Order List -->
          <tr v-for="item,key in coupons">
            <td>{{key+1}}</td>
            <td>
              <!-- <div class="form-group" v-if="editExistingCoupon(item.id)">
                <input v-model="coupon.code" :class="{'not-validated':errors.code}"  type="text" class="form-control" placeholder="COUPON TITLE">
                <div class="invalid-feedback" style="display: block;" v-if="errors.code">
                  {{errors.code[0]}}
                </div>
              </div> -->
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
              <!-- <div class="form-group" v-if="editExistingCoupon(item.id)">
                <input v-model="coupon.discount" :class="{'not-validated':errors.discount}" type="number" class="form-control" placeholder="COUPON DISCOUNT">
                <div class="invalid-feedback" style="display: block;" v-if="errors.discount">
                  {{errors.discount[0]}}
                </div>
              </div> -->
              <div>
                {{item.discount}}
              </div>
            </td>
            <td>
              <!-- <select class="form-control" v-model="coupon.type" v-if="editExistingCoupon(item.id)">
                <option value="1">Percentage</option>
                <option value="2">Amount</option>
              </select> -->
              <div>
                {{couponDiscountType(item.type)}}
              </div>
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

  export default{
    data(){
      return{
        coupon:{
          code:'',
          description:'',
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
        this.coupon = {
          'code' : '',
          'description': '',
          'discount': 0,
          'type': '',
          'status': 0,
        }
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
        this.coupon = {}
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
            axios.delete('/offers/'+id)
            .then((response) => {
              this.$store.dispatch('getOffers')
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
      }
    },
    computed: {
      coupons(){
        return this.$store.getters.coupons
      },
      module(){
        return this.$parent.modules.coupon
      },
    },
  }

</script>