<template>
    <div>
        <div class="setting-details" v-for="(s, key, index) in settings" :key="index">
            <h4 class="setting-title">{{s.name}}</h4>
            <div class="setting-color-picker-wrap" v-if="s.type === 'color'">
                <ColorPicker
                    :name="name"
                    :id="s.id"
                    :color="!s.value ? s.default : s.value"
                    v-model="s.value"
                ></ColorPicker>
                <span>{{s.value}}</span>
            </div>
            <div class="setting-radio-icon-wrap" v-else-if="s.type === 'icon'">                                
                <RadioIcon
                    :id="s.id"
                    :name="name"
                    :options="s.options"
                    :value="!s.value ? s.default : s.value"
                    @changeSettings="updateSettings"
                ></RadioIcon>
            </div>
            <div class="setting-radio-icon-wrap" v-else-if="s.type === 'radio_image'">                                
                <RadioImage
                    :id="s.id"
                    :name="name"
                    :options="s.option"
                    :value="!s.value ? s.default : s.value"
                ></RadioImage>
            </div>
            <div class="setting-radio-icon-wrap" v-else-if="s.type === 'repeater'">                                
                <CurrencyRepeater
                    :name="name"                    
                    :id="s.id"
                    :resultItems="s.value"
                    :fields="s.fields"
                ></CurrencyRepeater>
            </div>
            <div class="setting-number-wrap" v-else-if="s.type === 'number'">
                <v-slider
                    :name="name"                    
                    :id="s.id"
                    v-if="!s.value"
                    v-model="s.default"
                    thumb-label
                    step="10"
                ></v-slider>
                <v-slider
                    :name="name"                    
                    :id="s.id"
                    v-else
                    v-model="s.value"
                    thumb-label
                    step="10"
                ></v-slider>
            </div>
            <div class="setting-checkbox-wrap" v-else-if="s.type === 'checkbox'">
                <v-switch
                    :name="name"                    
                    :id="s.id"
                    v-if="!s.value"
                    v-model="s.default"
                    :label="s.label"
                ></v-switch>
                <v-switch
                    :name="name"                    
                    :id="s.id"
                    v-else
                    v-model="s.value"
                    :label="s.label"
                ></v-switch>
            </div>
            <div class="setting-uploader-wrap" v-else-if="s.type === 'image'">
                <Uploader :name="name" :id="s.id"></Uploader>
            </div>
            <div class="setting-text-wrap" v-else>
                <input :name="name" :id="s.id" type="text" :value="s.value" />
            </div>
        </div>
        <v-btn
            class="white--text settings-post app-button"
            large
        >Save Settings</v-btn>
    </div>
</template>

<script>
import ColorPicker from '../fields/color-picker.vue'
import RadioIcon from '../fields/radio-icon.vue'
import Uploader from '../fields/uploader.vue'
import CurrencyRepeater from '../fields/currency-repeater.vue'
import RadioImage from '../fields/radio-image.vue'

export default {
    name: 'setting-fields',
    props: {
        settings: {
            type: Array | Object
        },
        name: {
            type: String
        },
        slug: {
            type: String
        }
    },
    data() {
        return {    
            settingsToEmit: [],
        }
    },    
    components: {
        ColorPicker,
        RadioIcon,
        Uploader,
        CurrencyRepeater,
        RadioImage
    },
    methods: {
        updateSettings() {
            console.log('changed')
        }
    }
}
</script>

<style></style>