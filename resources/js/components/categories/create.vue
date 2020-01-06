<template>
  <div class="card-body">
    <div v-for="(section,sec_name,index) in fields">
      <div class="pl-lg-4">
        <br>
        <div class="row">
          <div :class="'col-lg-'+item['col']" v-for="item,key in section">
            <div class="form-group">
              <label class="form-control-label" :for="'input-'+key">{{item['display_name']}}</label>
              <template v-if="item['type']==='file' && key==='icon'" >
                <div class="card-profile-image">
                  <a href="#">
                    <img :src="category.icon_src" class="rounded-circle" @click="triggerIconInput" :class="{'img-not-validated':errors.icon_file}" >
                    <input type="file" class="custom-file-input" lang="en" v-on:change="imageChange" style="display: none;" ref="icon_file">
                    <div class="invalid-feedback" style="display: block;" v-if="errors.icon_file">
                      {{errors.icon_file[0]}}
                    </div>
                  </a>
                </div>            
              </template>
              <input 
                v-if="item['type']==='text' || item['type']==='number'" 
                :class="{'not-validated':errors[key]}" 
                :type="item['type']" 
                :id="'input-'+key" 
                :placeholder="item['display_name']" 
                v-model="category[key]"
                class="form-control" 
              >
              <textarea 
                v-if="item['type']==='textarea'" 
                rows="4" 
                :class="{'not-validated':errors[key]}" 
                class="form-control" 
                :placeholder="item['placeholder']" 
                v-model="category[key]"
              ></textarea>
              <div class="invalid-feedback" style="display: block;" v-if="errors[key]">
                {{errors[key][0]}}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="text-center">
      <button class="btn btn-outline-primary" @click="save">Create</button>
    </div>
  </div>
</template>

<script>
  import {fields} from '../../config/fields'

  export default{
    data(){
      return{
        category:{
          icon_file:'',
          icon_src:window.location.origin+'/files/categories/no_image.png',
        },
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'createCategory')
      this.$store.commit('changeCurrentMenu', 'settingsMenu')
    },
    mounted(){

    },
    methods:{
      save(){
        let formData = new FormData()
        for (var key in this.category) {
            formData.append(key, this.category[key]);
        }
        axios.post('/categories',formData)
        .then((response) => {
          console.log(response.data)
          this.errors = {}
          this.category = {}
          this.$parent.addBtn = true
          this.$store.dispatch('getCategories')
          showNotify('success',response.data)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      triggerIconInput() {
        console.log(this.$refs.icon_file)
          this.$refs.icon_file[0].click()
      },
      imageChange(e) {
        this.category.icon_file = e.target.files[0]
        this.category.icon_src = URL.createObjectURL(this.category.icon_file)
      }
    },
    computed: {
      fields(){
        return fields.createCategory
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
  .img-not-validated{
    border: 3px solid red !important;
  }
</style>