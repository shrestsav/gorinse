<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="col-md-2">
            <div class="nav-wrapper">
              <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
                <li class="nav-item">
                  <a class="nav-link mb-sm-3 mb-md-0 active" id="createDriver" data-toggle="tab" href="" role="tab" aria-controls="tabs-icons-text-1" aria-selected="false">Drivers</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col" class="sort" data-sort="name">S.No.</th>
                <th scope="col" class="sort" data-sort="name">Name</th>
                <th scope="col" class="sort" data-sort="budget">Email</th>
                <th scope="col" class="sort" data-sort="status">Date of Birth</th>
                <th scope="col" class="sort" data-sort="completion">Address</th>
                <th scope="col" class="sort" data-sort="completion">Contact</th>
              </tr>
            </thead>
            <tbody class="list">
              <tr v-for="item,key in drivers">
                <td>{{++key}}</td>
                <td>{{item.name}}</td>
                <td>{{item.email}}</td>
                <td>{{item.d_o_b}}</td>
                <td>{{item.address}}</td>
                <td>{{item.contact}}</td>
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