<template>
    <div class="weather-widget" v-if="weather.currently">
        <div class="widget-left">
            <p class="currently-icon"><i :class="getIcon(weather.currently.icon)"></i></p>
            <p class="currently-temp">{{roundNumber(weather.currently.temperature)}}&deg;C</p>
            <p class="currently-summary">{{weather.currently.summary}}</p>
            <p class="currently-address">{{currentLocation}}</p>
            <p class="currently-day">{{todayWithFormat}}</p>
        </div>
        <div class="widget-right">
            <div class="widget-right-top">
                <div>
                    <i class="flaticon-humidity"></i>
                    <span>{{currentHumidity}}</span>
                </div>
                <div>
                    <i class="flaticon-wind-speed"></i>
                    <span>{{roundNumber(weather.currently.windSpeed)}}m/s</span>  
                </div>                              
            </div>
            <div class="widget-right-bot">                
                <div class="forecast-section" v-for="i in timeDatas" :key="i">
                    <div class="forecast-left-section">
                        <p>{{calDay(i)}}</p>
                        <p>{{roundNumber(weather.daily.data[i].temperatureLow)}} - {{roundNumber(weather.daily.data[i].temperatureHigh)}}&deg;C</p>
                    </div>
                    <i :class="getIcon(weather.daily.data[i].icon)"></i>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Axios from 'axios'

export default {
    name: 'weather',
    data() {
        return {
            weather: [],
            days: ['Sun','Mon','Tues','Wed','Thurs','Fri','Sat'],
            timeDatas: [1,2,3,4]
        }        
    },
    computed: {
        currentlyIcon:function() {
            return 'flaticon-' + this.weather.currently.icon
        },
        // currentlyTempCelcius: function() {
        //     return Math.round((this.weather.currently.temperature - 32) * 5 / 9)
        // },
        todayWithFormat: function() {
            var now = new Date()

            var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec']

            return this.days[now.getDay()] + ' - ' + now.getDate() + ' ' + months[now.getMonth()]
        },
        currentLocation: function() {
            if(typeof nb.geo_country === 'undefined' || !nb.geo_country) {
                return nb.geo_country
            } else {
                return 'Somewhere :)'
            }
        },
        currentHumidity: function() {
            return Math.round(this.weather.currently.humidity * 100) + '%'
        },
        // currentWindSpeed: function() {
        //     return Math.round(this.weather.currently.windSpeed) + 'm/s'
        // }        
    },
    created() {
        var self = this
        if ("geolocation" in navigator) {
            var watchID = navigator.geolocation.watchPosition(function(position) {
                var proxy = 'https://cors-anywhere.herokuapp.com/'
                var apiUrl = 'https://api.darksky.net/forecast/'
                
                Axios.get(proxy + apiUrl + nb.ds_api_key + '/' + position.coords.latitude + ',' + position.coords.longitude + '?units=si')
                    .then(response => {
                        self.weather = response.data        
                    })
                    .catch(e => {
                        console.log(e)
                    })

            });
        } else {
            //TODO Bring this to snackbar
            console.log('Please update your browser to lastest version so you can use this widget')
        }        
    },
    methods: {
        calDay: function(i) {
            var timestamp = this.weather.daily.data[i].time
            var date = new Date()
            date.setTime(timestamp*1000)
            
            return this.days[date.getDay()]
        },
        getIcon: function(icon) {
            return 'flaticon-' + icon
        },
        roundNumber: function(number) {
            return Math.round(number)
        }
    }
}
</script>

<style lang="scss">
@import '../scss/variable.scss';

.weather-widget {
    display: flex;
    .widget-left, .widget-right {
        flex: 0 0 50%;
        max-width: 50%;
        min-height: 400px;
        padding: 30px 30px 0;        
    }
    .widget-left {
        background-image: url(~../static/weather.png);
        background-size: 100% 100%;
        text-align: right;
        i, p {
            color: #fff;
            line-height: 1;
            margin-top: 0;
        }
        .currently-summary {
            font-size: 18px;
        }
        .currently-icon {
            margin-bottom: 45px;
            i:before {
                font-size: 65px;
            }
        }
        .currently-temp {
            font-size: 35px;
        }
        .currently-address {
            margin-top: 75px !important;
            position: relative;
            margin-bottom: 30px;
            &:after {
                content: '';
                width: 60px;
                height: 1px;
                background: #fff;
                display: block;
                position: absolute;
                bottom: -15px;
                right: 0;
            }
            &, .currently-day {
                font-size: 16px;
            }
        }
    }
    .widget-right {
        background-color: #fff;
        .widget-right-top {
            display: flex;
            border-bottom: 2px solid #f3f3f3;
            padding-bottom: 10px;    
            margin-bottom: 30px;
            & > div {
                flex: 0 0 50%;
                max-width: 50%;
                text-align: center;
                color: $db;
            }
            i {
                display: block;
                line-height: 1;
                margin-bottom: 10px;
                &:before {
                    margin-left: 0;
                    font-size: 40px;
                }
            }
            span {
                font-size: 25px;
            }
        }
    }
    .forecast-section {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 17px;
        .forecast-left-section {
            p {
                font-size: 16px;
                margin-top: 0;
                margin-bottom: 0;
                &:first-child {
                    color: $db
                }
                &:last-child {
                    color: #9e9e9e
                }
            }
        }
        > i:before {
            font-size: 35px;
            color: $db;
        }
    }
}
</style>