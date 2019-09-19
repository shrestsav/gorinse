<template>
  <div class="row">
    <div class="col">
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
                <th scope="col" class="sort" data-sort="name">S.No.</th>
                <th scope="col" class="sort" data-sort="name">Name</th>
                <th scope="col" class="sort" data-sort="name">Area</th>
                <th scope="col" class="sort" data-sort="budget">Email</th>
                <th scope="col" class="sort" data-sort="status">Date of Birth</th>
                <th scope="col" class="sort" data-sort="completion">Address</th>
                <th scope="col" class="sort" data-sort="completion">Contact</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in drivers">
                <td>{{++key}}</td>
                <td>{{item.fname}} {{item.lname}}</td>
                <td><span v-if="item.details">{{item.details.area_id}}</span></td>
                <td>{{item.email}}</td>
                <td><span v-if="item.details">{{item.details.dob}}</span></td>
                <td><span v-if="item.details">{{item.details.address}}</span</td>
                <td>{{item.phone}}</td>
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
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'drivers')
      this.$store.commit('changeCurrentMenu', 'driversMenu')
      this.$store.dispatch('getDrivers')
    },
    mounted(){
    },
    methods:{
    },
    computed: {
      drivers(){
        return this.$store.getters.drivers
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
</style>