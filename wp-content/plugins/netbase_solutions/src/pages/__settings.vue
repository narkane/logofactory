<template>
    <v-container grid-list-xl class="module-settings-wrap">
        <transition name="fade">
            <div class="bs-row">
                <v-progress-circular
                    v-if="!loading"
                    indeterminate
                    :size="300"
                    :width="3"
                    class="purple--text dashboard-spinner"
                ></v-progress-circular>
            </div>
        </transition>
        <transition name="fade-slow">
            <div v-if="loading" class="bs-row">
                <div class="module-settings col-md-12" v-for="(setting, index) in settings" :key="index">
                    <div class="setting-wrap">
                        <h3 class="module-title">{{setting.module_name}}</h3>
                        <div class="setting-details" v-for="(s, i) in setting.settings" :key="i">
                            <h4 class="setting-title">{{s.name}}</h4>
                            <div class="setting-color-picker-wrap" v-if="s.type === 'color'">
                                <ColorPicker
                                    :id="s.id"
                                    :color="!s.value ? s.default : s.value"
                                    v-model="settingsSubmit"
                                ></ColorPicker>
                            </div>
                            <div class="setting-radio-icon-wrap" v-else-if="s.type === 'icon'">                                
                                <RadioIcon
                                    :id="s.id"
                                    :name="setting.slug"
                                    :options="s.options"
                                    :value="!s.value ? s.default : s.value"
                                    v-model="settingsSubmit"                                    
                                ></RadioIcon>
                            </div>
                            <div class="setting-radio-icon-wrap" v-else-if="s.type === 'radio_image'">                                
                                <RadioImage
                                    :id="s.id"
                                    :name="setting.slug"
                                    :options="s.option"
                                    :value="!s.value ? s.default : s.value"
                                    v-model="settingsSubmit"                                    
                                ></RadioImage>
                            </div>
                            <div class="setting-radio-icon-wrap" v-else-if="s.type === 'repeater'">                                
                                <CurrencyRepeater
                                    :id="s.id"
                                    :resultItems="s.value"
                                    :fields="s.fields"
                                    v-model="settingsSubmit"                                    
                                ></CurrencyRepeater>
                            </div>
                            <div class="setting-number-wrap" v-else-if="s.type === 'number'">
                                <v-slider
                                    :id="s.id"
                                    v-if="!s.value"
                                    v-model="s.default"
                                    thumb-label
                                    step="10"
                                    v-model="settingsSubmit"                                    
                                ></v-slider>
                                <v-slider
                                    :id="s.id"
                                    v-else
                                    v-model="s.value"
                                    thumb-label
                                    step="10"
                                    v-model="settingsSubmit"                                    
                                ></v-slider>
                            </div>
                            <div class="setting-checkbox-wrap" v-else-if="s.type === 'checkbox'">
                                <v-switch
                                    :id="s.id"
                                    v-if="!s.value"
                                    v-model="s.default"
                                    :label="s.label"
                                    v-model="settingsSubmit"                                    
                                ></v-switch>
                                <v-switch
                                    :id="s.id"
                                    v-else
                                    v-model="s.value"
                                    :label="s.label"
                                    v-model="settingsSubmit"                                    
                                ></v-switch>
                            </div>
                            <div class="setting-uploader-wrap" v-else-if="s.type === 'image'">
                                <Uploader :id="s.id" v-model="settingsSubmit"></Uploader>
                            </div>
                            <div class="setting-text-wrap" v-else>
                                <input :id="s.id" type="text" :value="s.value" v-model="settingsSubmit"/>
                            </div>
                        </div>
                        <v-btn
                            class="white--text settings-post app-button"
                            large
                            @click="updateModuleSettings(setting.slug)"
                        >Save Settings</v-btn>
                    </div>
                </div>
            </div>
        </transition>
    </v-container>
</template>

<script>
import Axios from 'axios'
import ColorPicker from '../fields/color-picker.vue'
import RadioIcon from '../fields/radio-icon.vue'
import Uploader from '../fields/uploader.vue'
import CurrencyRepeater from '../fields/currency-repeater.vue'
import RadioImage from '../fields/radio-image.vue'

export default {
    name: 'settings',
    data() {
        return {
            settings: [],
            loading: false,
            settingsSubmit: []
        }
    },
    components: {
        ColorPicker,
        RadioIcon,
        Uploader,
        CurrencyRepeater,
        RadioImage
    },
    created() {
        Axios.get(nb.api_route + 'settings/')
            .then(response => {
                this.settings = response.data
                this.loading = true
            })
            .catch(e => {
                console.log(e)
            })
    },
    methods: {
        updateModuleSettings(setting) {
            this.settingsSubmit = []
            Axios.post(nb.api_route + 'modules/' + setting, {                
                this.settingsSubmit
            })
        }
    }
}
</script>

<style lang="scss">
@import '../scss/variable.scss';

.fade-enter-active,
.fade-leave-active {
  transition: opacity .3s
}

.fade-enter,
.fade-leave-to {
  opacity: 0
}

.fade-slow-enter-active,
.fade-slow-leave-active {
  transition: opacity .9s
}

.fade-slow-enter,
.fade-slow-leave-to {
  opacity: 0
} 

.dashboard-spinner {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.module-settings-wrap {
    // margin-left: -700px;
    // padding-left: 700px;
    ul {
        padding-left: 0
    }
    button {
        margin-bottom: 0;
    }
    .setting-number-wrap {
        max-width: 240px;
        .slider {
            padding-top: 0;
            padding-bottom: 0;
        }
    }
    .settings-post {

    }
}

.module-settings {
    background-color: #fff;
    padding: 30px;
    margin-bottom: 45px;
    .setting-details {
        margin-bottom: 45px;
    }
    .module-title {
        font-size: 20px;
        margin-top: 0;
        padding-bottom: 15px;
        margin-bottom: 30px;
        position: relative;
        color: $db;
            border-bottom: 1px solid #eee;
        &:before {
            content: '';
            height: 1px;
            width: 90px;
            background: #11ffbd;
            display: block;
            position: absolute;
            bottom: -1px;
        }
    }
    .setting-title {
        font-size: 16px;
        margin-top: 0;
    }    
}
</style>