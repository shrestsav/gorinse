<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Items</h3>
            </div>
            <div class="col-4 text-right">
              <button type="button" class="btn btn-success btn-sm" @click="addBtn = !addBtn" v-if="addBtn">Add</button>
              <button type="button" class="btn btn-danger btn-sm" @click="addBtn = !addBtn" v-if="!addBtn">Cancel</button>
            </div>
          </div>
        </div>
        <create v-if="!addBtn"></create>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th>S.No.</th>
                <th>Item Name</th>
                <th>Category</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="(item,index) in items">
                <td>{{index+1}}</td>
                <td>{{item.name}}</td>
                <td>{{item.category.name}}</td>
                <td>
                  <div class="form-group" v-if="editExistingItem(item.id)">
                    <input type="number" v-model="item.price" :class="{'not-validated':errors.price}" class="form-control">
                    <div class="invalid-feedback" style="display: block;" v-if="errors.price">
                      {{errors.price[0]}}
                    </div>
                  </div>
                  <div v-else>{{item.price}}</div>
                </td>
                <td>
                  <div v-if="editExistingItem(item.id)">
                    <button type="button" class="btn btn-success btn-sm" @click="saveEditedItem">Update</button>
                    <button type="button" class="btn btn-info btn-sm" @click="cancelEditItem">Cancel</button>
                  </div>
                  <a href="javascript:;" class="table-action" title="Edit product" @click="editItem(index)" v-if="!modifyItem.edit">
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
  import create from './create.vue'

  export default{
    components: {
      create,
    },
    data(){
      return{
        item:{},
        modifyItem:{
          id: null,
          edit:false
        },
        addBtn:true,
        errors:{}
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'items')
      this.$store.commit('changeCurrentMenu', 'settingsMenu')
      this.$store.dispatch('getItems')
    },
    mounted(){
  
    },
    methods:{
      editExistingItem(edit_id){
        if(this.modifyItem.id == edit_id && this.modifyItem.edit)
          return true
        else 
          return false
      },
      editItem(key){
        this.item = this.items[key]
        this.modifyItem.id = this.items[key].id
        this.modifyItem.edit = true
      },
      cancelEditItem(){
        this.$store.dispatch('getItems')
        this.item = {}
        this.modifyItem.id = ''
        this.modifyItem.edit = false
      },
      saveEditedItem(){
        axios.patch('/items/'+this.item.id,this.item)
        .then((response) => {
          this.cancelEditItem()
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
      items(){
        return this.$store.getters.items
      }
    },
    watch: {
    },
  }

</script>
