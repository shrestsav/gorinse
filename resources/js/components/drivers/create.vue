<template>
  <div class="card">
    <div class="card-header">
      <div class="col-md-2">
        <div class="nav-wrapper">
          <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
            <li class="nav-item">
              <a class="nav-link mb-sm-3 mb-md-0 active" id="createDriver" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Add Driver</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="card-body">
      <div v-for="(section,sec_name,index) in fields">
        <h6 class="heading-small text-muted mb-4">{{sec_name}}</h6>
        <div class="pl-lg-4">
          <div class="row">
            <div :class="'col-lg-'+item['col']" v-for="item,key in section">
              <div class="form-group">
                <label class="form-control-label" :for="'input-'+key">{{item['display_name']}}</label>
                <input 
                  v-if="item['type']==='text' || item['type']==='number' || item['type']==='email'" 
                  :class="{'not-validated':errors[key]}" 
                  :type="item['type']" 
                  :id="'input-'+key" 
                  :placeholder="item['display_name']" 
                  v-model="driver[key]"
                  class="form-control"  
                >
                <date-picker 
                  :input-class="{'not-validated':errors[key]}" 
                  v-if="item['type']==='date'"  
                  v-model="driver[key]"
                  lang='en' 
                  valueType="format" 
                  input-class="form-control"
                ></date-picker>
                <textarea 
                  v-if="item['type']==='textarea'" 
                  rows="4" 
                  :class="{'not-validated':errors[key]}" 
                  class="form-control" 
                  placeholder="Write something about driver" 
                  v-model="driver[key]"
                ></textarea>
                <select class="form-control" v-if="item['type']==='select' && key==='area_id'" v-model="driver[key]" :class="{'not-validated':errors[key]}" >
                  <option v-for="location in mainArea" :value="location.id">{{location.name}}</option>
                </select>
                <div class="invalid-feedback" style="display: block;" v-if="errors[key]">
                  {{errors[key][0]}}
                </div>
              </div>
            </div>
          </div>
        </div>
        <hr class="my-4"/>
      </div>
    </div>
    <div class="card-footer text-center">
       <button class="btn btn-outline-info" @click="save">Create</button>
    </div>
  </div>
</template>

<script>

  import DatePicker from 'vue2-datepicker'

  export default{
    components: {
      DatePicker
    },
    data(){
      return{
        fields:{},
        driver:{},
        errors:{}
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'createDriver')
      this.$store.commit('changeCurrentMenu', 'driversMenu')
    },
    mounted(){
      this.defSettings();
    },
    methods:{
      defSettings(){
        axios.get('/getFields/createUser').then(response => this.fields = response.data)
        this.$store.dispatch('getMainAreas')
      },
      save(){
        axios.post('/drivers',this.driver)
        .then((response) => {
          this.errors = {}
          showNotify('success','Driver has been created')
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }       
        })
      },
    },
    computed: {
      mainArea(){
        return this.$store.getters.mainAreas
      }
    },

  }

</script>
