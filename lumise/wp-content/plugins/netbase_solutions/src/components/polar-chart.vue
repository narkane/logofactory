<script>
import { PolarArea } from 'vue-chartjs'

export default PolarArea.extend({
    data() {
        return {
            options: {
                legend: {
                    position: 'right',
                },
                startAngle: -0.5 * Math.PI
            }
        }
    },
    props: {
        chartData: {
            type: Array | Object,
            required: false
        },
    },
    mounted() {
        this.renderChart(this.chartData, this.options)        
    }
})
</script>