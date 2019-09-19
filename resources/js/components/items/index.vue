<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">Items</h3>
            </div>
          </div>
        </div>
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
                <td>{{item.price}}</td>
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
      this.$store.commit('changeCurrentPage', 'items')
      this.$store.commit('changeCurrentMenu', 'settingsMenu')
      this.$store.dispatch('getItems')
    },
    mounted(){
  
    },
    methods:{
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
