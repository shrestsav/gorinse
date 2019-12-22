<template>
  <div class="card">
    <div class="card-header">
      <div class="row">
        <div class="col">
          <h3 class="mb-0">General</h3>
        </div>
        <div class="col-auto">
          <button type="button" class="btn btn-primary btn-sm" @click="toggleModule">{{module.icon}}</button>
        </div>
      </div>
    </div>
    <div class="card-body" v-if="module.display">
      <div class="row">
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">VAT (%)</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.VAT" :class="{'not-validated':errors.VAT}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.VAT">
                {{errors.VAT[0]}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">Delivery Charge</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.delivery_charge" :class="{'not-validated':errors.delivery_charge}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.delivery_charge">
                {{errors.delivery_charge[0]}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">Urgent Order Charge</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.urgent_charge" :class="{'not-validated':errors.urgent_charge}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.urgent_charge">
                {{errors.urgent_charge[0]}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">Estimated Delivery Time (in Days)</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.EDT" :class="{'not-validated':errors.EDT}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.EDT">
                {{errors.EDT[0]}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">OTP Expiry Time</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.OTP_expiry" :class="{'not-validated':errors.OTP_expiry}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.OTP_expiry">
                {{errors.OTP_expiry[0]}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">App Data Rows</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.app_rows" :class="{'not-validated':errors.app_rows}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.app_rows">
                {{errors.app_rows[0]}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">System Data Rows</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.sys_rows" :class="{'not-validated':errors.sys_rows}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.sys_rows">
                {{errors.sys_rows[0]}}
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-2">
          <div class="form-group">
            <label class="form-control-label">Referral Grant Amount</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.referral_grant" :class="{'not-validated':errors.referral_grant}">
              <div class="invalid-feedback" style="display: block;" v-if="errors.referral_grant">
                {{errors.referral_grant[0]}}
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="float-right">
        <button class="btn btn-outline-primary" @click="save">Save</button>
      </div>
    </div>
  </div>
</template>

<script>
  import { mapState } from 'vuex'
  export default{
    data(){
      return{
        errors:{},
      }
    },
    created(){
    },
    mounted(){

    },
    methods:{
      save(){
        this.appDefaults.saveType = 'generalSetting'
        
        axios.post('/appDefaults',this.appDefaults)
        .then((response) => {
          showNotify('success','General Settings Updated')
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      toggleModule(){
        this.$parent.toggleModule('general')
      }
    },
    computed: {
      module(){
        return this.$parent.modules.general
      },
      ...mapState(['appDefaults'])
    },
  }

</script>