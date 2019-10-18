<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Services</h3>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th>S.No.</th>
                <th>Service Name</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="(item,index) in services">
                <td>{{index+1}}</td>
                <td>{{item.name}}</td>
                <td>
                  <div class="form-group" v-if="editExistingService(item.id)">
                    <input type="number" v-model="service.price" :class="{'not-validated':errors.price}" class="form-control">
                    <div class="invalid-feedback" style="display: block;" v-if="errors.price">
                      {{errors.price[0]}}
                    </div>
                  </div>
                  <div v-else>{{item.price}}</div>
                </td>
                <td>
                  <div v-if="editExistingService(item.id)">
                    <button type="button" class="btn btn-success btn-sm" @click="saveEditedService">Update</button>
                    <button type="button" class="btn btn-info btn-sm" @click="cancelEditService">Cancel</button>
                  </div>
                  <a href="javascript:;" class="table-action" title="Edit product" @click="editService(index)" v-if="!modifyService.edit">
                    <i class="fas fa-user-edit"></i>
                  </a>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  export default{
    data(){
      return{
        service:{},
        modifyService:{
          id: null,
          edit:false
        },
        errors:{}
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'services')
      this.$store.commit('changeCurrentMenu', 'settingsMenu')
      this.$store.dispatch('getServices')
    },
    mounted(){
  
    },
    methods:{
      editExistingService(edit_id){
        if(this.modifyService.id == edit_id && this.modifyService.edit)
          return true
        else 
          return false
      },
      editService(key){
        this.service = this.services[key]
        this.modifyService.id = this.services[key].id
        this.modifyService.edit = true
      },
      cancelEditService(){
        this.$store.dispatch('getServices')
        this.service = {}
        this.modifyService.id = ''
        this.modifyService.edit = false
      },
      saveEditedService(){
        axios.patch('/services/'+this.service.id,this.service)
        .then((response) => {
          this.cancelEditService()
          showNotify('success',response.data)
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
      services(){
        return this.$store.getters.services
      }
    },
    watch: {
    },
  }

</script>
<style type="text/css" scoped>
  .form-group {
    margin-bottom: unset; 
  }
</style>