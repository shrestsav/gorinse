<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header border-0">
          <div class="nav-wrapper">
            <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
              <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" :class="active.type=='verified' ? 'active' : ''" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false" @click="switchCustomer('verified')">Verified Customers</a>
              </li>
              <li class="nav-item">
                <a class="nav-link mb-sm-3 mb-md-0" :class="active.type=='unverified' ? 'active' : ''" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false" @click="switchCustomer('unverified')">Unverified Customers</a>
              </li>
            </ul>
          </div>
        </div>
        <div class="card-body" v-if="active.edit">
          <h6 class="heading-small text-muted mb-4">EDIT CUSTOMER DATA</h6>
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">First Name</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                  <input v-model="customer.fname" :class="{'not-validated':errors.fname}" type="text" class="form-control">
                </div>
                <div class="invalid-feedback" style="display: block;" v-if="errors.fname">
                  {{errors.fname[0]}}
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group">
                <label class="form-control-label">Last Name</label>
                <div class="input-group input-group-merge">
                  <div class="input-group-prepend">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                  </div>
                  <input v-model="customer.lname" :class="{'not-validated':errors.lname}" type="text" class="form-control">
                </div>
                <div class="invalid-feedback" style="display: block;" v-if="errors.lname">
                  {{errors.lname[0]}}
                </div>
              </div>
            </div>
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
                <th>
                  <div class="" v-if="active.type=='unverified'">
                    <a href="javascript:;" class="btn btn-sm btn-danger" @click="deleteUnverifiedCustomers" v-if="active.selectedIds.length && active.select">Delete</a>
                    <a href="javascript:;" class="btn btn-sm btn-neutral" @click="active.select = !active.select" v-if="!active.select">Select</a>
                    <a href="javascript:;" class="btn btn-sm btn-info" @click="active.select = !active.select" v-if="active.select">Cancel</a>
                  </div>
                </th>
                <th>S.No.</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in customers.data">
                <td>
                  <div class="dropdown" v-if="!active.select">
                    <a class="btn btn-sm btn-icon-only text-light" href="javascript:;" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      <a class="dropdown-item" href="javascript:;" @click="edit(key-1)" title="Edit Customer Information">Edit Info</a>
                    </div>
                  </div>
                  <div class="custom-control custom-checkbox" v-if="active.select">
                    <input class="custom-control-input" :id="'check_'+key" type="checkbox" @change="selectCustomer(item.id,$event)">
                    <label class="custom-control-label" :for="'check_'+key"></label>
                  </div>
                </td>
                <td>{{++key}}</td>
                <td>{{item.id}}</td>
                <td>{{item.fname}} {{item.lname}}</td>
                <td>{{item.phone}}</td>
                <td>{{item.email}}</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div class="card-footer py-4" v-if="customers.data">
          <pagination v-if="active.type=='verified'" :data="customers" @pagination-change-page="getVerifiedCustomers"></pagination>
          <pagination v-if="active.type=='unverified'" :data="customers" @pagination-change-page="getUnverifiedCustomers"></pagination>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default{
    data(){
      return{
        errors:{},
        active:{
          type:'verified',
          select:false,
          selectedIds:[],
          edit:false
        },
        customers:[],
        customer:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'customers')
      this.$store.commit('changeCurrentMenu', 'customersMenu')
      this.$store.dispatch('getCustomers')
    },
    mounted(){
      this.switchCustomer(this.active.type)
    },
    methods:{
      switchCustomer(type){
        this.active.type = type
        if(type == 'verified')
          this.getVerifiedCustomers()
        else if(type == 'unverified')
          this.getUnverifiedCustomers()
      },
      getVerifiedCustomers(page = 1){
        axios.get('/customers?page='+page)
        .then(response => {
          this.customers = response.data
        })
      },
      getUnverifiedCustomers(page = 1){
        axios.get('/unverifiedCustomers?page='+page)
        .then(response => {
          this.customers = response.data
        })
      },
      selectCustomer(id,event){
        if(event.target.checked){
          if(!this.active.selectedIds.includes(id))
            this.active.selectedIds.push(id)
        }
        else if(!event.target.checked){
          if(this.active.selectedIds.includes(id)){
            var index = this.active.selectedIds.indexOf(id)
            this.active.selectedIds.splice(index, 1)
          }
        }
      },
      deleteUnverifiedCustomers(){
        var data = {
          customerIds: this.active.selectedIds
        }
        this.$swal({
          title: 'Are you sure?',
          text: 'Customer data will be destroyed',
          type: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Yes, delete it!'
        })
        .then((result) => {
          if (result.value) {
            axios.post('/deleteCustomers',data)
            .then((response) => {
              this.active.selectedIds = []
              this.switchCustomer(this.active.type)
              showNotify('success',response.data.message)
            })
            .catch((error) => {
              showNotify('danger',error.response.data.message)
            })
          }
        })
      },
      edit(key){
        this.active.edit = true
        this.customer = this.customers.data[key]
      },
      discardEdit(){
        this.active.edit = false
        this.customer = {}
      },
      updateEditedData(){
        axios.post('/deleteCustomers',data)
        .then((response) => {
          this.active.selectedIds = []
          this.switchCustomer(this.active.type)
          showNotify('success',response.data.message)
        })
        .catch((error) => {
          showNotify('danger',error.response.data.message)
        })
      }
    },
    computed: {
      // customers(){
      //   return this.$store.getters.customers
      // }
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