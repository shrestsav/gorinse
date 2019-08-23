<template>
 <div class="row">
    <div class="col-xl-12">
      <div class="card bg-primary">
        <div class="card-body">
          <div class="text-center">
            <h5 class="h3 text-white mb-0">WELCOME TO THE DASHBOARD OF GO-RINSE</h5>
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
  .modal-fullscreen{
    max-width: unset;
    width: 90%;
  }
  body{
    zoom:90%;
  }
  .modal-backdrop {
      width: 100%;
      height: 100%;
  }

</style>