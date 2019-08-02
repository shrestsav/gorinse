<template>
  <div class="modal fade" id="assignOrder" tabindex="-1" role="dialog" aria-labelledby="add_staffs_modal" aria-hidden="true">
    <div class="modal-dialog modal- modal-dialog-centered modal- modal-fullscreen" role="document">
      <div class="modal-content">
        <div class="modal-header text-center">
          <h6 class="modal-title" id="modal-title-default">Assign Order</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close" ref="myBtn">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">
          {{order['customer_name']}}
<!-- {{order.order_type}}
{{order.order_date}} -->
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-link ml-auto" data-dismiss="modal">Close</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>

  export default{
    props: ['orderKey'],
    data(){
      return{

      }
    },
    mounted(){
      
    },
    computed: {
      order(){
        return this.$store.getters.orders[0]
      },
    },
}

</script>