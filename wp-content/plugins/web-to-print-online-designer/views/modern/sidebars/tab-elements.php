<div class="<?php if( $active_elements ) echo 'active'; ?> tab" id="tab-element" nbd-scroll="scrollLoadMore(container, type)" data-container="#tab-element" data-type="element" data-offset="20">
    <div class="nbd-search">
        <input ng-class="(resource.element.type != 'icon' || !resource.element.onclick) ? 'nbd-disabled' : ''" ng-keyup="$event.keyCode == 13 && getMedia(resource.element.type, 'search')" type="text" name="search" placeholder="<?php _e('Search element', 'web-to-print-online-designer'); ?>" ng-model="resource.element.contentSearch"/>
        <i class="icon-nbd icon-nbd-fomat-search"></i>
    </div>     
    <div class="tab-main tab-scroll" style="margin-top: 70px;height: calc(100% - 70px);">
        <div class="nbd-items-dropdown">
            <div class="main-items">
                <div class="items">
                    <div ng-if="settings['nbdesigner_enable_draw'] == 'yes' && !settings.is_mobile" class="item" data-type="draw" data-api="false" ng-click="onClickTab('draw', 'element')">
                        <div class="main-item">
                            <div class="item-icon"><i class="icon-nbd icon-nbd-drawing"></i></div>
                            <div class="item-info">
                                <span class="item-name"><?php _e('Draw','web-to-print-online-designer'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div ng-if="settings['nbdesigner_enable_clipart'] == 'yes'" class="item" data-type="shapes" data-api="false" ng-click="onClickTab('shape', 'element')">
                        <div class="main-item">
                            <div class="item-icon"><i class="icon-nbd icon-nbd-shapes"></i></div>
                            <div class="item-info">
                                <span class="item-name"><?php _e('Shapes','web-to-print-online-designer'); ?></span>
                            </div>
                        </div>
                    </div>
                    <div ng-if="settings['nbdesigner_enable_clipart'] == 'yes'" class="item" data-type="icons" data-api="false" ng-click="onClickTab('icon', 'element')">
                        <div class="main-item">
                            <div class="item-icon"><i class="icon-nbd icon-nbd-diamond"></i></div>
                            <div class="item-info">
                                <span class="item-name"><?php _e('Icons','web-to-print-online-designer'); ?></span>
                            </div>
                        </div>
                    </div>
<!--                    <div class="item" data-type="lines" data-api="false" ng-click="onClickTab('line', 'element')">
                        <div class="main-item">
                            <div class="item-icon"><i class="icon-nbd icon-nbd-line"></i></div>
                            <div class="item-info">
                                <span class="item-name"><?php _e('Lines','web-to-print-online-designer'); ?></span>
                            </div>
                        </div>
                    </div>-->
                    <div ng-if="settings['nbdesigner_enable_qrcode'] == 'yes'" class="item" data-type="qr-code" data-api="false" ng-click="onClickTab('qrcode', 'element')">
                        <div class="main-item">
                            <div class="item-icon"><i class="icon-nbd icon-nbd-qrcode"></i></div>
                            <div class="item-info">
                                <span class="item-name"><?php _e('Bar/QR-Code','web-to-print-online-designer'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="pointer"></div>
            </div>
            <div class="result-loaded">
                <div class="content-items">
                    <div class="content-item type-draw" data-type="draw">
                        <div class="main-type">
                            <span class="heading-title"><?php _e('Free Drawing','web-to-print-online-designer'); ?></span>
                            <div class="brush" style="text-align: left;">
                                <h3 class="color-palette-label" style="font-size: 12px; text-align: left; margin: 0 0 5px;"><?php _e('Choose ','web-to-print-online-designer'); ?></h3>
                                <button class="nbd-button nbd-dropdown">
                                    <?php _e('Brush','web-to-print-online-designer'); ?> <i class="icon-nbd icon-nbd-arrow-drop-down"></i>
                                    <div class="nbd-sub-dropdown" data-pos="left">
                                        <ul class="tab-scroll">
                                            <li ng-click="resource.drawMode.brushType = 'Pencil';changeBush()" ng-class="resource.drawMode.brushType == 'Pencil' ? 'active' : ''"><span><?php _e('Pencil','web-to-print-online-designer'); ?></span></li>
                                            <li ng-click="resource.drawMode.brushType = 'Circle';changeBush()" ng-class="resource.drawMode.brushType == 'Circle' ? 'active' : ''"><span><?php _e('Circle','web-to-print-online-designer'); ?></span></li>
                                            <li ng-click="resource.drawMode.brushType = 'Spray';changeBush()" ng-class="resource.drawMode.brushType == 'Spray' ? 'active' : ''"><span><?php _e('Spray','web-to-print-online-designer'); ?></span></li>
                                        </ul>
                                    </div>
                                </button>
                            </div>                            
                            <ul class="main-ranges" style="margin-top: 15px;">
                                <li class="range range-brightness">
                                    <label><?php _e('Brush width ','web-to-print-online-designer'); ?></label>
                                    <div class="main-track">
                                        <input class="slide-input" type="range" step="1" min="1" max="100" ng-change="changeBush()" ng-model="resource.drawMode.brushWidth">
                                        <span class="range-track"></span>
                                    </div>
                                    <span class="value-display">{{resource.drawMode.brushWidth}}</span>
                                </li>
                            </ul>
                            <div class="color">
                                <h3 class="color-palette-label" style="font-size: 12px; text-align: left; margin: 0 0 5px;"><?php _e('Brush color','web-to-print-online-designer'); ?></h3>
                                <ul class="main-color-palette nbd-perfect-scroll" style="margin-bottom: 10px; max-height: 220px">
                                    <li class="color-palette-add" ng-init="showBrushColorPicker = false" ng-click="showBrushColorPicker = !showBrushColorPicker;" ng-style="{'background-color': currentColor}"></li>
                                    <li ng-repeat="color in listAddedColor track by $index" ng-click="resource.drawMode.brushColor=color; changeBush()" class="color-palette-item" data-color="{{color}}" title="{{color}}" ng-style="{'background-color': color}"></li>
                                </ul>
                                <div class="pinned-palette default-palette" style="margin-bottom: 10px">
                                    <h3 class="color-palette-label" style="font-size: 12px; text-align: left; margin: 0 0 5px;"><?php _e('Default palette','web-to-print-online-designer'); ?></h3>
                                    <ul class="main-color-palette" ng-repeat="palette in resource.defaultPalette" style="margin-bottom: 15px;">
                                        <li ng-class="{'first-left': $first, 'last-right': $last}" ng-repeat="color in palette track by $index" ng-click="resource.drawMode.brushColor=color; changeBush()" class="color-palette-item" data-color="{{color}}" title="{{color}}" ng-style="{'background': color}"></li>
                                    </ul>                        
                                </div>
                                <div class="nbd-text-color-picker" id="nbd-bg-color-picker" ng-class="showBrushColorPicker ? 'active' : ''" style="z-index: 999;">
                                    <spectrum-colorpicker
                                        ng-model="currentColor"
                                        options="{
                                                preferredFormat: 'hex',
                                                color: '#fff',
                                                flat: true,
                                                showButtons: false,
                                                showInput: true,
                                                containerClassName: 'nbd-sp'
                                        }">
                                    </spectrum-colorpicker>
                                    <div style="text-align: <?php echo (is_rtl()) ? 'right' : 'left'?>">
                                        <button class="nbd-button" ng-click="addColor();changeBush(currentColor);showBrushColorPicker = false;"><?php _e('Choose','web-to-print-online-designer'); ?></button>
                                    </div>
                                </div>        
                            </div>
                            <div class="nbd-color-palette-inner" style="padding: 15px;display: none;">
                                <div class="working-palette" ng-if="settings['nbdesigner_show_all_color'] == 'yes'" style="margin-bottom: 10px; position: relative;z-index: 99;">
                                    <ul class="main-color-palette tab-scroll">
                                        <li class="color-palette-item color-palette-add" ng-click="stageBgColorPicker.status = !stageBgColorPicker.status;" style="background: #fff;"></li>
                                        <li ng-repeat="color in listAddedColor track by $index"
                                            ng-click="changeBackgroundCanvas(color)"
                                            class="color-palette-item"
                                            data-color="{{color}}"
                                            title="{{color}}"
                                            ng-style="{'background-color': color}">
                                        </li>
                                    </ul>
                                    <div class="nbd-text-color-picker" id="nbd-stage-bg-color-picker" style="top: 20px; left: 20px;"
                                         ng-class="stageBgColorPicker.status ? 'active' : ''">
                                        <spectrum-colorpicker
                                            ng-model="stageBgColorPicker.currentColor"
                                            options="{
                                            preferredFormat: 'hex',
                                            color: '#fff',
                                            flat: true,
                                            showButtons: false,
                                            showInput: true,
                                            containerClassName: 'nbd-sp'
                                            }">
                                        </spectrum-colorpicker>
                                        <div>
                                            <button class="nbd-button"
                                                    ng-click="changeBackgroundCanvas(stageBgColorPicker.currentColor);">
                                                        <?php _e('Choose', 'web-to-print-online-designer'); ?>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="pinned-palette default-palette" ng-if="settings['nbdesigner_show_all_color'] == 'yes'">
                                    <h3 class="color-palette-label" style="font-size: 12px;font-weight: 500;margin: 0 0 10px;text-transform: uppercase;text-align: left;"><?php _e('Default palette', 'web-to-print-online-designer'); ?></h3>
                                    <ul class="main-color-palette tab-scroll" ng-repeat="palette in resource.defaultPalette" style="margin-bottom: 5px; max-height: 80px">
                                        <li ng-class="{'first-left': $first, 'last-right': $last}"
                                            ng-repeat="color in palette track by $index"
                                            ng-click="changeBackgroundCanvas(color)"
                                            class="color-palette-item"
                                            data-color="{{color}}"
                                            title="{{color}}"
                                            ng-style="{'background': color}">
                                        </li>
                                    </ul>
                                </div>
                                <div class="pinned-palette default-palette" ng-if="settings['nbdesigner_show_all_color'] == 'no'">
                                    <h3 class="color-palette-label"><?php _e('Color palette', 'web-to-print-online-designer'); ?></h3>
                                    <ul class="main-color-palette" style="margin-bottom: 15px;">
                                        <li ng-repeat="color in __colorPalette track by $index" ng-class="{'first-left': $first, 'last-right': $last}" ng-click="changeBackgroundCanvas(color)" class="color-palette-item" data-color="{{color}}" title="{{color}}" ng-style="{'background': color}"></li>
                                    </ul>   
                                </div>
                                <div><button class="nbd-button" ng-click="removeBackgroundCanvas()"><?php _e('Remove background', 'web-to-print-online-designer'); ?></button></div>
                            </div>                            
                        </div>
                    </div>
                    <div class="content-item type-shapes" data-type="shapes" id="nbd-shape-wrap">
                        <div class="mansory-wrap">
                            <div nbd-drag="art.url" extenal="true" type="svg" class="mansory-item" ng-click="addSvgFromMedia(art)" ng-repeat="art in resource.shape.data" repeat-end="onEndRepeat('shape')"><img ng-src="{{art.url}}"><span class="photo-desc">{{art.name}}</span></div>
                        </div>                        
                    </div>
                    <div class="content-item type-icons" data-type="icons" id="nbd-icon-wrap">
                        <div class="mansory-wrap">
                            <div nbd-drag="art.url" extenal="true" type="svg" class="mansory-item" ng-click="addSvgFromMedia(art, $index)" ng-repeat="art in resource.icon.data" repeat-end="onEndRepeat('icon')">
                                <div style="position: relative;">
                                    <img ng-src="{{art.url}}" /><span class="photo-desc">{{art.name}}</span>
                                    <?php if(!$valid_license): ?>
                                    <span class="nbd-pro-mark-wrap" ng-if="$index > 20">
                                        <svg class="nbd-pro-mark" fill="#F3B600" xmlns="http://www.w3.org/2000/svg" viewBox="-505 380 12 10"><path d="M-503 388h8v1h-8zM-494 382.2c-.4 0-.8.3-.8.8 0 .1 0 .2.1.3l-2.3.7-1.5-2.2c.3-.2.5-.5.5-.8 0-.6-.4-1-1-1s-1 .4-1 1c0 .3.2.6.5.8l-1.5 2.2-2.3-.8c0-.1.1-.2.1-.3 0-.4-.3-.8-.8-.8s-.8.4-.8.8.3.8.8.8h.2l.8 3.3h8l.8-3.3h.2c.4 0 .8-.3.8-.8 0-.4-.4-.7-.8-.7z"></path></svg>
                                        <?php _e('Pro','web-to-print-online-designer'); ?>
                                    </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>                          
                    </div>
                    <div class="content-item type-lines" data-type="lines" id="nbd-line-wrap">
                        <div class="mansory-wrap">
                            <div nbd-drag="art.url" extenal="true" type="svg" class="mansory-item" ng-click="addSvgFromMedia(art)" ng-repeat="art in resource.line.data" repeat-end="onEndRepeat('line')"><img ng-src="{{art.url}}"><span class="photo-desc">{{art.name}}</span></div>
                        </div>                          
                    </div>
                    <div class="content-item type-qrcode" data-type="qr-code">
                        <div class="main-type">
                            <div class="main-input">
                                <input ng-model="resource.qrText" type="text" class="nbd-input input-qrcode" name="qr-code" placeholder="https://yourcompany.com">
                            </div>
                            <button ng-class="resource.qrText != '' ? '' : 'nbd-disabled'" class="nbd-button" ng-click="addQrCode()"><?php _e('Create QRCode','web-to-print-online-designer'); ?></button>
                            <button ng-class="resource.qrText != '' ? '' : 'nbd-disabled'" class="nbd-button" ng-click="addBarCode()"><?php _e('Create BarCode','web-to-print-online-designer'); ?></button>
                            <div class="main-qrcode">
                                
                            </div>
                            <svg id="barcode"></svg>
                        </div>
                    </div>
                </div>
                <div class="nbdesigner-gallery" id="nbdesigner-gallery">
                </div>
                <div class="loading-photo" style="width: 40px; height: 40px;">
                    <svg class="circular" viewBox="25 25 50 50">
                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                    </svg>
                </div>           
            </div>
            <div class="info-support">
                <span>Facebook</span>
                <i class="icon-nbd icon-nbd-clear close-result-loaded" ng-click="onClickTab('', 'element')"></i>
            </div>
        </div>
    </div>
</div>