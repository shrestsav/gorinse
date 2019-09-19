<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-8">
          <h3 class="mb-0">Add New Item</h3>
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
            <div :class="'col-lg-'+data['col']" v-for="data,key in section">
              <div class="form-group">
                <label class="form-control-label" :for="'input-'+key">{{data['display_name']}}</label>
                <input 
                  v-if="data['type']==='text' || data['type']==='number'" 
                  :class="{'not-validated':errors[key]}" 
                  :type="data['type']" 
                  :id="'input-'+key" 
                  :placeholder="data['display_name']" 
                  v-model="item[key]"
                  class="form-control" 
                >
                <v-select
                  class="form-control"  
                  v-if="data['type']==='select' && key==='category_id'" 
                  v-model="item[key]" 
                  :options="categories"
                  :reduce="name => name.id"
                  label="name" 
                  placeholder="Category"
                />
                <textarea 
                  v-if="data['type']==='textarea'" 
                  rows="4" 
                  :class="{'not-validated':errors[key]}" 
                  class="form-control" 
                  :placeholder="data['placeholder']" 
                  v-model="item[key]"
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
  import vSelect from 'vue-select'
  import 'vue-select/dist/vue-select.css'
  import {fields} from '../../config/fields'

  export default{
    components: {
      vSelect
    },
    data(){
      return{
        item:{},
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'createItem')
      this.$store.commit('changeCurrentMenu', 'settingsMenu')
      this.$store.dispatch('getCategories')
    },
    mounted(){
      
    },
    methods:{
      save(){
        axios.post('/items',this.item)
        .then((response) => {
          this.errors ={}
          this.item = {}
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
      categories(){
        return this.$store.getters.categories
      },
      fields(){
        return fields.createItem
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