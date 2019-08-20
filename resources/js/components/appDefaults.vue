<template>
  <div class="row">
    <div class="col-lg-4">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">VAT (%)</h3>
          </div>
          <div class="card-body">
            <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                          </div>
                          <input class="form-control" placeholder="Your name" type="text">
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="input-group input-group-merge">
                          <div class="input-group-prepend">
                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                          </div>
                          <input class="form-control" placeholder="Email address" type="email">
                        </div>
                      </div>
                    </div>
                  </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">Delivery Charge</h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <input type="number" class="form-control">
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">Order Time</h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <input type="number" class="form-control">
            </div>
          </div>
        </div>
      </div>
    </div>        
    <div class="col-lg-4">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">VAT (%)</h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <input type="number" class="form-control">
            </div>
          </div>
        </div>
      </div>
    </div>       
    <div class="col-lg-4">
      <div class="card-wrapper">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">VAT (%)</h3>
          </div>
          <div class="card-body">
            <div class="form-group">
              <input type="number" class="form-control">
            </div>
          </div>
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
      this.$store.commit('changeCurrentPage', 'dashboard')
      this.$store.commit('changeCurrentMenu', 'dashboardMenu')
    },
    mounted(){
    },
    methods:{
    },
    computed: {
    },
    watch: {
    },
  }

</script>

<style>
  .mx-datepicker{
    width: unset;
    display: unset;
  }
  .mx-datepicker-popup{
    top: 0 !important;
  }
  .not-validated{
    border-color: #fb6340;
  }
  .form-control .vs__dropdown-toggle {
    border: 0px !important;
  }
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
  .notification_count{
    padding: 1px 7px 6px 7px;
    border-radius: 17px;
    background: #7d8a92;
    position: relative;
    top: -7px;
  }
</style>