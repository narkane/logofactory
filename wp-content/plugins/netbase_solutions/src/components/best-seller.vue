<template>
    <v-tabs dark v-model="active" class="best-seller-wrap">
        <v-tabs-bar slot="activators" class="">
            <v-tabs-item v-for="tab in tabs" :key="tab" :href="'#' + tab" ripple>
                {{tab}}
            </v-tabs-item>
            <v-tabs-slider class="yellow"></v-tabs-slider>
        </v-tabs-bar>
        <v-tabs-content v-for="(tab, value, index) in tabs" :key="tab" :id="tab">
            <v-select
                v-if="index === 0"
                v-bind:items="periods"
                label="Year"
                single-line
                bottom
                v-model="getBestSeller"
                class="best-seller-action"
                append-icon="keyboard_arrow_down"
            >
            </v-select>
            <v-data-table
                :headers="headersProducts"
                :items="products"
                class="elevation-1"
                v-if="index === 0"
                :rows-per-page-items="[6]"
                hide-actions
            >
                <template slot="items" scope="props">
                    <td>{{props.item.product_title}}</td>
                    <td class="text-xs-right">{{props.item.qty}}</td>
                </template>
            </v-data-table>
            <v-data-table
                :headers="headersCustomers"
                :items="customers"
                hide-actions
                class="elevation-1"
                v-if="index === 1"
            >
                <template slot="items" scope="props">
                    <td>{{props.item.user_name}}</td>
                    <td>{{props.item.user_email}}</td>
                    <td class="text-xs-right">{{props.item.order_count}}</td>
                    <td class="text-xs-right">{{props.item.total_spent}}</td>
                </template>
            </v-data-table>  
        </v-tabs-content>
    </v-tabs>
</template>

<script>
import Axios from 'axios';

export default {
    name: 'best-seller',
    props: {
        customers: {
            type: Array | Object,
            required: false
        }
    },
    data() {
        return {
            getBestSeller: [],
            tabs: {
                'tab-1': 'Best seller product',
                'tab-2': 'Best customer',
            },
            periods: [
                'month',
                'week',
                'year'
            ],
            headersProducts: [
                { text: 'Product', align: 'left', value: 'product_title' },
                { text: 'Quantity', align: 'right', value: 'qty' },
            ],
            headersCustomers: [
                { text: 'Customer name', align: 'left', value: 'user_name' },
                { text: 'Email', align: 'left', value: 'user_email' },
                { text: 'Order Number', align: 'right', value: 'order_count' },
                { text: 'Total', align: 'right', value: 'total_spent' },
            ],
            active: null,
            products: [],            
        }
    },
    created() {
        this.getBestSellerByPeriod('year')
    },
    watch: {
        getBestSeller: function(input) {
            this.getBestSellerByPeriod(input);
        }
    },
    methods: {
        getBestSellerByPeriod: function(input) {
            Axios.get(nb.api_route + 'best-seller/' + input)
                .then(response => {
                    this.products = response.data
                })
                .catch(e => {
                    this.errors.push(e)
                })
        }
    }
}
</script>

<style lang="scss">
@import '../scss/variable.scss';



.best-seller-wrap {
    min-height: 574px;
    &.tabs:not(.tabs--centered):not(.tabs--grow):not(.tabs--mobile) .tabs__wrapper--scrollable {
        margin-left: 30px;
        margin-right: 30px;    
    }
    .tabs__bar {
        background-color: $db
    }
    .tabs__container {
        .tabs__li {
            margin-bottom: 0;
            margin-right: 30px;
            .tabs__item {
                padding-left: 5px;
                padding-right: 5px;     
                text-transform: none;
                font-size: 14px;           
            }
        }
        .tabs__slider {
            bottom: -6px;
            background: #2ee8b7 !important;
        }
    }
    .best-seller-action {
        &.input-group--text-field {
            max-width: 180px;
            float: right;
            margin-right: 30px;
        }
        &.input-group--text-field label {
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
            text-transform: capitalize;
        }
    }
    .table {
        thead {
            th {
                font-size: 14px;
                color: #424242 !important;
                padding-top: 25px;
                padding-bottom: 25px;
                font-weight: bold;
            }
        }
    }
}
</style>
