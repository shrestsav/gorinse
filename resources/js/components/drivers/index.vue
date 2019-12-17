<template>
  <div class="row">
    <div class="col">
      <div class="card" v-if="active.edit">
        <div class="row justify-content-center">
          <div class="col-lg-3 order-lg-2">
            <div class="card-profile-image">
              <a href="#">
                <img :src="imageUrl()" class="rounded-circle" @click="triggerDPInput">
                <input type="file" class="custom-file-input" lang="en" v-on:change="imageChange" style="display: none;" ref="dp_photo">
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
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
      </div>
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Drivers List</h3>
            </div>
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
                      <a class="dropdown-item" href="javascript:;" @click="driverOrders(item.id)" title="Edit Driver Information">View Orders</a>
                    </div>
                  </div>
                </td>
                <td>{{++key}}</td>
                <td>{{item.full_name}}</td>
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
      <b-modal id="driverOrders" ref="driverOrders" title="Orders" hide-footer hide-header>
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-4">
                <h3 class="mb-0 text-black">Orders</h3>
              </div>
              <div class="col-5">
                
              </div>
              <div class="col-3 text-right">
                <a :href="origin_url+'/reports/export?report=driverOrders&driver_id='+active.driver_id" target="_blank"><button type="button" class="btn btn-success btn-sm">Export <i class="fas fa-file-excel"></i></button></a>
              </div>
            </div>
          </div>
          <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th>S.No.</th>
                <th>Order No</th>
                <th>Ordered Date</th>
                <th>Pick Date</th>
                <th>Drop Date</th>
                <th>Client</th>
                <th>Area</th>
                <th>Status</th>
                <th>Amount</th>
                <th>Type</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in orders.data">
                <td>{{++key}}</td>
                <td>{{item.id}}</td>
                <td>{{dateTime(item.created_at)}}</td>
                <td>{{dateTime(item.details.PFC)}}</td>
                <td>{{dateTime(item.details.DAO)}}</td>
                <td>{{item.customer.full_name}}</td>
                <td><span v-if="item.pick_location_details && item.pick_location_details.main_area">{{item.pick_location_details.main_area.name}}</span></td>
                <td>{{orderStatus[item.status]}}</td>
                <td><template v-if="item.total_amount">AED {{item.total_amount}}</template><template v-else>Pending</template></td>
                <td>
                  <template v-if="item.driver_id==active.driver_id && item.drop_driver_id==active.driver_id">
                    Pick and Drop
                  </template>
                  <template v-else="item.driver_id==active.driver_id && item.drop_driver_id!=active.driver_id">
                    Pick
                  </template>
                  <template v-else="item.driver_id!=active.driver_id && item.drop_driver_id==active.driver_id">
                    Drop
                  </template>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        </div>
      </b-modal>
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
          driver_id:'',
          page:'',
          select:false,
          selectedIds:[],
          edit:false
        },
        driver:{},
        drivers:[],
        orders:[],
        orderStatus:[],
        origin_url: window.location.origin
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
      dateTime(date){
        if(date){
          var date = new Date(date+' UTC')
          return this.$moment(date).format("ddd MMM DD YYYY [at] HH:mm A")
        }
        else
          return ' - '
      },
      hideModal() {
        this.$refs['driverOders'].hide()
        this.active.driver_id = ''
      },
      driverOrders(id){
        axios.get('/driver/orders/'+id)
        .then((response) => {
          this.active.driver_id = id
          this.orders = response.data.orders
          this.orderStatus = response.data.orderStatus
          this.$refs['driverOrders'].show()
        })
        
      },
      edit(key){
        this.active.edit = true
        this.driver = this.drivers.data[key]
      },
      discardEdit(){
        this.active.edit = false
        this.driver = {}
        this.getDrivers(this.active.page)
      },
      updateEditedData(){
        let formData = new FormData()
        for (var key in this.driver) {
          if(this.driver[key])
            formData.append(key, this.driver[key])
        }
        formData.append('_method', 'patch')
        axios.post('/drivers/'+this.driver.id, formData)
        .then((response) => {
          this.discardEdit()
          showNotify('success',response.data.message)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      triggerDPInput(){
        this.$refs.dp_photo.click()
      },
      imageChange(e){
        this.driver.photo_file = e.target.files[0]
        this.driver.photo = URL.createObjectURL(this.driver.photo_file)
      },
      imageUrl(){
        return window.location.origin + '/files/users/' + this.driver.id +'/' + this.driver.photo
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
  #driverOrders .modal-dialog{
    max-width: 90%;
  }
</style>