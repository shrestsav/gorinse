<template>
  <div class="row">
    <div class="col-lg-12">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                <h3 class="mb-0">General</h3>
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-primary btn-sm" @click="toggleModule('general')">{{modules.general.icon}}</button>
              </div>
            </div>
          </div>
          <div class="card-body" v-if="modules.general.display">
            <div class="row">
              <div class="col-md-4">
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
              <div class="col-md-4">
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
              <div class="col-md-4">
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
            </div>
            <div class="float-right">
              <button class="btn btn-outline-primary" @click="save('generalSetting')">Save</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h5 class="h3 mb-0">Banner Offers</h5>
              </div>
              <div class="col-4 text-right">
                <button type="button" class="btn btn-info btn-sm" @click="addOffer()" v-if="modules.offers.display">Add Offer</button>
                <button type="button" class="btn btn-primary btn-sm" @click="toggleModule('offers')">{{modules.offers.icon}}</button>
              </div>
            </div>
          </div>
          <div class="table-responsive" v-if="modules.offers.display">
            <table class="table align-items-center table-flush">
              <thead class="thead-light">
                <tr>
                  <th>S.No</th>
                  <th>Image</th>
                  <th>Title</th>
                  <th>Description</th>
                  <th>Status</th>
                  <th>Action</th>
                </tr>
              </thead>
              <tbody>
                <!-- For adding new Offers -->
                <tr v-if="newOffer">
                  <td></td>
                  <td>
                    <div class="banner_images">
                      <img :src="offer.offer_url" class="img-center img-fluid" style="height: 200px;">
                    </div>
                    <div class="custom-file" style="width: 274px; ">
                      <input type="file" class="custom-file-input" lang="en" ref="offerFile" v-on:change="offerFileUpload()" :class="{'not-validated':errors.offer_image}">
                      <label class="custom-file-label">Offer Image</label>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.offer_image">
                        {{errors.offer_image[0]}}
                      </div>
                    </div>  
                  </td>
                  <td>
                    <div class="form-group">
                      <input v-model="offer.offer_name" :class="{'not-validated':errors.offer_name}"  type="text" class="form-control" placeholder="OFFER TITLE" style="height: 250px;">
                      <div class="invalid-feedback" style="display: block;" v-if="errors.offer_name">
                        {{errors.offer_name[0]}}
                      </div>
                    </div>
                  </td>
                  <td>
                    <div class="form-group">
                      <textarea v-model="offer.offer_description" :class="{'not-validated':errors.offer_description}" class="form-control" rows="8" placeholder="BRIEF DESCRIPTION OF OFFER" style="height: 250px;"></textarea>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.offer_description">
                        {{errors.offer_description[0]}}
                      </div>
                    </div>
                  </td>
                  <td>
                    <select class="form-control" v-model="offer.status">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </td>
                  <td>
                    <button type="button" class="btn btn-success btn-sm" @click="saveOffer()">+</button>
                    <button type="button" class="btn btn-danger btn-sm" @click="discardOffer()">-</button>
                  </td>
                </tr>
                <!-- For Old Order List -->
                <tr v-for="item,key in offers">
                  <td>{{key+1}}</td>
                  <td>
                    <div v-if="editExistingOrder(item['id'])">
                      <div class="banner_images">
                        <img :src="offer.offer_url" class="img-center img-fluid" style="height: 200px;">
                      </div>
                      <div class="custom-file" style="width: 274px; ">
                        <input type="file" class="custom-file-input" lang="en" v-on:change="editOfferImage" :class="{'not-validated':errors.offer_image}">
                        <label class="custom-file-label">Offer Image</label>
                        <div class="invalid-feedback" style="display: block;" v-if="errors.offer_image">
                          {{errors.offer_image[0]}}
                        </div>
                      </div>
                    </div> 
                    <div class="banner_images" v-else>
                      <img :src="base_url+'/files/offer_banners/'+item['image']" class="img-center img-fluid" style="height: 200px;">
                    </div>
                  </td>
                  <td>
                    <div class="form-group" v-if="editExistingOrder(item['id'])">
                      <input v-model="offer.name" :class="{'not-validated':errors.name}"  type="text" class="form-control" placeholder="OFFER TITLE" style="height: 180px;">
                      <div class="invalid-feedback" style="display: block;" v-if="errors.name">
                        {{errors.name[0]}}
                      </div>
                    </div>
                    <b v-else>{{item['name']}}</b>
                  </td>
                  <td>
                    <div class="form-group" v-if="editExistingOrder(item['id'])">
                      <textarea v-model="offer.description" :class="{'not-validated':errors.description}" class="form-control" rows="7" placeholder="BRIEF DESCRIPTION OF OFFER" style="height: 180px;"></textarea>
                      <div class="invalid-feedback" style="display: block;" v-if="errors.description">
                        {{errors.description[0]}}
                      </div>
                    </div>
                    <div v-else>{{item['description']}}</div>
                  </td>
                  <td>
                    <select class="form-control" v-model="offer.status" v-if="editExistingOrder(item['id'])">
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                    <select class="form-control" v-model="item.status" @change="changeOfferStatus(key)" v-else>
                      <option value="1">Active</option>
                      <option value="0">Inactive</option>
                    </select>
                  </td>
                  <td>
                    <div v-if="editExistingOrder(item['id'])">
                      <button type="button" class="btn btn-success btn-sm" @click="saveEditedOffer">Edit</button>
                      <button type="button" class="btn btn-info btn-sm" @click="cancelEditOffer()">Cancel</button>
                    </div>
                    <div v-else>
                      <button type="button" class="btn btn-danger btn-sm" @click="deleteOffer(item.id)"><i class="far fa-trash-alt"></i></button>
                      <button type="button" class="btn btn-info btn-sm" @click="editOffer(key)"><i class="far fa-edit"></i></button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
        <coupons></coupons>
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                <h3 class="mb-0">Support Page</h3>
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-primary btn-sm" @click="toggleModule('supportPage')">{{modules.supportPage.icon}}</button>
              </div>
            </div>
          </div>
          <div class="card-body" v-if="modules.supportPage.display">
            <div class="row">
              <div class="col-md-4">
                <!-- <label class="form-control-label text-center">Company Logo</label> -->
                <img :src="appDefaults.company_logo_url" class="img-center img-fluid" style="height: 109px;">
                <br>
                <div class="custom-file">
                  <input type="file" class="custom-file-input" lang="en" ref="file" v-on:change="logoFileUpload()">
                  <label class="custom-file-label">Company Logo</label>
                </div>                
              </div>
              <div class="col-md-8">
                <div class="row">
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Company Email</label>
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                        </div>
                        <input class="form-control" type="text" v-model="appDefaults.company_email">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">FAQ Link</label>
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-user"></i></span>
                        </div>
                        <input class="form-control" type="text" v-model="appDefaults.FAQ_link">
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Online Chat</label>
                      <div class="row">
                        <div class="col-md-5">
                          <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-clock"></i></span>
                            </div>
                            <input class="form-control" type="text" v-model="appDefaults.online_chat.time">
                          </div>
                        </div>
                        <div class="col-md-7">
                          <div class="input-group input-group-merge">
                            <div class="input-group-prepend">
                              <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                            </div>
                            <input class="form-control" type="text" v-model="appDefaults.online_chat.url">
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-6">
                    <div class="form-group">
                      <label class="form-control-label">Hotline Contact No</label>
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-phone"></i></span>
                        </div>
                        <input class="form-control" type="text" v-model="appDefaults.hotline_contact">
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="float-right">
              <button class="btn btn-outline-primary" @click="save('supportSetting')">Save</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                <h3 class="mb-0">Order</h3>
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-primary btn-sm" @click="toggleModule('order')">{{modules.order.icon}}</button>
              </div>
            </div>
          </div>
          <div class="card-body" v-if="modules.order.display">
            <div class="row">
              <div class="col-md-12">
                <label class="form-control-label">Order Active Hours</label>
                <div class="row">
                  <div class="col-md-2" v-for="timerange,key in appDefaults.order_time">
                    <div class="form-group">
                      <div class="input-group input-group-merge" >
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-clock"></i></span>
                        </div>
                        <input class="form-control" type="text"  v-model="appDefaults.order_time[key]">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-primary btn-sm" @click="addTime()">Add Time</button>
                </div>
              </div>
              <br><br><br>
              <div class="col-md-12">
                <label class="form-control-label">Driver Predefined Notes</label>
                <div class="row">
                  <div class="col-md-4" v-for="note,key in appDefaults.driver_notes">
                    <div class="form-group">
                      <div class="input-group input-group-merge">
                        <div class="input-group-prepend">
                          <span class="input-group-text"><i class="fas fa-sticky-note"></i></span>
                        </div>
                        <input class="form-control" type="text" v-model="appDefaults.driver_notes[key]">
                      </div>
                    </div>
                  </div>
                </div>
                <div class="text-center">
                  <button type="button" class="btn btn-primary btn-sm" @click="addDriverNotes()">Add Notes</button>
                </div>
              </div>
            </div>
            <div class="float-right">
              <button class="btn btn-outline-primary" @click="save('orderSetting')">Save</button>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <div class="row">
              <div class="col">
                <h3 class="mb-0">Main Areas</h3>
              </div>
              <div class="col-auto">
                <button type="button" class="btn btn-primary btn-sm" @click="toggleModule('mainArea')">{{modules.mainArea.icon}}</button>
              </div>
            </div>
          </div>
          <div class="card-body" v-if="modules.mainArea.display">
            <div class="row">
              <div class="bootstrap-tagsinput">
                <span class="tag badge badge-primary" v-for="item in mainAreas">
                  {{item.name}}
                  <span data-role="remove" @click="deleteArea(item.id)"></span>
                </span>
              </div>
            </div>
            <br>
            <div class="row">
              <div class="col-md-12">
                <div class="form-group">
                  <label class="form-control-label">Area Name</label>
                  <div class="input-group input-group-merge">
                    <div class="input-group-prepend">
                      <span class="input-group-text"><i class="fas fa-map-marker"></i></span>
                    </div>
                    <input :class="{'not-validated':errors.name}" class="form-control" type="text" v-model="mainArea.name">
                  </div>
                  <div class="invalid-feedback" style="display: block;" v-if="errors.name">
                    {{errors.name[0]}}
                  </div>
                </div>
              </div>
            </div>
            <div class="float-right">
              <button class="btn btn-outline-primary" @click="saveMainArea()">Create</button>
            </div>
          </div>
        </div>
      </div>
    </div>        
  </div>
</template>

<script>
  import coupons from './coupons.vue'

  export default{
    components: {
     coupons
    },
    data(){
      return{
        modules:{
          general : {
            display : false,
            icon : "+",
          },
          coupon : {
            display : true,
            icon : "+",
          },
          supportPage : {
            display : false,
            icon : "+",
          },
          order : {
            display : false,
            icon : "+",
          },
          mainArea : {
            display : false,
            icon : "+",
          },
          offers : {
            display : false,
            icon : "+",
          },
        },
        offer:{
          offer_url:'',
        },
        newOffer:false,
        modifyOrder:{
          id:'',
          edit:false,
        },
        mainArea:{},
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'appDefaults')
      this.$store.commit('changeCurrentMenu', 'appDefaultsMenu')
      this.$store.dispatch('getAppDefaults')
      this.$store.dispatch('getMainAreas')
      this.$store.dispatch('getOffers')
    },
    mounted(){
      
    },
    methods:{
      save(part){
        let formData = new FormData()

        formData.append('saveType', part)
        for (var key in this.appDefaults) {
            formData.append(key, this.appDefaults[key]);
        }
        formData.append('order_time',JSON.stringify(this.appDefaults.order_time))
        formData.append('online_chat',JSON.stringify(this.appDefaults.online_chat))
        formData.append('driver_notes',JSON.stringify(this.appDefaults.driver_notes))
        
        axios.post('/appDefaults',formData)
          .then((response) => {
            console.log(response)
            showNotify('success','App Default has been created')
          })
          .catch((error) => {
            for (var prop in error.response.data.errors) {
              showNotify('danger',error.response.data.errors[prop])
            }  
          })
      },
      saveMainArea(){
        axios.post('/mainArea',this.mainArea)
        .then((response) => {
          console.log(response.data)
          this.errors ={}
          this.mainArea = {}
          this.$store.dispatch('getMainAreas')
          showNotify('success',response.data)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      deleteArea(id){
        this.$swal({
          title: 'Are you sure?',
          text: "This may mess things up for customers!",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            axios.delete('/mainArea/'+id)
            .then((response) => {
              this.$store.dispatch('getMainAreas')
              showNotify('success',response.data)
            })
            .catch((error) => {  
              showNotify('danger',error.response.data.errors)
            })
          }
        })
      },
      logoFileUpload(){
        this.appDefaults.logoFile = this.$refs.file.files[0];
        this.appDefaults.company_logo_url = URL.createObjectURL(this.appDefaults.logoFile);
      },
      addTime(){
        if(this.appDefaults.order_time[this.appDefaults.order_time.length-1]!="")
          this.appDefaults.order_time.push("")
        else
          this.$swal({
            type: 'error',
            title: 'First Fill Empty Rows',
          });
      },
      addDriverNotes(){
        if(this.appDefaults.driver_notes[this.appDefaults.driver_notes.length-1]!="")
          this.appDefaults.driver_notes.push("")
        else
          this.$swal({
            type: 'error',
            title: 'First Fill Empty Rows',
          });
      },

      //Offer Methods

      addOffer(){
        this.offer = {
          'offer_name' : '',
          'offer_image': '',
          'offer_description': '',
          'status': 0,
          'offer_url':''
        }
        this.newOffer = true
      },
      offerFileUpload(){
        this.offer.offer_image = this.$refs.offerFile.files[0]
        this.offer.offer_url = URL.createObjectURL(this.offer.offer_image)
      },
      editOfferImage(e){
        this.offer.offer_image = e.target.files[0]
        this.offer = Object.assign({}, this.offer, { offer_url: URL.createObjectURL(this.offer.offer_image) })
      },
      saveOffer(){
        let offerForm = new FormData()

        for (var key in this.offer) {
            offerForm.append(key, this.offer[key]);
        }
        
        axios.post('/offers',offerForm)
        .then((response) => {
          this.$store.dispatch('getOffers')
          this.offer = {}
          this.newOffer = false
          showNotify('success',response.data)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      saveEditedOffer(){
        let offerForm = new FormData()

        for (var key in this.offer) {
            offerForm.append(key, this.offer[key]);
        }
        
        axios.post('/offers/edit/'+this.offer.id,offerForm)
        .then((response) => {
          this.$store.dispatch('getOffers')
          this.offer = {}
          this.modifyOrder.id = ''
          this.modifyOrder.edit = true
          showNotify('success',response.data)
        })
        .catch((error) => {
          this.errors = error.response.data.errors
          for (var prop in error.response.data.errors) {
            showNotify('danger',error.response.data.errors[prop])
          }  
        })
      },
      discardOffer(){
        this.offer = {}
        this.newOffer = false
      },
      deleteOffer(id){
        this.$swal({
          title: 'Are you sure?',
          text: "You may not undo this",
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
          if (result.value) {
            axios.delete('/offers/'+id)
            .then((response) => {
              this.$store.dispatch('getOffers')
              showNotify('success',response.data)
            })
            .catch((error) => {  
              showNotify('danger',error.response.data.errors)
            })
          }
        })
      },
      editOffer(key){
        this.offer = this.offers[key]
        this.offer.offer_url = this.base_url+'/files/offer_banners/'+this.offer.image

        this.modifyOrder.id = this.offers[key].id
        this.modifyOrder.edit = true
      },
      cancelEditOffer(){
        this.offer = {}
        this.modifyOrder.id = ''
        this.modifyOrder.edit = false
      },
      editExistingOrder(edit_id){
        if(this.modifyOrder.id == edit_id && this.modifyOrder.edit)
          return true
        else 
          return false
      },
      changeOfferStatus(key){
        this.$swal({
          title: 'Are you sure?',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes'
        }).then((result) => {
          if (result.value) {
            axios.post('/changeOfferStatus/',this.offers[key])
            .then((response) => {
              this.$store.dispatch('getOffers')
              showNotify('success',response.data)
            })
            .catch((error) => {  
              showNotify('danger',error.response.data.errors)
            })
          }
        })
      },
      toggleModule(module){
        if(this.modules[module].display){
          this.modules[module].display = false
          this.modules[module].icon = "+"
        }
        else{
          this.modules[module].display = true
          this.modules[module].icon = "-"
        }
        for (var mod in this.modules) {
          if(mod!=module){
            this.modules[mod].display = false
            this.modules[mod].icon = "+"
          }
        }
      }
    },
    computed: {
      appDefaults(){
        return this.$store.getters.appDefaults
      },
      mainAreas(){
        return this.$store.getters.mainAreas
      },
      offers(){
        return this.$store.getters.offers
      },
      base_url(){
        return window.location.origin
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
  .notification_count{
    padding: 1px 7px 6px 7px;
    border-radius: 17px;
    background: #7d8a92;
    position: relative;
    top: -7px;
  }
</style>