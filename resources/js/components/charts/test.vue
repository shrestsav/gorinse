<template>
  <div>
    <canvas id="planet-chart"></canvas>
  </div>
</template>

<script>
  
  import Chart from 'chart.js';
  import planetChartData from './test.js';
  export default {
    
    components: {
      
    },
    data () {
      return {
        planetChartData: planetChartData,
      }
    },
    mounted () {
      console.log('fasdf')
     this.createChart('planet-chart', this.planetChartData);
    },
    methods: {
      createChart(chartId, chartData) {
        const ctx = document.getElementById(chartId);
        const myChart = new Chart(ctx, {
          type: chartData.type,
          data: chartData.data,
          options: chartData.options,
        });
      }
    },
    computed:{
      
    },
    watch:{
     
    }
  }
</script>
