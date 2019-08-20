<template>
       <div class="row">
        <div class="col-lg-12">
          <div class="card-wrapper">
            <div class="col-lg-6">
              <div class="card">
                <div class="card-header">
                  <h3 class="mb-0">Form controls</h3>
                </div>
                <div class="card-body">
                  <form>
                    <div class="form-group">
                      <label class="form-control-label" for="exampleFormControlInput1">Email address</label>
                      <input type="email" class="form-control" id="exampleFormControlInput1" placeholder="name@example.com">
                    </div>
                  </form>
                </div>
              </div>
            </div>
            <div class="col-lg-4">
              <div class="card">
                <!-- Card header -->
                <div class="card-header">
                  <h3 class="mb-0">HTML5 inputs</h3>
                </div>
                <!-- Card body -->
                <div class="card-body">
                  <form>
                    <div class="form-group row">
                      <label for="example-text-input" class="col-md-2 col-form-label form-control-label">Text</label>
                      <div class="col-md-10">
                        <input class="form-control" type="text" value="John Snow" id="example-text-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-search-input" class="col-md-2 col-form-label form-control-label">Search</label>
                      <div class="col-md-10">
                        <input class="form-control" type="search" value="Tell me your secret ..." id="example-search-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-email-input" class="col-md-2 col-form-label form-control-label">Email</label>
                      <div class="col-md-10">
                        <input class="form-control" type="email" value="argon@example.com" id="example-email-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-url-input" class="col-md-2 col-form-label form-control-label">URL</label>
                      <div class="col-md-10">
                        <input class="form-control" type="url" value="https://www.creative-tim.com" id="example-url-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-tel-input" class="col-md-2 col-form-label form-control-label">Phone</label>
                      <div class="col-md-10">
                        <input class="form-control" type="tel" value="40-(770)-888-444" id="example-tel-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-password-input" class="col-md-2 col-form-label form-control-label">Password</label>
                      <div class="col-md-10">
                        <input class="form-control" type="password" value="password" id="example-password-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-number-input" class="col-md-2 col-form-label form-control-label">Number</label>
                      <div class="col-md-10">
                        <input class="form-control" type="number" value="23" id="example-number-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-datetime-local-input" class="col-md-2 col-form-label form-control-label">Datetime</label>
                      <div class="col-md-10">
                        <input class="form-control" type="datetime-local" value="2018-11-23T10:30:00" id="example-datetime-local-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-date-input" class="col-md-2 col-form-label form-control-label">Date</label>
                      <div class="col-md-10">
                        <input class="form-control" type="date" value="2018-11-23" id="example-date-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-month-input" class="col-md-2 col-form-label form-control-label">Month</label>
                      <div class="col-md-10">
                        <input class="form-control" type="month" value="2018-11" id="example-month-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-week-input" class="col-md-2 col-form-label form-control-label">Week</label>
                      <div class="col-md-10">
                        <input class="form-control" type="week" value="2018-W23" id="example-week-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-time-input" class="col-md-2 col-form-label form-control-label">Time</label>
                      <div class="col-md-10">
                        <input class="form-control" type="time" value="10:30:00" id="example-time-input">
                      </div>
                    </div>
                    <div class="form-group row">
                      <label for="example-color-input" class="col-md-2 col-form-label form-control-label">Color</label>
                      <div class="col-md-10">
                        <input class="form-control" type="color" value="#5e72e4" id="example-color-input">
                      </div>
                    </div>
                  </form>
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