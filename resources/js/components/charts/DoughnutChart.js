import { Doughnut, mixins } from 'vue-chartjs'
import ChartJsPluginDataLabels from 'chartjs-plugin-labels'
const { reactiveProp } = mixins

export default {
  extends: Doughnut,
  mixins: [reactiveProp],
  components: {
    ChartJsPluginDataLabels,
  },
  // props: ['options'],
  data () {
    return {
      options:{
        plugins: {
          labels: {
            // render 'label', 'value', 'percentage', 'image' or custom function, default is 'percentage'
            render: 'label',

            // precision for percentage, default is 0
            precision: 0,

            // identifies whether or not labels of value 0 are displayed, default is false
            showZero: false,

            // font size, default is defaultFontSize
            fontSize: 12,

            // font color, can be color array for each data or function for dynamic color, default is defaultFontColor
            fontColor: '#535353',

            // font style, default is defaultFontStyle
            fontStyle: 'normal',

            // font family, default is defaultFontFamily
            fontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",

            // draw text shadows under labels, default is false
            textShadow: true,

            // text shadow intensity, default is 6
            shadowBlur: 10,

            // text shadow X offset, default is 3
            shadowOffsetX: -5,

            // text shadow Y offset, default is 3
            shadowOffsetY: 5,

            // text shadow color, default is 'rgba(0,0,0,0.3)'
            shadowColor: 'rgba(255,0,0,0.75)',

            // draw label in arc, default is false
            // bar chart ignores this
            arc: true,

            // position to draw label, available value is 'default', 'border' and 'outside'
            // bar chart ignores this
            // default is 'default'
            position: 'default',

            // draw label even it's overlap, default is true
            // bar chart ignores this
            overlap: true,

            // show the real calculated percentages from the values and don't apply the additional logic to fit the percentages to 100 in total, default is false
            showActualPercentages: true,

            // set images when `render` is 'image'
            images: [{
              src: 'image.png',
              width: 16,
              height: 16
            }],

            // add padding when position is `outside`
            // default is 2
            outsidePadding: 4,

            // add margin of text when position is `outside` or `border`
            // default is 2
            textMargin: 4
          }
        },
        legend: {
          display: false,
        }
      }
    }
  },
  mounted () {
    // this.chartData is created in the mixin.
    // If you want to pass options please create a local options object
    this.renderChart(this.chartData, this.options)
  }
}