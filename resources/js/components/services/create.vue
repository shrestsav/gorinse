<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-8">
          <h3 class="mb-0">Add New Service</h3>
        </div>
        <div class="col-4 text-right">
          <a href="javascript:;" class="btn btn-sm btn-primary">Go Back</a>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div v-for="(section,sec_name,index) in fields">
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
                  v-model="service[key]"
                  class="form-control" 
                >
                <textarea 
                  v-if="item['type']==='textarea'" 
                  rows="4" 
                  :class="{'not-validated':errors[key]}" 
                  class="form-control" 
                  :placeholder="item['placeholder']" 
                  v-model="service[key]"
                ></textarea>
                <div class="invalid-feedback" style="display: block;" v-if="errors[key]">
                  {{errors[key][0]}}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer text-center">
       <button class="btn btn-outline-primary" @click="save">Create</button>
    </div>
  </div>
</template>

<script>
  import {fields} from '../../config/fields'

  export default{
    data(){
      return{
        service:{},
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'createService')
      this.$store.commit('changeCurrentMenu', 'settingsMenu')
    },
    mounted(){

    },
    methods:{
      save(){
        axios.post('/services',this.service)
        .then((response) => {
          this.errors = {} 
          this.service = {}
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
    computed: {
      fields(){
        return fields.createService
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