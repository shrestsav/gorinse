<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Categories</h3>
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
                <th>Category Name</th>
                <th>Description</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="(item,index) in categories">
                <td>{{index+1}}</td>
                <td>{{item.name}}</td>
                <td>
                  <div class="form-group" v-if="editExistingCategory(item.id)">
                    <textarea v-model="category.description" :class="{'not-validated':errors.description}" class="form-control" rows="3" placeholder="BRIEF DESCRIPTION OF CATEGORY"></textarea>
                    <div class="invalid-feedback" style="display: block;" v-if="errors.description">
                      {{errors.description[0]}}
                    </div>
                  </div>
                  <div v-else>{{item.description}}</div>
                </td>
                <td>
                  <div v-if="editExistingCategory(item.id)">
                    <button type="button" class="btn btn-success btn-sm" @click="saveEditedCategory">Update</button>
                    <button type="button" class="btn btn-info btn-sm" @click="cancelEditCategory">Cancel</button>
                  </div>
                  <a href="javascript:;" class="table-action" title="Edit Category" @click="editCategory(index)" v-if="!modifyCategory.edit">
                    <i class="fas fa-user-edit"></i>
                  </a>
                  <a href="javascript:;" class="table-action" title="Delete New Category" @click="deleteNewCategory(item.id)" v-if="!modifyCategory.edit && item.can_delete">
                    <i class="fas fa-trash"></i>
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
        category:{},
        modifyCategory:{
          id: null,
          edit:false
        },
        addBtn:true,
        errors:{}
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'categories')
      this.$store.commit('changeCurrentMenu', 'settingsMenu')
      this.$store.dispatch('getCategories')
    },
    mounted(){
  
    },
    methods:{
      editExistingCategory(edit_id){
        if(this.modifyCategory.id == edit_id && this.modifyCategory.edit)
          return true
        else 
          return false
      },
      editCategory(key){
        this.category = this.categories[key]
        this.modifyCategory.id = this.categories[key].id
        this.modifyCategory.edit = true
      },
      deleteNewCategory(id){
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
            axios.delete('/categories/'+id)
            .then((response) => {
              this.$store.dispatch('getCategories')
              showNotify('success',response.data.message)
            })
            .catch((error) => {  
              showNotify('danger',error.response.data.message)
            })
          }
        })
      },
      cancelEditCategory(){
        this.$store.dispatch('getCategories')
        this.category = {}
        this.modifyCategory.id = ''
        this.modifyCategory.edit = false
      },
      saveEditedCategory(){
        axios.patch('/categories/'+this.category.id,this.category)
        .then((response) => {
          this.cancelEditCategory()
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
      categories(){
        return this.$store.getters.categories
      }
    },
    watch: {
    },
  }

</script>
