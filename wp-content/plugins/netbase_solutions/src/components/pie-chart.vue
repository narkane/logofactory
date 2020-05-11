<script>
import { Pie } from 'vue-chartjs'

export default Pie.extend({
    data() {
        return {
            options: {
                legend: {
                    position: 'right',
                },
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

<style>
    #chartjs-legend {
        position:absolute;
        width:100%;
        bottom:10%;
        }
        #chartjs-legend li {
        cursor:pointer;
        margin: 10px 4px;
        }
        #chartjs-legend li span {
        position:relative;
        padding: 6px 20px;
        border-radius:13px;
        color:white;
        z-index:2;
        }
</style>