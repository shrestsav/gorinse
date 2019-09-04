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
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">VAT (%)</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-user"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.VAT">
            </div>
          </div>
        </div>
        <div class="col-md-3">
          <div class="form-group">
            <label class="form-control-label">Delivery Charge</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-envelope"></i></span>
              </div>
              <input class="form-control" type="number" v-model="appDefaults.delivery_charge">
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
              <input class="form-control" type="number" v-model="appDefaults.OTP_expiry">
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
              <input class="form-control" type="number" v-model="appDefaults.app_rows">
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
              <input class="form-control" type="number" v-model="appDefaults.sys_rows">
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