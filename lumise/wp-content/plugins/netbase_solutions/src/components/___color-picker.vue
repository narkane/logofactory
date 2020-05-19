<template>
    <div class="color-picker-wrap">   
        <div class="color-placeholder" @click="triggerUI">
            <p>Select a custom color</p>
            <button v-bind:style="{ backgroundColor: mutateColor }"></button>
        </div>     
        <transition name="fade">
            <Chrome :value="mutateColor" @input="updateColor" v-if="showUI"></Chrome>    
        </transition>    
        <!-- <input @input="testAsd"/> -->
    </div>
</template>

<script>
import { Chrome } from 'vue-color'

export default {
    name: 'settings',
    data() {
        return {
            mutateColor: this.color,
            showUI: false
        }
    },
    props: {
        color: {
            type: Array | Object | String,
        }
    },
    components: {
        Chrome
    },
    methods: {
        updateColor(val) {
            var rgba = val.rgba
            this.mutateColor = 'rgba(' + rgba.r + ',' + rgba.g + ',' + rgba.b + ',' + rgba.a + ')'
            // console.log(this.mutateColor)
        },
        triggerUI() {
            this.showUI = !this.showUI
        }
    }
}
</script>

<style lang="scss">
.fade-enter-active,
.fade-leave-active {
  transition: opacity .3s
}

.fade-enter,
.fade-leave-to {
  opacity: 0
}

.color-picker-wrap {
    .color-placeholder {
        max-width: 225px;
        border: 1px solid #abc0d1;
        height: 50px;
        display: flex;
        align-items: center;
        border-radius: 25px;
        padding-left: 20px;
        padding-right: 4px;
        justify-content: space-between;
        p {
            margin: 0;
            color: #abc0d1;
        }
        button {
            width: 42px;
            height: 42px;
            display: block;
            border-radius: 50%;
            border: 0;
        }
    }
    .vc-chrome {
        margin-top: 5px;
    }
}
</style>