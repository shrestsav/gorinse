<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Drivers List</h3>
            </div>
          </div>
        </div>
        <div class="card-body" v-if="active.edit">
          <h6 class="heading-small text-muted mb-4">EDIT DRIVER DATA</h6>
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
          <div class="float-right">
            <button type="button" class="btn btn-success btn-sm" @click="updateEditedData()">Update</button>
            <button type="button" class="btn btn-danger btn-sm" @click="discardEdit()">Cancel</button>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th></th>
                <th>S.No.</th>
                <th>Name</th>
                <th>Area</th>
                <th>Email</th>
                <th>Date of Birth</th>
                <th>Address</th>
                <th>Contact</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in drivers.data">
                <td>
                  <div class="dropdown">
                    <a class="btn btn-sm btn-icon-only text-light" href="javascript:;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      <a class="dropdown-item" href="javascript:;" @click="edit(key-1)" title="Edit Driver Information">Edit Info</a>
                    </div>
                  </div>
                </td>
                <td>{{++key}}</td>
                <td>{{item.fname}} {{item.lname}}</td>
                <td><span v-if="item.details">{{item.details.area_id}}</span></td>
                <td>{{item.email}}</td>
                <td><span v-if="item.details">{{item.details.dob}}</span></td>
                <td><span v-if="item.details">{{item.details.address}}</span</td>
                <td>{{item.phone}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer py-4" v-if="drivers.data">
          <pagination :data="drivers" @pagination-change-page="getDrivers"></pagination>
        </div>
      </div>
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
        errors:{},
        active:{
          page:'',
          select:false,
          selectedIds:[],
          edit:false
        },
        driver:{},
        drivers:[],
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'drivers')
      this.$store.commit('changeCurrentMenu', 'driversMenu')
      this.getDrivers()
      this.defSettings()
    },
    mounted(){
    },
    methods:{
      defSettings(){
        axios.get('/getFields/createUser').then(response => this.fields = response.data)
        this.$store.dispatch('getMainAreas')
      },
      getDrivers(page = 1){
        this.active.page = page
        axios.get('/drivers?page='+page)
        .then(response => {
          this.drivers = response.data
        })
      },
      edit(key){
        this.active.edit = true
        this.driver = this.drivers.data[key]
      },
      discardEdit(){
        this.active.edit = false
        this.driver = {}
      },
      updateEditedData(){
        axios.patch('/drivers/'+this.driver.id, this.driver)
        .then((response) => {
          this.discardEdit()
          this.getDrivers(this.active.page)
          showNotify('success',response.data.message)
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
      mainArea(){
        return this.$store.getters.mainAreas
      }
    },
  }

</script>

<style type="text/css">
  .table td, .table th {
    padding: 0.8rem;
  }
  .nav-pills .nav-item:not(:last-child) {
    padding-right: unset;
  }
  .nav-pills .nav-link {
    border-radius: unset;
  }
  .nav-wrapper{
    padding: unset;
  }
  .status_count{
    padding: 2px 7px;
    border-radius: 17px;
    background: #F44336;
    position: relative;
    left: 25px;
    color: white;
  }
</style>