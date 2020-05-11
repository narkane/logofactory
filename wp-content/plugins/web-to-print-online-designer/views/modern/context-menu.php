<div class="nbd-context-menu" id="nbd-context-menu" ng-style="ctxMenuStyle" ng-click="ctxMenuStyle.visibility = 'hidden'">
    <div class="main-context">
        <ul class="contexts">
            <li class="context-item" ng-click="setLayerAttribute('excludeFromExport', !stages[currentStage].states.excludeFromExport)" ng-show="settings.task == 'create_template' && !stages[currentStage].states.excludeFromExport"><i class="icon-nbd icon-nbd-clear"></i> <?php _e('Exclude from export','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="rotateLayer('reflect-hoz')" ng-show="stages[currentStage].states.isLayer"><i class="icon-nbd icon-nbd-reflect-horizontal"></i> <?php _e('Reflect Horizontal','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="rotateLayer('reflect-ver')" ng-show="stages[currentStage].states.isLayer"><i class="icon-nbd icon-nbd-reflect-vertical"></i> <?php _e('Reflect Vertical','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="fitToStage('width')" ng-show="stages[currentStage].states.isLayer && stages[currentStage].states.isImage">
                <i style="height: 24px;" class="icon-nbd">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#888" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M0.938 5.203c-0.518 0-0.938 0.42-0.938 0.938v11.719c0 0.518 0.42 0.938 0.938 0.938s0.938-0.42 0.938-0.938v-11.719c0-0.518-0.42-0.938-0.938-0.938z"></path>
                        <path d="M23.063 5.203c-0.518 0-0.938 0.42-0.938 0.938v11.719c0 0.518 0.42 0.938 0.938 0.938s0.938-0.42 0.938-0.938v-11.719c0-0.518-0.42-0.938-0.938-0.938z"></path>
                        <path d="M16.453 11.063h-12.196l2.684-2.663c0.368-0.365 0.37-0.958 0.005-1.326s-0.958-0.37-1.326-0.005l-2.914 2.89c-0.537 0.532-0.832 1.241-0.832 1.994s0.296 1.462 0.832 1.994l2.914 2.89c0.183 0.181 0.421 0.272 0.66 0.272 0.241 0 0.482-0.093 0.666-0.277 0.365-0.368 0.362-0.961-0.005-1.326l-2.59-2.569h12.102c0.518 0 0.938-0.42 0.938-0.938s-0.42-0.938-0.938-0.938z"></path>
                        <path d="M21.293 9.959l-2.914-2.89c-0.368-0.365-0.961-0.362-1.326 0.005s-0.362 0.961 0.005 1.326l2.914 2.89c0.179 0.178 0.278 0.413 0.278 0.663s-0.099 0.486-0.278 0.663l-2.914 2.89c-0.368 0.365-0.37 0.958-0.005 1.326 0.183 0.185 0.424 0.277 0.666 0.277 0.239 0 0.477-0.091 0.66-0.272l2.914-2.89c0.537-0.532 0.832-1.241 0.832-1.994s-0.296-1.462-0.832-1.994z"></path>
                    </svg>
                </i> <?php _e('Fit to width','web-to-print-online-designer'); ?>
            </li>
            <li class="context-item" ng-click="fitToStage('height')" ng-show="stages[currentStage].states.isLayer && stages[currentStage].states.isImage">
                <i style="height: 24px;" class="icon-nbd">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#888" width="24" height="24" viewBox="0 0 24 24">
                        <path d="M17.859 0h-11.719c-0.518 0-0.938 0.42-0.938 0.938s0.42 0.938 0.938 0.938h11.719c0.518 0 0.938-0.42 0.938-0.938s-0.42-0.938-0.938-0.938z"></path>
                        <path d="M17.859 22.125h-11.719c-0.518 0-0.938 0.42-0.938 0.938s0.42 0.938 0.938 0.938h11.719c0.518 0 0.938-0.42 0.938-0.938s-0.42-0.938-0.938-0.938z"></path>
                        <path d="M16.931 5.621l-2.89-2.914c-0.532-0.537-1.241-0.832-1.994-0.832s-1.462 0.296-1.994 0.832l-2.89 2.914c-0.365 0.368-0.362 0.961 0.005 1.326s0.961 0.362 1.326-0.005l2.569-2.59v12.195c0 0.518 0.42 0.938 0.938 0.938s0.938-0.42 0.938-0.938v-12.29l2.663 2.684c0.183 0.185 0.424 0.277 0.666 0.277 0.239 0 0.477-0.091 0.66-0.272 0.368-0.365 0.37-0.958 0.005-1.326z"></path>
                        <path d="M16.926 17.053c-0.368-0.365-0.961-0.362-1.326 0.005l-2.89 2.914c-0.178 0.179-0.413 0.278-0.663 0.278s-0.486-0.099-0.663-0.278l-2.89-2.914c-0.365-0.368-0.958-0.37-1.326-0.005s-0.37 0.958-0.005 1.326l2.89 2.914c0.532 0.537 1.241 0.832 1.994 0.832s1.462-0.296 1.994-0.832l2.89-2.914c0.365-0.368 0.362-0.961-0.005-1.326z"></path>
                    </svg>
                </i> <?php _e('Fit to height','web-to-print-online-designer'); ?>
            </li>
            <li class="context-item" ng-click="fitToStage()" ng-show="stages[currentStage].states.isLayer && stages[currentStage].states.isImage">
                <i class="icon-nbd">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="vertical-align: middle;" fill="#888" width="24" height="24" viewBox="0 0 24 24"><defs><path id="a" d="M0 0h24v24H0z"/></defs><clipPath id="b"><use xlink:href="#a" overflow="visible"/></clipPath><path clip-path="url(#b)" d="M15 3l2.3 2.3-2.89 2.87 1.42 1.42L18.7 6.7 21 9V3zM3 9l2.3-2.3 2.87 2.89 1.42-1.42L6.7 5.3 9 3H3zm6 12l-2.3-2.3 2.89-2.87-1.42-1.42L5.3 17.3 3 15v6zm12-6l-2.3 2.3-2.87-2.89-1.42 1.42 2.89 2.87L15 21h6z"/><path clip-path="url(#b)" fill="none" d="M0 0h24v24H0z"/></svg>
                </i> <?php _e('Stretch','web-to-print-online-designer'); ?>
            </li>
            <li class="separator" ng-show="stages[currentStage].states.isLayer"></li>
            <li class="context-item" ng-click="setStackPosition('bring-front')" ng-show="stages[currentStage].states.isLayer && !isTemplateMode"><i class="icon-nbd icon-nbd-bring-to-front"></i> <?php _e('Bring to Front','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="setStackPosition('bring-forward')" ng-show="stages[currentStage].states.isLayer && !isTemplateMode"><i class="icon-nbd icon-nbd-bring-forward"></i> <?php _e('Bring Forward','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="setStackPosition('send-backward')" ng-show="stages[currentStage].states.isLayer && !isTemplateMode"><i class="icon-nbd icon-nbd-sent-to-backward"></i> <?php _e('Send to Backward','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="setStackPosition('send-back')" ng-show="stages[currentStage].states.isLayer && !isTemplateMode"><i class="icon-nbd icon-nbd-send-to-back"></i> <?php _e('Send to Back','web-to-print-online-designer'); ?></li>
            <li class="separator" ng-show="stages[currentStage].states.isLayer && !isTemplateMode"></li>
            <li class="context-item" ng-click="translateLayer('vertical')" ng-show="stages[currentStage].states.isLayer && !isTemplateMode"><i class="icon-nbd icon-nbd-fomat-vertical-align-center"></i> <?php _e('Center horizontal','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="translateLayer('horizontal')" ng-show="stages[currentStage].states.isLayer && !isTemplateMode"><i class="icon-nbd icon-nbd-fomat-vertical-align-center rotate90"></i> <?php _e('Center vertical','web-to-print-online-designer'); ?></li>
            <!--  Group  -->
            <li class="context-item" ng-click="alignLayer('vertical')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-fomat-vertical-align-center rotate90"></i> <?php _e('Align Vertical Center','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="alignLayer('horizontal')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-fomat-vertical-align-center"></i> <?php _e('Align Horizontal Center','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="alignLayer('left')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-fomat-vertical-align-top rotate-90"></i> <?php _e('Align Left','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="alignLayer('right')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-fomat-vertical-align-top rotate90"></i> <?php _e('Align Right','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="alignLayer('top')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-fomat-vertical-align-top"></i> <?php _e('Align Top','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="alignLayer('bottom')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-fomat-vertical-align-top rotate-180"></i> <?php _e('Align Bottom','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="alignLayer('dis-horizontal')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-dis-horizontal"></i> <?php _e('Distribute Horizontal','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="alignLayer('dis-vertical')" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-dis-vertical"></i> <?php _e('Distribute Vertical','web-to-print-online-designer'); ?></li>
            <!--  Template Mode  -->
            <li ng-class="stages[currentStage].states.elementUpload ? 'active' : ''" ng-click="setLayerAttribute('elementUpload', !stages[currentStage].states.elementUpload)" class="context-item" ng-show="stages[currentStage].states.isImage && isTemplateMode"><i class="icon-nbd icon-nbd-replace-image"></i> <?php _e('Replace Image','web-to-print-online-designer'); ?></li>
            <li ng-class="!stages[currentStage].states.forceLock ? '' : 'active'" class="context-item" ng-click="setLayerAttribute('forceLock', !stages[currentStage].states.forceLock)" ng-show="stages[currentStage].states.isLayer && isTemplateMode">
                <i style="padding-left: 3px;" class="icon-nbd icon-nbd-lock"></i> <?php _e('Lock all adjustment','web-to-print-online-designer'); ?>
            </li>
            <li ng-class="stages[currentStage].states.text.editable ? '' : 'active'" class="context-item" ng-click="setTextAttribute('editable', !stages[currentStage].states.text.editable)" ng-show="stages[currentStage].states.isText && isTemplateMode">
                <i style="padding-left: 3px;" class="icon-nbd icon-nbd-lock"></i> <?php _e('Lock edit','web-to-print-online-designer'); ?>
            </li>
            <li ng-class="!stages[currentStage].states.lockMovementX ? '' : 'active'" class="context-item" ng-click="setLayerAttribute('lockMovementX', !stages[currentStage].states.lockMovementX)" ng-show="stages[currentStage].states.isLayer && isTemplateMode">
                <i style="font-size: 18px;" class="icon-nbd icon-nbd-arrows-h"></i> <?php _e('Lock horizontal movement','web-to-print-online-designer'); ?>
            </li>       
            <li ng-class="!stages[currentStage].states.lockMovementY ? '' : 'active'" class="context-item" ng-click="setLayerAttribute('lockMovementY', !stages[currentStage].states.lockMovementY)" ng-show="stages[currentStage].states.isLayer && isTemplateMode">
                <i style="padding-left: 5px; font-size: 18px;" class="icon-nbd icon-nbd-arrows-v"></i> <?php _e('Lock vertical movement','web-to-print-online-designer'); ?>
            </li>
            <li ng-class="!stages[currentStage].states.lockScalingX ? '' : 'active'" class="context-item" ng-click="setLayerAttribute('lockScalingX', !stages[currentStage].states.lockScalingX)" ng-show="stages[currentStage].states.isLayer && isTemplateMode">
                <i style="font-size: 18px;" class="icon-nbd icon-nbd-expand horizontal horizontal-x"><sub>x</sub></i> <?php _e('Lock horizontal scaling','web-to-print-online-designer'); ?>
            </li>     
            <li ng-class="!stages[currentStage].states.lockScalingY ? '' : 'active'" class="context-item" ng-click="setLayerAttribute('lockScalingY', !stages[currentStage].states.lockScalingY)" ng-show="stages[currentStage].states.isLayer && isTemplateMode">
                <i style="font-size: 18px;" class="icon-nbd icon-nbd-expand horizontal horizontal-y"><sub>y</sub></i> <?php _e('Lock vertical scaling','web-to-print-online-designer'); ?>
            </li>
            <li ng-class="!stages[currentStage].states.lockRotation ? '' : 'active'" class="context-item" ng-click="setLayerAttribute('lockRotation', !stages[currentStage].states.lockRotation)" ng-show="stages[currentStage].states.isLayer && isTemplateMode">
                <i class="icon-nbd icon-nbd-refresh rotate180"></i> <?php _e('Lock rotation','web-to-print-online-designer'); ?>
            </li>         
            <!--  Template Mode  -->
            <li class="separator"></li>
            <li class="context-item" ng-click="copyLayers()"><i class="icon-nbd icon-nbd-content-copy"></i> <?php _e('Duplicate','web-to-print-online-designer'); ?></li>
            <li class="context-item"  ng-click="deactiveAllLayer()" ng-show="stages[currentStage].states.isGroup"><i class="icon-nbd icon-nbd-ungroup"></i> <?php _e('Ungroup','web-to-print-online-designer'); ?></li>
            <li class="context-item" ng-click="deleteLayers()"><i class="icon-nbd icon-nbd-delete"></i> <?php _e('Delete','web-to-print-online-designer'); ?></li>
        </ul>
    </div>
</div>