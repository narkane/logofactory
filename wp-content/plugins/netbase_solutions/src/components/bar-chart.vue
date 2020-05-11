<script>
  import {Bar, mixins} from 'vue-chartjs'
  const { reactiveProp } = mixins

  export default Bar.extend({
    mixins: [reactiveProp],
    data() {
        return {
          options: {
            scales: {
              yAxes: [{
                gridLines: {
                  drawBorder: false,
                  display: true,
                  borderDash: [1, 7],
                  color: '#ccc'
                },
                ticks: {
                  fontColor: '#abc0d1',
                  fontFamily: 'Comfortaa',
                  fontStyle: 'bold'
                }
              }],
              xAxes: [ {
                categoryPercentage: 0.9,
                barPercentage: 0.3,
                gridLines: { 
                  display: false,
                  // color: 'rgba(0, 0, 0, 0)'
                },
                ticks: {
                  fontColor: '#abc0d1',
                  fontFamily: 'Comfortaa',
                  fontStyle: 'bold'
                }
              }]
            },
            legend: {
              display: false
            },
            tooltips: {
              titleFontFamily: 'Comfortaa',
              bodyFontFamily: 'Comfortaa',
            }
          },
        }
    },
    mounted() {
      this.renderChart(this.chartData, this.options)
    }
  })
</script>