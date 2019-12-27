<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-8">
          <h5 class="h3 mb-0">Referral Coupons</h5>
        </div>
        <div class="col-4 text-right">
        
        </div>
      </div>
    </div>
    <div class="table-responsive">
      <table class="table align-items-center table-flush">
        <thead class="thead-light">
          <tr>
            <th>S.No</th>
            <th>Code</th>
            <th>Customer ID</th>
            <th>Customer Name</th>
            <th>Discount</th>
            <th>Discount Type</th>
            <th>Active Interval</th>
            <th>Used Status</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item,key in coupons.data">
            <td>{{key+1}}</td>
            <td>
              <b>{{item.code}}</b>
            </td>
            <td>{{item.user_with_access.id}}</td>
            <td>{{item.user_with_access.full_name}}</td>
            <td>AED {{item.discount}}</td>
            <td>{{couponDiscountType(item.type)}}</td>
            <td>
              {{datetime(item.valid_from)}} - 
              {{datetime(item.valid_to)}}
            </td>
            <td>{{redeemed(item.redeemed)}}</td>
          </tr>
        </tbody>
      </table>
    </div>
    <div class="card-footer py-4" v-if="coupons.data">
      <pagination :data="coupons" @pagination-change-page="getReferralCoupon"></pagination>
    </div>
  </div>
</template>


<script>
  import {settings} from '../../config/settings'
  import DatePicker from 'vue2-datepicker'

  export default{
    components:{DatePicker},
    data(){
      return{
        coupons:{},
      }
    },
    created(){
      this.getReferralCoupon()
      this.$store.commit('changeCurrentPage', 'referral')
      this.$store.commit('changeCurrentMenu', 'couponMenu')
    },
    mounted(){

    },
    methods:{
      getReferralCoupon(page = 1){
        axios.get('/coupon/referral?page='+page)
        .then(response => {
          this.coupons = response.data
        }); 
      },
      couponDiscountType(type){
        return settings.couponDiscountType[type]
      },
      couponType(type){
        return settings.couponType[type]
      },
      redeemed(status){
        if(status)
          return 'Redeemed'
        return 'Not Redeemed'
      },
      datetime(datetime){
        var date = new Date(datetime)
        return this.$moment(String(datetime)).format('YYYY/MM/DD hh:mm a');
      },
    }
  }

</script>