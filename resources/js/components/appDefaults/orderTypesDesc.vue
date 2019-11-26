<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-8">
          <h5 class="h3 mb-0">Normal / Urgent Description</h5>
        </div>
        <div class="col-4 text-right">
          <button type="button" class="btn btn-primary btn-sm" @click="toggleModule">{{module.icon}}</button>
        </div>
      </div>
    </div>
    <div class="card-body" v-if="module.display">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-control-label">Normal Order Description</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-clock"></i></span>
              </div>
              <input class="form-control" type="text" v-model="appDefaults.OTD.normal">
            </div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-control-label">Urgent Order Description</label>
            <div class="input-group input-group-merge">
              <div class="input-group-prepend">
                <span class="input-group-text"><i class="fas fa-clock"></i></span>
              </div>
              <input class="form-control" type="text" v-model="appDefaults.OTD.urgent">
            </div>
          </div>
        </div>
      </div>
      <div class="float-right">
        <button class="btn btn-outline-primary" @click="save('supportSetting')">Save</button>
      </div>
    </div>
  </div>
</template>


<script>
  import {settings} from '../../config/settings'
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
      this.$store.dispatch('getOffers')
    },
    methods:{
      save(){
        this.appDefaults.saveType = 'OTD'
        
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
        this.$parent.toggleModule('orderTypesDesc')
      }
    },
    computed: {
      module(){
        return this.$parent.modules.orderTypesDesc
      },
      ...mapState(['appDefaults'])
    },
  }

</script>