<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Categories</h3>
            </div>
          </div>
        </div>
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
                <td>{{item.description}}</td>
                <td></td>
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
