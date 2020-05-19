<template>  
    <div class="dashboard-container-wrap">
      <transition name="fade">        
        <AppLoader v-if="!loading"></AppLoader>
      </transition>

      <transition name="fade-2" v-if="enoughData">
        <v-container grid-list-xl v-show="loading">
          <v-layout row wrap>  
            <v-flex xs12 sm6 lg3>
              <DashboardCards
                :result="currencySymbol + items.total_income"
                icon="flaticon-money"
                message="Total Income"
              ></DashboardCards>
            </v-flex>

            <v-flex xs12 sm6 lg3>
              <DashboardCards
                :result="items.orders"
                icon="flaticon-folder"
                message="Orders need processing"
              ></DashboardCards>
            </v-flex>

            <v-flex xs12 sm6 lg3>
              <DashboardCards
                v-if="items.customers"
                :result="items.customers.new_customers"
                icon="flaticon-identification"
                message="New customers this month"
              ></DashboardCards>
            </v-flex>

            <v-flex xs12 sm6 lg3>
              <DashboardCards
                v-if="items.comments_on_hold"
                :result="items.comments_on_hold.comments_count"
                icon="flaticon-money-1 "
                message="Comments on hold"
              ></DashboardCards>
            </v-flex>
            
            <v-flex xs12 lg8>
              <RecentIncome></RecentIncome>
            </v-flex>
            <v-flex xs12 lg4>
              <v-data-table
                :headers="saleTableHeaders"
                :items="items.recent_income"
                class="sale-stats-table section-wrap"
                :rows-per-page-items="[7]"
              >
                <template slot="items" scope="props">
                    <td>{{props.item.label}}</td>
                    <td>{{props.item.order_count}}</td>
                    <td>{{currencySymbol}}{{props.item.total_sale}}</td>                    
                </template>                
              </v-data-table>
            </v-flex>  

            <v-flex xs12 lg6>
              <BestSeller
                v-if="items.customers"
                :customers="items.customers.best_customers"
                class="section-wrap"
              ></BestSeller>
            </v-flex>

            <v-flex xs12 lg6>
              <div class="section-wrap comments-on-hold">
                <h3 class="app-headings">Recent reviews</h3>
                <Comments
                  v-if="items.comments_on_hold"
                  :comments="items.comments_on_hold.comments"                
                ></Comments>
              </div>
            </v-flex> 

            <v-flex xs12 lg6>
              <div class="section-wrap payment-stats" v-if="loading">
                <h3 class="app-headings">Payment methods stats</h3>
                <PieChart
                  ref="pieChart"
                  :chartData="paymentData"
                  :height="182"
                ></PieChart> 
              </div>
            </v-flex>      
          
            <v-flex xs12 lg6>
              <v-data-table
                :headers="paymentTableHeaders"
                :items="items.payments"
                :hide-actions="hidePaymentTableActions"
                class="payment-stats-table section-wrap"
                :rows-per-page-items="[4]"
              >
                <template slot="items" scope="props">
                    <td>{{props.item.payment_title}}</td>
                    <td>{{props.item.payment_slug}}</td>
                    <td>{{props.item.payment_order_count}}</td>
                    <td>{{props.item.total_sales}}</td>        
                </template>
              </v-data-table>
            </v-flex>     

            <v-flex xs12 lg4>
              <div class="geo-stats-wrap">
                <h3 class="app-headings">Geographical stats</h3>
                <PolarArea
                  v-if="items.billing_country"
                  :chartData="billingData"
                  :height="270"                  
                ></PolarArea> 
              </div>
            </v-flex>   

            <v-flex xs12 md6 lg4>
              <v-data-table
                :headers="geoTableHeaders"
                :items="items.billing_country"
                hide-actions
                class="geo-table-wrap section-wrap"
                :rows-per-page-items="[6]"
              >
                <template slot="items" scope="props">
                    <td>{{props.item.country}}</td>
                    <td>{{props.item.order_count}}</td>
                    <td>{{props.item.total_country_billing}}</td>
                </template>
              </v-data-table>
            </v-flex>
            
            <v-flex xs12 md6 lg4>
              <Weather></Weather>
            </v-flex>
          </v-layout>
        </v-container>
      </transition>

      <transition name="fade-slow" v-if="!enoughData">
        <div class="not-enough-data">
          <div class="h1-wrap">
            <h1>Your site seems pretty new and we will need more data to setup this dashboard :)</h1>
          </div>
        </div>
      </transition>
    </div>
  
</template>

<script>
import Axios from 'axios'
import DashboardCards from '../components/dashboard-cards.vue'
import BestSeller from '../components/best-seller.vue'
import PieChart from '../components/pie-chart.vue'
import RecentIncome from '../components/recent-income.vue'
import Comments from '../components/comments.vue'
import Weather from '../components/weather.vue'
import PolarArea from '../components/polar-chart.vue'
import AppLoader from '../components/app-loader.vue'

export default {
  name: 'dashboard',
  data() {
    return {
      items: [],
      errors: [],
      loading: false,
      paymentLabels: [],
      paymentTotals: [],
      paymentData: [],
      currencySymbol: nb.site_currency_symbol,       
      saleTableHeaders: [
        { text: 'Period', align: 'left', value: 'label' },
        { text: 'Order count', align: 'left', value: 'order_count' },
        { text: 'Total sale', align: 'left', value: 'total_sale' },
      ],
      paymentTableHeaders: [
        { text: 'Name', align: 'left', value: 'payment_title' },
        { text: 'Code', align: 'left', value: 'payment_slug' },
        { text: 'Order Count', align: 'left', value: 'payment_order_count' },
        { text: 'Order Sale', align: 'left', value: 'total_sales' },
      ],
      geoTableHeaders: [
        { text: 'Country', align: 'left', value: 'country' },
        { text: 'Order Count', align: 'left', value: 'order_count' },
        { text: 'Total Billings', align: 'left', value: 'total_country_billing' },
      ],
      gradient: [],
      hidePaymentTableActions: false,
      billingData: [],
      billingLabels: [],
      billingTotals: [],
      enoughData: true,
    }
  },
  components: {
    BestSeller,
    DashboardCards,
    PieChart,
    RecentIncome,
    Comments,
    Weather,
    PolarArea,
    AppLoader
  },
  created() {
    Axios.get(nb.api_route + 'dashboard/')
      .then(response => {
        this.items = response.data

        if(this.items.total_income === 0) {
          this.enoughData = false
        }


        Object.values(this.items.payments).forEach(value => {
          this.paymentLabels.push(value.payment_title)
          this.paymentTotals.push(value.total_sales)
        })

        Object.values(this.items.billing_country).forEach(value => {
          this.billingLabels.push(value.country)                
          this.billingTotals.push(parseInt(value.total_country_billing))          
        })

        if(this.items.payments.length < 5) {
          this.hidePaymentTableActions = true
        }

        this.paymentData = {
          labels: this.paymentLabels,
          datasets: [{
            data: this.paymentTotals,
            backgroundColor: ['#5b86e5', '#36d1dc', '#ef629f', '#11ffbd'],
            borderWidth: 0
          }]
        }

        this.billingData = {
          labels: this.billingLabels,
          datasets: [{
            data: this.billingTotals,
            backgroundColor: [
              'rgba(91, 134, 229, 0.8)',
              'rgba(54, 209, 220, 0.8)',
              'rgba(239, 98, 159, 0.8)',
              'rgba(238, 205, 163, 0.8)',
              'rgba(16, 255, 188, 0.8)',
              'rgba(241, 154, 77, 0.8)'
            ],
            borderWidth: 0
          }]
        }

        this.loading = true
      })
      .catch(e => {
        this.errors.push(e)
      })
  }
}
</script>

<style lang="scss">
@import '../scss/variable.scss';

#app {
  min-height: 700px;
  position: relative;
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity .3s
}

.fade-enter,
.fade-leave-to {
  opacity: 0
}

.fade-2-enter-active,
.fade-2-leave-active {
  transition: opacity .9s
}

.fade-2-enter,
.fade-2-leave-to {
  opacity: 0
} 

.dashboard-spinner {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.section-wrap {
  background-color: #fff;
}

.payment-stats {
  padding: 20px 30px;
  .app-headings {
    margin-bottom: 30px;
  }
}

.payment-stats-table {
  height: 440px;
}

.comments-on-hold {
  padding: 20px 30px;
  min-height: 574px;
  .app-headings {
    margin-bottom: 30px;
  }
}

.geo-stats-wrap {
  padding: 30px;
  background-color: #fff;  
  .app-headings {
    margin-bottom: 30px;
  }
}

.geo-table-wrap {
  min-height: 435px;
  .datatable {
    thead {
      th {
        &.column.sortable.active {
          color: #fff;
        }
        i {
          color: #fff !important;
        }
        background-color: $db;
        color: #fff !important;
      }
    }
  }
}

.sale-stats-table, .payment-stats-table {
  .datatable {
    thead {
      background: $db;
      th {
        color: #fff;
        font-size: 16px;
        font-weight: bold;
        &.column.sortable {
          &.active {
            color: #f1f1f1;
          }     
          &:hover {
            color: #f1f1f1;
          }     
          i {
            color: #fff;
            margin-left: 3px;
          }
        }
        &:first-child {
          padding-left: 17px;
          padding-right: 17px;
        }
      }
    }
    tbody {
      td {        
        font-size: 16px;
        font-weight: bold;
        color: #666;
        &:first-child {
          padding-top: 25px;
          padding-bottom: 25px;
        }
      }
    }
    tr:not(:last-child) {
      border-bottom: 1px solid #eeeeee;
    }
    tfoot {
      .datatable__actions {
        padding-left: 15px;
        @media(max-width: 1424px) {
          justify-content: flex-start;
        }
      }
      tr {
        border-top: 0;
      }
    }
    .datatable__actions__select {
      .input-group--select {
        margin-top: 5px;
        margin-bottom: 5px;
      }
    }
    .datatable__actions .btn:last-of-type {
      margin-left: 5px;
    }
    .btn__content {
      &:before {
        border-radius: 50%;
        background: -webkit-linear-gradient(left, $db, $lb); /* For Safari 5.1 to 6.0 */
        background: -o-linear-gradient(right, $db, $lb); /* For Opera 11.1 to 12.0 */
        background: -moz-linear-gradient(right, $db, $lb); /* For Firefox 3.6 to 15 */
        background: linear-gradient(to right, $db, $lb);
        opacity: 1;
      }
      .icon {
        z-index: 9;
        color: #fff;
      }
    }
  }
}

.not-enough-data {  
  .h1-wrap {
    padding-top: 150px;
    padding-bottom: 150px;
    max-width: 900px;
    text-align: center;
    margin-left: auto;
    margin-right: auto;
  }
  h1 {
    font-size: 40px;
    color: #fff;
    line-height: 60px;
  }
}
</style>

