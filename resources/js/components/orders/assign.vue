<template>
  <div class="modal fade" id="assignOrder" tabindex="-1" role="dialog" aria-labelledby="add_staffs_modal" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h6 class="modal-title" id="modal-title-default">Assign Order</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="myBtn">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          <!-- <div class="row">
            <div class="col-md-6">
              <h4 class="mb-0">
                <a href="javascript:;">{{order.customer.name}}</a>
              </h4>
              <span class="text-success">●</span>
              <small>Online</small>
            </div>
            <div class="col-md-6">
              <h4 class="mb-0">
                <a href="javascript:;">Items</a>
              </h4>
              <span class="text-success"></span><small>Dummy</small>
              <span class="text-success"></span><small>Dummy</small>
            </div>
          </div> -->
          <div class="row">
            <div class="col-md-12">
              <h4 class="mb-1">
                <a href="javascript:;">Assign to</a>
              </h4>
              <v-select
                class="form-control"  
                v-model="assign.driver_id" 
                :options="drivers" 
                :reduce="fname => fname.id"
                label="fname" 
                placeholder="Drivers"
              />
            </div>
          </div>
          <div class="invalid-feedback" style="display: block;">
           <!--  {{errors.driver}} -->
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
          <button class="btn btn-outline-success" @click="setAssign()">Create</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import vSelect from 'vue-select'
  import 'vue-select/dist/vue-select.css'
    
  export default{
    components: {
      vSelect
    },
    props: ['active'],
    data(){
      return{
        assign:{},
        showErr:false,
      }
    },
    mounted(){
      this.$store.dispatch('getDrivers')
    },
    computed: {
      order(){
        return this.$store.getters.orders.data[this.active.order]
      },
      drivers(){
        return this.$store.getters.drivers
      },
    },
    methods:{
      setAssign(){
        this.assign.order_id = this.order.id
        this.assign.type = this.active.type

        axios.post('/assignOrder',this.assign)
        .then((response) => {
          this.assign = {}
          this.$store.dispatch('getOrders',this.active)
          showNotify('success',response.data)
        })
        .catch((error) => {
        })
      }
    },
}

</script>