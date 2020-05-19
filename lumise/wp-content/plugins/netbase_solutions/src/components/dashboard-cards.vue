<template>
    <div class="dashboard-card section-wrap">         
        <div class="dashboard-details">            
            <span>{{result}}</span>
            <p>{{message}}</p>
        </div>
        <i v-bind:class="iconFade"></i>
        <i v-bind:class="icon"></i>        
    </div>
</template>

<script>
export default {
    name: 'dashboard-cards',
    props: {
        result: {
            default: '',            
        },
        icon: {
            type: String,
            default: 0,            
        },
        message: {
            type: String,
            default: 0,            
        },
    },
    computed: {
        iconFade: function() {
            return [
                this.icon,
                'icon-fade'
            ]
        }
    }
}
</script>

<style lang="scss">
.dashboard-card {
    display: flex;
    // background: #fff;
    align-items: center;
    padding: 30px;
    position: relative;
    .dashboard-details {
        flex: 0 0 70%;
        min-height: 93px;
        display: flex;
        flex-direction: column;
        justify-content: center;
        span {
            font-size: 30px;
            color: #5b86e5;
        }
        p {
            font-size: 16px;
            margin-top: 0;
            margin-bottom: 0;
            color: #abc0d1;
            z-index: 10;
        }
    }    
    > i {
        flex: 0 0 30%;
        z-index: 2;
        &:before {
            font-size: 50px;
            background: -webkit-linear-gradient(left top, left bottom, from(#36d1dc), to(#5b86e5));
            background: -o-linear-gradient(left top, left bottom, from(#36d1dc), to(#5b86e5));
            background: -moz-linear-gradient(left top, left bottom, from(#36d1dc), to(#5b86e5));
            background: linear-gradient(left top, left bottom, from(#36d1dc), to(#5b86e5));
            // background: -webkit-linear-gradient(left, $db, $lb); /* For Safari 5.1 to 6.0 */
            // background: -o-linear-gradient(right, $db, $lb); /* For Opera 11.1 to 12.0 */
            // background: -moz-linear-gradient(right, $db, $lb); /* For Firefox 3.6 to 15 */
            // background: linear-gradient(right, $db, $lb); /* Standard syntax */   
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-color: #5b86e5;
        }
        &.icon-fade {
            position: absolute;
            bottom: -18px;
            right: 10px;           
            z-index: 1; 
            &:before {
                color: #fafafa;
                font-size: 128px;
                background: #fafafa;
                -webkit-background-clip: text;
                -webkit-text-fill-color: transparent;
            }
        }
    }
}

</style>
