<template>
  <div class="small">
    <doughnut-chart :chart-data="datacollection"></doughnut-chart>
  </div>
</template>

<script>
  import DoughnutChart from './DoughnutChart.js'

  export default {
    props:['chartFor'],
    components: {
      DoughnutChart
    },
    data () {
      return {
        datacollection: {},
      }
    },
    mounted () {
      this.$store.dispatch('getOrderStatusCount')
      this.fillData()
    },
    methods: {
      fillData () {
        if(this.chartFor=="pendingOrders"){
          this.datacollection = {
            labels: ['Pending', 'Assigned','Invoice Generated','Invoice Confirmed'],
            datasets: [
              {
                label: 'Data One',
                backgroundColor: [
                  'rgba(255, 99, 132, 0.5)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                ],
                data: [
                  this.orderStatusCount.pending, 
                  this.orderStatusCount.assigned, 
                  this.orderStatusCount.invoice_generated, 
                  this.orderStatusCount.invoice_confirmed
                ]
              }, 
            ]
          }
        }
        else if(this.chartFor=="receivedOrders"){
          this.datacollection = {
            labels: ['On Work'],
            datasets: [
              {
                label: 'Data One',
                backgroundColor: [
                  'rgba(65, 192, 192, 0.2)'
                ],
                data: [
                  this.orderStatusCount.on_work, 
                ]
              }, 
            ]
          }
        }
        else if(this.chartFor=="readyForDeliveryOrders"){
          this.datacollection = {
            labels: ['Assigned for Delivery','Picked for Delivery'],
            datasets: [
              {
                label: 'Data One',
                backgroundColor: [
                  'rgba(50, 200, 192, 0.2)',
                  'rgba(25, 192, 192, 0.2)',
                ],
                data: [
                  this.orderStatusCount.assigned_for_delivery, 
                  this.orderStatusCount.picked_for_delivery, 
                ]
              }, 
            ]
          }
        }
        else if(this.chartFor=="onHoldOrders"){
          this.datacollection = {
            labels: ['Delivered by Driver','Delivery Received by Customer','Paid'],
            datasets: [
              {
                label: 'Data One',
                backgroundColor: [
                  'rgba(45, 192, 192, 0.2)',
                  'rgba(85, 100, 192, 0.2)',
                  'rgba(95, 192, 192, 0.2)',
                ],
                data: [
                  this.orderStatusCount.delivered_by_driver, 
                  2, 
                  3
                ]
              }, 
            ]
          }
        }
        else if(this.chartFor=="completedOrders"){
          this.datacollection = {
            labels: ['Pending', 'Assigned','Invoice Generated','Invoice Confirmed','On Work','Assigned for Delivery','Picked for Delivery','Delivered by Driver','Delivery Received by Customer','Paid'],
            datasets: [
              {
                label: 'Data One',
                backgroundColor: [
                  'rgba(255, 99, 132, 0.5)',
                  'rgba(54, 162, 235, 0.2)',
                  'rgba(255, 206, 86, 0.2)',
                  'rgba(75, 192, 192, 0.2)',
                  'rgba(50, 200, 192, 0.2)',
                  'rgba(25, 192, 192, 0.2)',
                  'rgba(65, 192, 192, 0.2)',
                  'rgba(45, 192, 192, 0.2)',
                  'rgba(85, 100, 192, 0.2)',
                  'rgba(95, 192, 192, 0.2)',
                ],
                data: [
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt(), 
                  this.getRandomInt()
                ]
              }, 
            ]
          }
        }
      },
      getRandomInt () {
        return Math.floor(Math.random() * (50 - 5 + 1)) + 5
      }
    },
    computed:{
      orderStatusCount(){
        return this.$store.state.orderStatusCount
      }
    },
    watch:{
      orderStatusCount(newVal){
        this.fillData()
      }
    }
  }
</script>

<style scoped>
  .small {
    max-width: 600px;
    margin:  20px auto;
  }
</style>