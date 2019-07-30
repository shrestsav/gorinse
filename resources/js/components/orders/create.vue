<template>
  <div class="card mb-4">
    <div class="card-header">
      <h3 class="mb-0">Test Page for creating orders</h3>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4">
          <div class="form-group">
            <label class="form-control-label" for="example3cols1Input">One of three cols</label>
            <input type="text" class="form-control" id="example3cols1Input" placeholder="One of three cols">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="form-control-label" for="example3cols2Input">One of three cols</label>
            <input type="text" class="form-control" id="example3cols2Input" placeholder="One of three cols">
          </div>
        </div>
        <div class="col-md-4">
          <div class="form-group">
            <label class="form-control-label" for="example3cols3Input">One of three cols</label>
            <input type="text" class="form-control" id="example3cols3Input" placeholder="One of three cols">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-sm-6 col-md-3">
          <div class="form-group">
            <label class="form-control-label" for="example4cols1Input">One of four cols</label>
            <input type="text" class="form-control" id="example4cols1Input" placeholder="One of four cols">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="form-group">
            <label class="form-control-label" for="example4cols2Input">One of four cols</label>
            <input type="text" class="form-control" id="example4cols2Input" placeholder="One of four cols">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="form-group">
            <label class="form-control-label" for="example4cols3Input">One of four cols</label>
            <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
          </div>
        </div>
        <div class="col-sm-6 col-md-3">
          <div class="form-group">
            <label class="form-control-label" for="example4cols3Input">One of four cols</label>
            <input type="text" class="form-control" id="example4cols3Input" placeholder="One of four cols">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-control-label" for="example2cols1Input">One of two cols</label>
            <input type="text" class="form-control" id="example2cols1Input" placeholder="One of two cols">
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label class="form-control-label" for="example2cols2Input">One of two cols</label>
            <input type="text" class="form-control" id="example2cols2Input" placeholder="One of two cols">
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
      this.$store.commit('changeCurrentPage', 'createOrder')
      this.$store.commit('changeCurrentMenu', 'ordersMenu')
      this.$store.dispatch('getOrders')
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