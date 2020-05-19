<template>
    <div class="sale-stats-chart"> 
        <h3 class="app-headings">Sale stats</h3>
        <div class="chart-actions-wrap">
            <span>({{currency}})</span>
            <v-select
                v-bind:items="periods"
                label="Week"
                single-line
                bottom
                @change="getRecentIncomeByPeriod"
                append-icon="keyboard_arrow_down"
            >
            </v-select>
        </div>
        <BarChart
            v-if="display"
            class="sale-stats-chart"
            ref="barChart"
            label="Monthly Income"
            :height="190"
            :chart-data="incomeData"
        ></BarChart>
        <p v-if="!display">Not enough data to analyze</p>
    </div>
</template>

<script>
import Axios from 'axios'
import BarChart from './bar-chart.vue'
import LineChart from './LineChart.vue'

export default {
    name: 'recent-income',
    components: {
        BarChart,
        LineChart
    },
    data() {
        return {
            recentIncome: [],
            recentIncomeLabel: [],
            recentIncomeTotal: [],
            periods: [
                'month',
                'week',
                'year',
            ],
            getRecentIncome: [],
            errors: [],
            incomeData: {},
            currency: nb.site_currency,   
            gradient: '',
            display: true
        }
    },
    created() {
        this.getRecentIncomeByPeriod('week')
        console.log(this.recentIncomeTotal)
    },
    methods: {
        getRecentIncomeByPeriod(input) {
            this.recentIncomeLabel = []
            this.recentIncomeTotal = []

            Axios.get(nb.api_route + 'recent-income/' + input)
                .then(response => {                    

                    this.recentIncome = response.data
                    
                    Object.values(this.recentIncome).forEach(value => {
                        this.recentIncomeLabel.push(value.label)
                        this.recentIncomeTotal.push(value.total_sale)
                    })   

                    var arraySum = this.recentIncomeTotal.reduce((a, b) => a + b, 0)   

                    if(arraySum === 0) {
                        this.display = false
                    }

                    this.gradient = this.$refs.barChart.$refs.canvas.getContext('2d').createLinearGradient(0, 0, 0, 450)

                    this.gradient.addColorStop(0, 'rgba(54, 209, 220, 1)')
                    this.gradient.addColorStop(1, 'rgba(91, 134, 229, 1)')

                    // console.log(this.$refs.barChart.$refs.canvas)

                    this.incomeData = {
                        labels: this.recentIncomeLabel,
                        datasets: [{                            
                            data: this.recentIncomeTotal,
                            backgroundColor: this.gradient,
                            hoverBackgroundColor: this.gradient,                            
                        }]                            
                    }
                })
                .catch(e => {
                    this.errors.push(e)
                })
        },
        arrayEqualZero(element, index, array) {
            return element === 0
        }
    }
}
</script>

<style lang="scss">
@import '../scss/variable.scss';

.sale-stats-chart {
    padding: 20px 30px !important;
    background: #fff;
    min-height: 509px;
    .input-group--text-field {
        max-width: 180px;
    }
    .input-group--text-field label {
        font-size: 14px;
        padding-left: 20px;
        top: 25px;
        color: #abc0d1 !important;
        font-weight: 400;
    }
    .input-group__input {
        border: 1px solid #e2e2e2;
        border-radius: 25px;
        min-height: 45px;
        align-items: center;
        .icon {
            margin-right: 15px;
            border-radius: 50%;
            color: #fff !important;
            background: -webkit-linear-gradient(left, $db, $lb); /* For Safari 5.1 to 6.0 */
            background: -o-linear-gradient(right, $db, $lb); /* For Opera 11.1 to 12.0 */
            background: -moz-linear-gradient(right, $db, $lb); /* For Firefox 3.6 to 15 */
            background: linear-gradient(to right, $db, $lb);
            box-shadow: rgba(0,0,0,0.15) 0px 2px 5px 0px;
            padding: 2px;
        }
    }
    .input-group__details {
        min-height: 0;
        &:before {
            display: none;
        }
        &:after {
            display: none;
        }
    }
    .input-group__selections__comma {
        padding-left: 20px;
        color: #abc0d1;
        text-transform: capitalize
    }
}
.chart-actions-wrap {
    display: flex;
    justify-content: space-between;
    align-items: center;
    > span {
        color: #abc0d1;
    }
}
</style>