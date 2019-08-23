<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="col-md-2">
            <div class="nav-wrapper">
              <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 active" id="createDriver" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Items</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th>S.No.</th>
                <th>Category</th>
                <th>Item Name</th>
                <th>Price</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="(item,index) in items">
                <td>{{index+1}}</td>
                <td>{{item.category.name}}</td>
                <td>{{item.name}}</td>
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
