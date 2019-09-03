<template>
  <div class="card">
    <div class="card-header">
      <div class="row align-items-center">
        <div class="col-8">
          <h5 class="h3 mb-0">Banner Offers</h5>
        </div>
        <div class="col-4 text-right">
          <button type="button" class="btn btn-info btn-sm" @click="addOffer()" v-if="module.display && addbtn">Add</button>
          <button type="button" class="btn btn-primary btn-sm" @click="toggleModule">{{module.icon}}</button>
        </div>
      </div>
    </div>
    <div class="table-responsive" v-if="module.display">
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
                <button type="button" class="btn btn-info btn-sm" @click="editOffer(key)" v-if="editbtn"><i class="far fa-edit"></i></button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
</template>


<script>
  import {settings} from '../../config/settings'
  import { mapState } from 'vuex'

  export default{
    data(){
      return{
        offer:{
          offer_url:'',
        },
        addbtn:true,
        editbtn:true,
        newOffer:false,
        modifyOrder:{
          id:'',
          edit:false,
        },
        errors:{},
      }
    },
    created(){
    },
    mounted(){
      this.$store.dispatch('getOffers')
    },
    methods:{
      status(status){
        return settings.status[status]
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
        this.editbtn = false
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
        this.editbtn = true
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
        this.addbtn = false
      },
      cancelEditOffer(){
        this.offer = {}
        this.modifyOrder.id = ''
        this.modifyOrder.edit = false
        this.addbtn = true
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
      toggleModule(){
        this.$parent.toggleModule('offers')
      }
    },
    computed: {
      module(){
        return this.$parent.modules.offers
      },
      base_url(){
        return window.location.origin
      },
      ...mapState(['offers'])
    },
  }

</script>