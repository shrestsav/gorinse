<template>
  <div class="vue-component-body">
    
    <div class="row">
      <div class="col-xl-12">
        <totalSales></totalSales>
      </div>
    </div>
  </div>
</template>


<script>
  import { mapState } from 'vuex'
  import totalSales from '../charts/totalSales.vue'
  export default{
    components:{
      totalSales
    },
    data(){
      return{
        errors:{},
      }
    },
    created(){
      this.$store.commit('changeCurrentPage', 'reports')
      this.$store.commit('changeCurrentMenu', 'reportsMenu')
    },
    mounted(){
      this.$store.dispatch('getOrdersCount')
    },
    methods:{
      goto(){
        this.$router.push({ name: 'orders'})
      }
    },
    computed: {
      ...mapState(['ordersCount'])
    },
    watch: {
    },
  }

</script>