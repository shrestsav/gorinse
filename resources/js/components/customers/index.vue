<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="row align-items-center">
            <div class="col-8">
              <h3 class="mb-0">List of Customers</h3>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th>S.No.</th>
                <th>User ID</th>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <!-- <th>Date of Birth</th>
                <th>Address</th> -->
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in customers">
                <td>{{++key}}</td>
                <td>{{item.id}}</td>
                <td>{{item.fname}} {{item.lname}}</td>
                <td>{{item.phone}}</td>
                <td>{{item.email}}</td>
                <!-- <td><span v-if="item.details">{{item.details.dob}}</span></td>
                <td><span v-if="item.details">{{item.details.address}}</span></td> -->
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
      this.$store.commit('changeCurrentPage', 'customers')
      this.$store.commit('changeCurrentMenu', 'customersMenu')
      this.$store.dispatch('getCustomers')
    },
    mounted(){
    },
    methods:{
    },
    computed: {
      customers(){
        return this.$store.getters.customers
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