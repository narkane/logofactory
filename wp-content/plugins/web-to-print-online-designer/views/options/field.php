<?php if (!defined('ABSPATH')) exit; ?>
<div class="nbd-field-wrap" ng-repeat="(fieldIndex, field) in options.fields" id="{{field.id}}">
    <div class="nbd-nav">
        <div ng-dblclick="toggleExpandField($index, $event)" style="cursor: pointer;" title="<?php _e('Double click to expand option', 'web-to-print-online-designer') ?>">
            <ul nbd-tab ng-class="field.isExpand ? '' : 'left'" class="nbd-tab-nav">
                <li class="nbd-field-tab active" data-target="tab-general"><?php _e('General', 'web-to-print-online-designer') ?></li>
                <li class="nbd-field-tab" data-target="tab-conditional"><?php _e('Conditinal', 'web-to-print-online-designer') ?></li>
                <li class="nbd-field-tab" data-target="tab-appearance"><?php _e('Appearance', 'web-to-print-online-designer') ?></li>
                <li ng-if="field.nbd_type" class="nbd-field-tab" data-target="tab-online-design"><?php _e('Online design', 'web-to-print-online-designer'); ?></li>
                <li ng-if="field.nbpb_type" class="nbd-field-tab" data-target="tab-product-builder"><?php _e('Product builder', 'web-to-print-online-designer'); ?></li>
            </ul>
            <input ng-hide="true" ng-model="field.id" name="options[fields][{{fieldIndex}}][id]"/>
            <span class="nbd-field-name" ng-class="field.isExpand ? '' : 'left'">
                <span>{{field.general.title.value}}</span>
            </span>
            <span class="nbdesigner-right field-action">
                <span class="nbo-type-label-wrap"><span class="nbo-type-label" ng-class="get_field_class((field.nbd_type != '' && field.nbd_type != null) ? field.nbd_type : field.nbpb_type)">{{get_field_type((field.nbd_type != '' && field.nbd_type != null) ? field.nbd_type : field.nbpb_type)}}</span></span>
                <span class="nbo-sort-group">
                    <span ng-click="sort_field($index, 'up')" class="dashicons dashicons-arrow-up nbo-sort-up nbo-sort" title="<?php _e('Up', 'web-to-print-online-designer') ?>"></span>
                    <span ng-click="sort_field($index, 'down')" class="dashicons dashicons-arrow-down nbo-sort-down nbo-sort" title="<?php _e('Down', 'web-to-print-online-designer') ?>"></span>
                </span>
                <a class="nbd-field-btn nbd-mini-btn button" ng-click="delete_field($index)" title="<?php _e('Delete', 'web-to-print-online-designer'); ?>"><span class="dashicons dashicons-no-alt"></span></a>
                <a class="nbd-field-btn nbd-mini-btn button" ng-click="copy_field($index)" title="<?php _e('Copy', 'web-to-print-online-designer'); ?>"><span class="dashicons dashicons-admin-page"></span></a>
                <a class="nbd-field-btn nbd-mini-btn button" ng-click="toggleExpandField($index, $event)" title="<?php _e('Expand', 'web-to-print-online-designer'); ?>"><span ng-show="!field.isExpand" class="dashicons dashicons-arrow-down"></span><span ng-show="field.isExpand" class="dashicons dashicons-arrow-up"></span></a>
            </span>
        </div>   
        <div class="clear"></div>
    </div>
    <div ng-show="field.isExpand">
        <div class="tab-general nbd-field-content active">
            <div class="nbd-field-info">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Option name', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <input required type="text" name="options[fields][{{fieldIndex}}][general][title]" ng-model="field.general.title.value">
                    </div>
                </div>
            </div>
            <div class="nbd-field-info">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Description', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <textarea name="options[fields][{{fieldIndex}}][general][description]" ng-model="field.general.description.value"></textarea>
                    </div>
                </div>
            </div> 
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.data_type)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Data type', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <select name="options[fields][{{fieldIndex}}][general][data_type]" ng-model="field.general.data_type.value" ng-change="update_price_type(fieldIndex)" >
                            <option ng-repeat="op in field.general.data_type.options" value="{{op.key}}">{{op.text}}</option>
                        </select>                        
                    </div>
                </div>
            </div>
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.input_type)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Input type', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <select name="options[fields][{{fieldIndex}}][general][input_type]" ng-model="field.general.input_type.value" ng-change="update_price_type(fieldIndex)" >
                            <option ng-repeat="op in field.general.input_type.options" value="{{op.key}}">{{op.text}}</option>
                        </select>                        
                    </div>
                </div>
            </div> 
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.input_option)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Input option', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <table class="nbd-table">
                            <tr>
                                <th><?php _e('Min', 'web-to-print-online-designer'); ?></th>
                                <th><?php _e('Max', 'web-to-print-online-designer'); ?></th>
                                <th><?php _e('Step', 'web-to-print-online-designer'); ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <input class="nbd-short-ip" type="text" string-to-number ng-model="field.general.input_option.value.min" name="options[fields][{{fieldIndex}}][general][input_option][min]"/>
                                </td>
                                <td>
                                    <input class="nbd-short-ip" type="text" string-to-number ng-model="field.general.input_option.value.max" name="options[fields][{{fieldIndex}}][general][input_option][max]"/>
                                </td>
                                <td>
                                    <input class="nbd-short-ip" type="text" string-to-number ng-model="field.general.input_option.value.step" name="options[fields][{{fieldIndex}}][general][input_option][step]"/>
                                </td>                                
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.text_option)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Text option', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <table class="nbd-table">
                            <tr>
                                <th><?php _e('Min length', 'web-to-print-online-designer'); ?></th>
                                <th><?php _e('Max length', 'web-to-print-online-designer'); ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <input class="nbd-short-ip" type="text" string-to-number ng-model="field.general.text_option.value.min" name="options[fields][{{fieldIndex}}][general][text_option][min]"/>
                                </td>
                                <td>
                                    <input class="nbd-short-ip" type="text" string-to-number ng-model="field.general.text_option.value.max" name="options[fields][{{fieldIndex}}][general][text_option][max]"/>
                                </td>                               
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.upload_option)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Upload file option', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <table class="nbd-table">
                            <tr>
                                <th><?php _e('Min size', 'web-to-print-online-designer'); ?></th>
                                <th><?php _e('Max size', 'web-to-print-online-designer'); ?></th>
                                <th><?php _e('Allow type', 'web-to-print-online-designer'); ?></th>
                            </tr>
                            <tr>
                                <td>
                                    <input class="nbd-short-ip" type="text" string-to-number ng-model="field.general.upload_option.value.min_size" name="options[fields][{{fieldIndex}}][general][upload_option][min_size]"/> MB
                                </td>
                                <td>
                                    <input class="nbd-short-ip" type="text" string-to-number ng-model="field.general.upload_option.value.max_size" name="options[fields][{{fieldIndex}}][general][upload_option][max_size]"/> MB
                                </td>  
                                <td>
                                    <input class="nbd-short-ip" type="text" ng-model="field.general.upload_option.value.allow_type" name="options[fields][{{fieldIndex}}][general][upload_option][allow_type]"/>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="nbd-field-info">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Enabled', 'web-to-print-online-designer'); ?></b> <nbd-tip data-tip="<?php _e('Choose whether the option is enabled or not.', 'web-to-print-online-designer'); ?>" ></nbd-tip></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <select name="options[fields][{{fieldIndex}}][general][enabled]" ng-model="field.general.enabled.value">
                            <option ng-repeat="op in field.general.enabled.options" value="{{op.key}}">{{op.text}}</option>
                        </select>                        
                    </div>
                </div>
            </div>
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.required)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Required', 'web-to-print-online-designer'); ?></b> <nbd-tip data-tip="<?php _e('Choose whether the option is required or not.', 'web-to-print-online-designer'); ?>" ></nbd-tip></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <select name="options[fields][{{fieldIndex}}][general][required]" ng-model="field.general.required.value">
                            <option ng-repeat="op in field.general.required.options" value="{{op.key}}">{{op.text}}</option>
                        </select>                        
                    </div>
                </div>
            </div>
            <div class="nbd-field-info">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Price type', 'web-to-print-online-designer'); ?></b> <nbd-tip data-tip="<?php _e('Here you can choose how the price is calculated. Depending on the field there various types you can choose.', 'web-to-print-online-designer'); ?>" ></nbd-tip></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <select name="options[fields][{{fieldIndex}}][general][price_type]" ng-model="field.general.price_type.value">
                            <option ng-repeat="op in field.general.price_type.options" ng-if="check_option_depend(fieldIndex, op.depend)" value="{{op.key}}">{{op.text}}</option>
                        </select>                        
                    </div>
                </div>
            </div> 
            <div class="nbd-field-info">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Depend quantity breaks', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <select name="options[fields][{{fieldIndex}}][general][depend_quantity]" ng-model="field.general.depend_quantity.value">
                            <option ng-repeat="op in field.general.depend_quantity.options" value="{{op.key}}">{{op.text}}</option>
                        </select>                        
                    </div>
                </div>
            </div>            
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.price)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Additional Price', 'web-to-print-online-designer'); ?></b> <nbd-tip data-tip="<?php _e('Enter the price for this field or leave it blank for no price.', 'web-to-print-online-designer'); ?>" ></nbd-tip></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div>
                        <input type="number" name="options[fields][{{fieldIndex}}][general][price]" ng-model="field.general.price.value">
                    </div>
                </div>
            </div> 
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.price_breaks)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Price depend quantity breaks', 'web-to-print-online-designer'); ?></b></label></div>
                </div>
                <div class="nbd-field-info-2">
                    <div class="nbd-table-wrap">
                        <table class="nbd-table">
                            <tr>
                                <th><?php _e('Quantity break', 'web-to-print-online-designer'); ?></th>
                                <th ng-repeat="break in options.quantity_breaks">{{break.val}}</th>
                            </tr>
                            <tr>
                                <td><?php _e('Additional Price', 'web-to-print-online-designer'); ?></td>
                                <td ng-repeat="break in options.quantity_breaks">
                                    <input class="nbd-short-ip" type="text" ng-model="field.general.price_breaks.value[$index]" name="options[fields][{{fieldIndex}}][general][price_breaks][{{$index}}]" />
                                </td>
                            </tr>
                        </table>                        
                    </div>
                </div>
            </div>  
            <div class="nbd-field-info" ng-show="check_depend(field.general, field.general.attributes)">
                <div class="nbd-field-info-1">
                    <div><label><b><?php _e('Attributes', 'web-to-print-online-designer'); ?></b> <nbd-tip data-tip="<?php _e('Attributes let you define extra product data, such as size or color.', 'web-to-print-online-designer'); ?>" ></nbd-tip></label></div>
                </div>  
                <div class="nbd-field-info-2">
                    <div>
                        <div ng-repeat="(opIndex, op) in field.general.attributes.options" class="nbd-attribute-wrap">
                            <div ng-show="op.isExpand" class="nbd-attribute-img-wrap">
                                <div><?php _e('Swatch type', 'web-to-print-online-designer'); ?> <!--<sup class="nbs-sup-des">1</sup>--></div>
                                <div>
                                    <select ng-model="op.preview_type" style="width: 110px;" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][preview_type]">
                                        <option value="i"><?php _e('Image', 'web-to-print-online-designer'); ?></option>
                                        <option value="c"><?php _e('Color', 'web-to-print-online-designer'); ?></option>                                                                    
                                    </select>   
                                </div>
                                <div class="nbd-attribute-img-inner" ng-show="op.preview_type == 'i'">
                                    <span class="dashicons dashicons-no remove-attribute-img" ng-click="remove_attribute_image(fieldIndex, $index, 'image', 'image_url')"></span>
                                    <input ng-hide="true" ng-model="op.image" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][image]"/>
                                    <img title="<?php _e('Click to change image', 'web-to-print-online-designer'); ?>" ng-click="set_attribute_image(fieldIndex, $index, 'image', 'image_url')" ng-src="{{op.image != 0 ? op.image_url : '<?php echo NBDESIGNER_ASSETS_URL . 'images/placeholder.png' ?>'}}" />
                                </div>
                                <div class="nbd-attribute-color-inner" ng-show="op.preview_type == 'c'">
                                    <input type="text" name="options[fields][{{fieldIndex}}][general][attributes][options][{{$index}}][color]" ng-model="op.color" class="nbd-color-picker" nbd-color-picker="op.color"/>
                                </div>
                                <div ng-if="field.appearance.change_image_product.value == 'y'">
                                    <div><?php _e('Product image', 'web-to-print-online-designer'); ?>  <!--<sup class="nbs-sup-des">2</sup>--></div>
                                    <div class="nbd-attribute-img-inner">
                                        <span class="dashicons dashicons-no remove-attribute-img" ng-click="remove_attribute_image(fieldIndex, $index, 'product_image', 'product_image_url')"></span>
                                        <input ng-hide="true" ng-model="op.product_image" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][product_image]"/>
                                        <img title="<?php _e('Click to change image', 'web-to-print-online-designer'); ?>" ng-click="set_attribute_image(fieldIndex, $index, 'product_image', 'product_image_url')" ng-src="{{op.product_image_url ? op.product_image_url : '<?php echo NBDESIGNER_ASSETS_URL . 'images/placeholder.png' ?>'}}" />
                                    </div>
                                </div>
                            </div>    
                            <div ng-show="op.isExpand" class="nbd-attribute-content-wrap">
                                <div><?php _e('Title', 'web-to-print-online-designer'); ?></div>
                                <div class="nbd-attribute-name">
                                    <input required type="text" value="{{op.name}}" ng-model="op.name" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][name]"/>
                                    <label><input type="checkbox" name="options[fields][{{fieldIndex}}][general][attributes][options][{{$index}}][selected]" ng-checked="op.selected" ng-click="seleted_attribute(fieldIndex, 'attributes', $index)"/> <?php _e('Default', 'web-to-print-online-designer'); ?></label>
                                </div>
                                <div class="nbd-margin-10"></div>
                                <div><?php _e('Description', 'web-to-print-online-designer'); ?></div>
                                <div class="nbd-attribute-name">
                                    <textarea placeholder="<?php _e('Description', 'web-to-print-online-designer'); ?>" value="{{op.des}}" ng-model="op.des" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][des]"></textarea>
                                </div> 
                                <div><?php _e('Price', 'web-to-print-online-designer'); ?></div>
                                <div ng-show="field.general.depend_quantity.value != 'y'">
                                    <div><?php _e('Additional Price', 'web-to-print-online-designer'); ?></div>
                                    <div>
                                        <input name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][price][0]" class="nbd-short-ip" type="text" ng-model="op.price[0]"/>
                                    </div>
                                </div>
                                <div class="nbd-table-wrap" ng-show="field.general.depend_quantity.value == 'y'" >
                                    <table class="nbd-table">
                                        <tr>
                                            <th><?php _e('Quantity break', 'web-to-print-online-designer'); ?></th>
                                            <th ng-repeat="break in options.quantity_breaks">{{break.val}}</th>
                                        </tr>
                                        <tr>
                                            <td><?php _e('Additional Price', 'web-to-print-online-designer'); ?></td>
                                            <td ng-repeat="break in options.quantity_breaks">
                                                <input name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][price][{{$index}}]" class="nbd-short-ip" type="text" ng-model="op.price[$index]"/>
                                            </td>
                                        </tr>                                                                        
                                    </table>
                                </div> 
                                <div class="nbd-margin-10"></div>
                                <div class="nbd-enable-subattribute" ng-hide="field.nbd_type != '' && field.nbd_type != null">
                                    <label><input ng-click="toggle_enable_subattr(fieldIndex, $index)" type="checkbox" name="options[fields][{{fieldIndex}}][general][attributes][options][{{$index}}][enable_subattr]" ng-true-value="'on'" ng-false-value="'off'" ng-model="op.enable_subattr" ng-checked="op.enable_subattr" /> <?php _e('Enable sub attributes', 'web-to-print-online-designer'); ?></label>
                                </div>
                                <div class="nbd-margin-10"></div>
                                <div class="nbd-subattributes-wrapper" ng-if="op.enable_subattr === true || op.enable_subattr == 'on'">
                                    <div class="nbd-field-info">
                                        <div class="nbd-field-info-1">
                                            <div><label><b><?php _e('Sub attributes type', 'web-to-print-online-designer'); ?></b></label></div>
                                        </div>
                                        <div class="nbd-field-info-2">
                                            <div>
                                                <select style="width: 150px;" name="options[fields][{{fieldIndex}}][general][attributes][options][{{$index}}][sattr_display_type]" ng-model="op.sattr_display_type" >
                                                    <option value="d"><?php _e('Dropdown', 'web-to-print-online-designer'); ?></option>
                                                    <option value="r"><?php _e('Radio button', 'web-to-print-online-designer'); ?></option>
                                                    <option value="s"><?php _e('Swatch', 'web-to-print-online-designer'); ?></option>
                                                    <option value="l"><?php _e('Label', 'web-to-print-online-designer'); ?></option>
                                                </select>                        
                                            </div>
                                        </div>
                                    </div>
                                    <div ng-repeat="(sopIndex, sop) in op.sub_attributes" class="nbd-subattributes-wrap">
                                        <div ng-show="sop.isExpand" class="nbd-attribute-img-wrap">
                                            <div><?php _e('Swatch type', 'web-to-print-online-designer'); ?></div>
                                            <div>
                                                <select ng-model="sop.preview_type" style="width: 110px;" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][preview_type]">
                                                    <option value="i"><?php _e('Image', 'web-to-print-online-designer'); ?></option>
                                                    <option value="c"><?php _e('Color', 'web-to-print-online-designer'); ?></option>                                                                    
                                                </select>   
                                            </div>
                                            <div class="nbd-attribute-img-inner" ng-show="sop.preview_type == 'i'">
                                                <span class="dashicons dashicons-no remove-attribute-img" ng-click="remove_sub_attribute_image(fieldIndex, opIndex, sopIndex)"></span>
                                                <input ng-hide="true" ng-model="sop.image" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][image]"/>
                                                <img title="<?php _e('Click to change image', 'web-to-print-online-designer'); ?>" ng-click="set_sub_attribute_image(fieldIndex, opIndex, sopIndex)" ng-src="{{sop.image != 0 ? sop.image_url : '<?php echo NBDESIGNER_ASSETS_URL . 'images/placeholder.png' ?>'}}" />
                                            </div>
                                            <div class="nbd-attribute-color-inner" ng-show="sop.preview_type == 'c'">
                                                <input type="text" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][color]" ng-model="sop.color" class="nbd-color-picker" nbd-color-picker="sop.color"/>
                                            </div>
                                        </div>
                                        <div ng-show="sop.isExpand" class="nbd-attribute-content-wrap">
                                            <div><?php _e('Title', 'web-to-print-online-designer'); ?></div>
                                            <div class="nbd-attribute-name">
                                                <input required type="text" value="{{sop.name}}" ng-model="sop.name" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][name]"/>
                                                <label><input type="checkbox" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][selected]" ng-checked="sop.selected" ng-click="seleted_sub_attribute(fieldIndex, 'attributes', opIndex, sopIndex)"/> <?php _e('Default', 'web-to-print-online-designer'); ?></label>
                                            </div>
                                            <div class="nbd-margin-10"></div>
                                            <div><?php _e('Description', 'web-to-print-online-designer'); ?></div>
                                            <div class="nbd-attribute-name">
                                                <textarea placeholder="<?php _e('Description', 'web-to-print-online-designer'); ?>" value="{{sop.des}}" ng-model="sop.des" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][des]"></textarea>
                                            </div>
                                            <div><?php _e('Price', 'web-to-print-online-designer'); ?></div>
                                            <div ng-show="field.general.depend_quantity.value != 'y'">
                                                <div><?php _e('Additional Price', 'web-to-print-online-designer'); ?></div>
                                                <div>
                                                    <input name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][price][0]" class="nbd-short-ip" type="text" ng-model="sop.price[0]"/>
                                                </div>
                                            </div>
                                            <div class="nbd-table-wrap" ng-show="field.general.depend_quantity.value == 'y'" >
                                                <table class="nbd-table">
                                                    <tr>
                                                        <th><?php _e('Quantity break', 'web-to-print-online-designer'); ?></th>
                                                        <th ng-repeat="break in options.quantity_breaks">{{break.val}}</th>
                                                    </tr>
                                                    <tr>
                                                        <td><?php _e('Additional Price', 'web-to-print-online-designer'); ?></td>
                                                        <td ng-repeat="break in options.quantity_breaks">
                                                            <input name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][sub_attributes][{{sopIndex}}][price][{{$index}}]" class="nbd-short-ip" type="text" ng-model="sop.price[$index]"/>
                                                        </td>
                                                    </tr>                                                                        
                                                </table>
                                            </div>
                                        </div>
                                        <div ng-show="!sop.isExpand" class="nbd-attribute-name-preview">{{sop.name}}</div>
                                        <div class="nbd-attribute-action">
                                            <span class="nbo-sort-group">
                                                <span ng-click="sort_sub_attribute(fieldIndex, opIndex, sopIndex, 'up')" class="dashicons dashicons-arrow-up nbo-sort-up nbo-sort" title="<?php _e('Up', 'web-to-print-online-designer') ?>"></span>
                                                <span ng-click="sort_sub_attribute(fieldIndex, opIndex, sopIndex, 'down')" class="dashicons dashicons-arrow-down nbo-sort-down nbo-sort" title="<?php _e('Down', 'web-to-print-online-designer') ?>"></span>
                                            </span>
                                            <a class="button nbd-mini-btn"  ng-click="remove_sub_attribute(fieldIndex, opIndex, sopIndex)" title="<?php _e('Delete', 'web-to-print-online-designer'); ?>"><span class="dashicons dashicons-no-alt"></span></a>
                                            <a class="button nbd-mini-btn"  ng-click="toggle_expand_sub_attribute(fieldIndex, opIndex, sopIndex)" title="<?php _e('Expend', 'web-to-print-online-designer'); ?>">
                                                <span ng-show="sop.isExpand" class="dashicons dashicons-arrow-up"></span>
                                                <span ng-show="!sop.isExpand" class="dashicons dashicons-arrow-down"></span>
                                            </a>
                                        </div>
                                    </div>
                                    <div><a class="button" ng-click="add_sub_attribute(fieldIndex, opIndex)"><span class="dashicons dashicons-plus"></span> <?php _e('Add sub attribute', 'web-to-print-online-designer'); ?></a></div>
                                </div>
                            </div> 
                            <div ng-show="!op.isExpand" class="nbd-attribute-name-preview">{{op.name}}</div>
                            <div class="nbd-attribute-action">
                                <span class="nbo-sort-group">
                                    <span ng-click="sort_attribute(fieldIndex, $index, 'up')" class="dashicons dashicons-arrow-up nbo-sort-up nbo-sort" title="<?php _e('Up', 'web-to-print-online-designer') ?>"></span>
                                    <span ng-click="sort_attribute(fieldIndex, $index, 'down')" class="dashicons dashicons-arrow-down nbo-sort-down nbo-sort" title="<?php _e('Down', 'web-to-print-online-designer') ?>"></span>
                                </span>
                                <a class="button nbd-mini-btn"  ng-click="remove_attribute(fieldIndex, 'attributes', $index)" title="<?php _e('Delete', 'web-to-print-online-designer'); ?>"><span class="dashicons dashicons-no-alt"></span></a>
                                <a class="button nbd-mini-btn"  ng-click="toggle_expand_attribute(fieldIndex, opIndex)" title="<?php _e('Expend', 'web-to-print-online-designer'); ?>">
                                    <span ng-show="op.isExpand" class="dashicons dashicons-arrow-up"></span>
                                    <span ng-show="!op.isExpand" class="dashicons dashicons-arrow-down"></span>
                                </a>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div><a class="button" ng-click="add_attribute(fieldIndex, 'attributes')"><span class="dashicons dashicons-plus"></span> <?php _e('Add attribute', 'web-to-print-online-designer'); ?></a></div>                        
                    </div>
                </div>
            </div>
        </div>
        <div class="tab-conditional nbd-field-content">
            <div class="nbd-field-info">
                <div class="nbd-field-info-1">
                    <div><b><?php _e('Field Conditional Logic', 'web-to-print-online-designer'); ?></b> <nbd-tip data-tip="<?php _e('Enable conditional logic for showing or hiding this field.', 'web-to-print-online-designer'); ?>"></nbd-tip></div>
                </div>  
                <div class="nbd-field-info-2">
                    <div>
                        <select ng-model="field.conditional.enable" style="width: 100px;" name="options[fields][{{fieldIndex}}][conditional][enable]">
                            <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
                            <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                        </select>
                    </div>
                    <div ng-if="field.conditional.enable == 'y'">
                        <div style="margin-top: 10px;">
                            <select ng-model="field.conditional.show" style="width: inherit;" name="options[fields][{{fieldIndex}}][conditional][show]">
                                <option value="y"><?php _e('Show', 'web-to-print-online-designer'); ?></option>
                                <option value="n"><?php _e('Hide', 'web-to-print-online-designer'); ?></option>
                            </select>
                            <?php _e('this field if', 'web-to-print-online-designer'); ?>
                            <select ng-model="field.conditional.logic" style="width: inherit;" name="options[fields][{{fieldIndex}}][conditional][logic]">
                                <option value="a"><?php _e('all', 'web-to-print-online-designer'); ?></option>
                                <option value="o"><?php _e('any', 'web-to-print-online-designer'); ?></option>
                            </select>  
                            <?php _e('of these rules match:', 'web-to-print-online-designer'); ?>
                        </div>
                        <div style="margin-top: 10px;">
                            <div ng-repeat="(cdIndex, con) in field.conditional.depend">
                                <select ng-model="con.id" style="width: 200px;" name="options[fields][{{fieldIndex}}][conditional][depend][{{cdIndex}}][id]">
<!--                                    <option ng-repeat="cf in options.fields | filter: { id: '!' + field.id }" value="{{cf.id}}">{{cf.general.title.value}}</option>-->
                                    <option ng-repeat="cf in options.fields | filter: { id: field.id }:excludeField" value="{{cf.id}}">{{cf.general.title.value}}</option>
                                </select>
                                <select ng-model="con.operator" style="width: 120px;" name="options[fields][{{fieldIndex}}][conditional][depend][{{cdIndex}}][operator]">
                                    <option value="i"><?php _e('is', 'web-to-print-online-designer'); ?></option>
                                    <option value="n"><?php _e('is not', 'web-to-print-online-designer'); ?></option>
                                    <option value="e"><?php _e('is empty', 'web-to-print-online-designer'); ?></option>
                                    <option value="ne"><?php _e('is not empty', 'web-to-print-online-designer'); ?></option>
                                </select>
                                <select ng-if="con.operator == 'i' || con.operator == 'n'" ng-model="con.val" ng-show="vf.id == con.id" ng-repeat="vf in options.fields | filter: {id: con.id}"  
                                    name="options[fields][{{fieldIndex}}][conditional][depend][{{cdIndex}}][val]" style="width: 200px;">
                                    <option ng-repeat="vop in vf.general.attributes.options" value="{{$index}}">{{vop.name}}</option>
                                </select> 
                                <a class="nbd-field-btn nbd-mini-btn button" ng-click="add_condition(fieldIndex)"><span class="dashicons dashicons-plus"></span></a>
                                <a class="nbd-field-btn nbd-mini-btn button" ng-click="delete_condition(fieldIndex, cdIndex)"><span class="dashicons dashicons-no-alt"></span></a>
                            </div>
                        </div>
                    </div>    
                </div>  
            </div>     
        </div>
        <div class="tab-appearance nbd-field-content">
            <div class="nbd-field-info" ng-repeat="(key, data) in field.appearance">
                <div class="nbd-field-info-1">
                    <div><label><b>{{data.title}}</b> <nbd-tip ng-if="data.description != ''" data-tip="{{data.description}}" ></nbd-tip></label></div>
                </div> 
                <div class="nbd-field-info-2">
                    <div ng-if="data.type == 'dropdown'">
                        <select name="options[fields][{{fieldIndex}}][appearance][{{key}}]" ng-model="data.value">
                            <option ng-repeat="op in data.options" value="{{op.key}}">{{op.text}}</option>
                        </select>        
                    </div>    
                    <div ng-if="data.type == 'dropdown_group'">
                        <select name="options[fields][{{fieldIndex}}][appearance][{{key}}]" ng-model="data.value">
                            <optgroup ng-repeat="gr in data.options" label={{gr.title}}>
                                <option ng-repeat="op in gr.value" value="{{op.key}}">{{op.text}}</option>
                            </optgroup>
                        </select>        
                    </div>                                                      
                </div>
            </div>     
        </div>
        <div class="tab-online-design nbd-field-content" ng-if="field.nbd_type">
            <input ng-hide="true" name="options[fields][{{fieldIndex}}][nbd_type]" ng-model="field.nbd_type">
            <ng-include src="field.nbd_template"></ng-include>
        </div>
        <div class="tab-product-builder nbd-field-content" ng-if="field.nbpb_type">
            <input ng-hide="true" name="options[fields][{{fieldIndex}}][nbpb_type]" ng-model="field.nbpb_type">
            <ng-include src="field.nbd_template"></ng-include>
        </div>
    </div>
</div>
<div><a class="button" ng-click="add_field()"><span class="dashicons dashicons-plus"></span> <?php _e('Add Field', 'web-to-print-online-designer'); ?></a></div>
<?php echo '<script type="text/ng-template" id="nbd.page">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Page display', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][page_display]" ng-model="field.general.page_display">
                <option value="1"><?php _e('Each page on a design stage', 'web-to-print-online-designer'); ?></option>
                <option value="2"><?php _e('Two pages on a design stage', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Exclude page', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][exclude_page]" ng-model="field.general.exclude_page">
                <option value="0"><?php _e('None', 'web-to-print-online-designer'); ?></option>
                <option value="2"><?php _e('Cover pages', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
<?php echo '</script>'; ?>
<?php echo '<script type="text/ng-template" id="nbd.color">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Background type', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][attributes][bg_type]" ng-model="field.general.attributes.bg_type">
                <option value="i"><?php _e('Image', 'web-to-print-online-designer'); ?></option>
                <option value="c"><?php _e('Color', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Number of sides', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <input class="nbd-short-ip" name="options[fields][{{fieldIndex}}][general][attributes][number_of_sides]" string-to-number type="number" min="1" step="1" ng-model="field.general.attributes.number_of_sides" />
        </div>
    </div>
    <div class="nbd-field-info" ng-if="field.general.attributes.bg_type == 'c'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Backgrund sides', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table" style="text-align: center;">
                    <tbody>
                        <tr ng-repeat="(opIndex, op) in field.general.attributes.options">
                            <th>{{op.name}}</th>
                            <td>
                                <input type="text" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][bg_color]" ng-model="op.bg_color" class="nbd-color-picker" nbd-color-picker="op.bg_color"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="nbd-field-info" ng-if="field.general.attributes.bg_type == 'i'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Sides background', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table" style="text-align: center;">
                    <tbody>
                        <tr ng-repeat="(opIndex, op) in field.general.attributes.options">
                            <th>{{op.name}}</th>
                            <td ng-repeat="n in [] | range:field.general.attributes.number_of_sides">
                                <input ng-hide="true" ng-model="op.bg_image[n]" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][bg_image][{{n}}]"/>
                                <img class="bg_od_preview" title="<?php _e('Click to change image', 'web-to-print-online-designer'); ?>" ng-click="set_attribute_image(fieldIndex, opIndex, 'bg_image', 'bg_image_url', n)" ng-src="{{op.bg_image[n] != undefined ? op.bg_image_url[n] : '<?php echo NBDESIGNER_ASSETS_URL . 'images/placeholder.png' ?>'}}" />  
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php echo '</script>'; ?>
<?php echo '<script type="text/ng-template" id="nbd.size">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div>
                <label>
                    <b><?php _e('Use a same online design config', 'web-to-print-online-designer'); ?></b>
                    <nbd-tip data-tip="<?php _e('All attributes have a same online design config ( product width, height, area design width, height, left, top ).', 'web-to-print-online-designer'); ?>" ></nbd-tip>
                </label>
            </div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][attributes][same_size]" ng-model="field.general.attributes.same_size">
                <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
    <div class="nbd-field-info" ng-if="field.general.attributes.same_size == 'n'">
        <div><b><?php _e('Online design config:', 'web-to-print-online-designer'); ?></b></div>
        <div class="nbd-table-wrap">
            <table class="nbd-table" style="text-align: center;">
                <thead>
                    <tr>
                        <th></th>
                        <th><?php _e('Product width', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Product height', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Design width', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Design height', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Design top', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Design left', 'web-to-print-online-designer'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="(opIndex, op) in field.general.attributes.options">
                        <th>{{op.name}}</th>
                        <td><input string-to-number required class="nbd-short-ip" ng-model="op.product_width" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][product_width]" /></td>
                        <td><input string-to-number required class="nbd-short-ip" ng-model="op.product_height" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][product_height]" /></td>
                        <td><input string-to-number required class="nbd-short-ip" ng-model="op.real_width" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][real_width]" /></td>
                        <td><input string-to-number required class="nbd-short-ip" ng-model="op.real_height" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][real_height]" /></td>
                        <td><input string-to-number required class="nbd-short-ip" ng-model="op.real_top" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][real_top]" /></td>
                        <td><input string-to-number required class="nbd-short-ip" ng-model="op.real_left" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][real_left]" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
<?php echo '</script>'; ?>
<script type="text/ng-template" id="nbd.dpi">
</script>
<script type="text/ng-template" id="nbd.area">
</script>
<script type="text/ng-template" id="nbd.orientation">
</script>
<?php echo '<script type="text/ng-template" id="nbd.dimension">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Dimension range:', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <table class="nbd-table">
                <thead>
                    <tr>
                        <th></th>
                        <th><?php _e('Min', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Max', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Step', 'web-to-print-online-designer'); ?></th>
                        <th><?php _e('Default value', 'web-to-print-online-designer'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th><?php _e('Width', 'web-to-print-online-designer'); ?></th>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.min_width" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][min_width]" /></td>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.max_width" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][max_width]" /></td>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.step_width" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][step_width]" /></td>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.default_width" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][default_width]" /></td>
                    </tr>
                    <tr>
                        <th><?php _e('Height', 'web-to-print-online-designer'); ?></th>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.min_height" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][min_height]" /></td>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.max_height" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][max_height]" /></td>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.step_height" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][step_height]" /></td>
                        <td><input string-to-number class="nbd-short-ip" ng-model="field.general.default_height" type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][default_height]" /></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div class="nbd-field-info" style="margin-top: 10px;">
        <div class="nbd-field-info-1">
            <div>
                <label>
                    <b><?php _e('Enable measure price base on design area', 'web-to-print-online-designer'); ?></b>
                    <nbd-tip data-tip="<?php _e('Measure price base on design area.', 'web-to-print-online-designer'); ?>" ></nbd-tip>
                </label>
            </div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][mesure]" ng-model="field.general.mesure">
                <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
    <div class="nbd-field-info" ng-if="field.general.mesure == 'y'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Price base on design area:', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <table class="nbd-table nbo-measure-range">
                <thead>
                    <tr>
                        <th class="check-column">
                            <input class="nbo-measure-range-select-all" type="checkbox" ng-click="select_all_measurement_range(fieldIndex, $event)">
                        </th>
                        <th class="range-column" style="padding-right: 30px;">
                            <span class="column-title" data-text="<?php esc_attr_e( 'Measurement Range', 'web-to-print-online-designer' ); ?>"><?php _e( 'Measurement Range', 'web-to-print-online-designer' ); ?></span>
                            <nbd-tip data-tip="<?php _e( 'Configure the starting-ending range, inclusive, of measurements to match this rule.  The first matched rule will be used to determine the price.  The final rule can be defined without an ending range to match all measurements greater than or equal to its starting range.', 'web-to-print-online-designer'); ?>" ></nbd-tip>
                        </th>
                        <th class="price-column">
                            <span><?php echo _e('Price per Unit', 'web-to-print-online-designer'); ?> <?php echo ' ('.get_woocommerce_currency_symbol() . '/' . nbdesigner_get_option('nbdesigner_dimensions_unit').'<sup>2</sup>)'; ?></span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr ng-repeat="(mrIndex, mr) in field.general.mesure_range">
                        <td>
                            <input type="checkbox" class="nbo-measure-range-checkbox" ng-model="mr[3]">
                        </td>                
                        <td>
                            <span>
                                <span class="nbd-table-price-label"><?php echo _e('From', 'web-to-print-online-designer'); ?></span>
                                <input string-to-number type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][mesure_range][{{mrIndex}}][0]" ng-model="mr[0]" class="nbd-short-ip">
                            </span>   
                            <span>
                                <span class="nbd-table-price-label" style="margin-left: 10px;"><?php echo _e('To', 'web-to-print-online-designer'); ?></span>
                                <input string-to-number type="number" min="0" step="any" name="options[fields][{{fieldIndex}}][general][mesure_range][{{mrIndex}}][1]" ng-model="mr[1]" class="nbd-short-ip">
                            </span>                       
                        </td>
                        <td>
                            <input string-to-number type="number" step="any" name="options[fields][{{fieldIndex}}][general][mesure_range][{{mrIndex}}][2]" ng-model="mr[2]" class="nbd-short-ip">
                        </td>                
                    </tr> 
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3">
                            <button ng-click="add_measurement_range(fieldIndex)" style="float: left;" type="button" class="button button-primary nbd-pricing-table-add-rule"><?php _e( 'Add Rule', 'web-to-print-online-designer' ); ?></button>
                            <button ng-click="delete_measurement_ranges(fieldIndex, $event)" style="float: right;" type="button" class="button button-secondary nbd-pricing-table-delete-rules"><?php _e( 'Delete Selected', 'web-to-print-online-designer' ); ?></button>
                        </th>
                    </tr>
                </tfoot> 
            </table>
        </div>
    </div>
    <div class="nbd-field-info" style="margin-top: 10px;" ng-if="field.general.mesure == 'y'">
        <div class="nbd-field-info-1">
            <div>
                <label>
                    <b><?php _e('Calculate price base on number of sides/pages', 'web-to-print-online-designer'); ?></b>
                </label>
            </div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][mesure_base_pages]" ng-model="field.general.mesure_base_pages">
                <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
<?php echo '</script>'; ?>
<?php echo '<script type="text/ng-template" id="nbd.padding">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Padding value', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table" style="text-align: center;">
                    <thead>
                        <tr><th><?php _e('Option', 'web-to-print-online-designer'); ?></th><th><?php _e('Value', 'web-to-print-online-designer'); ?></th></tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="(opIndex, op) in field.general.attributes.options">
                            <td>{{op.name}}</td>
                            <td>
                                <input type="text" class="nbd-short-ip" ng-model="op.padding" name="options[fields][{{fieldIndex}}][general][attributes][options][{{opIndex}}][padding]"/>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php echo '</script>'; ?>
<?php echo '<script type="text/ng-template" id="nbd.nbpb_com">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Views', 'web-to-print-online-designer'); ?></b><nbd-tip data-tip="<?php _e('Add product view/side, example: Front, Back, Top, Inside... and use them for all product components.', 'web-to-print-online-designer'); ?>" ></nbd-tip></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table" style="text-align: center;">
                    <thead>
                        <tr>
                            <th><?php _e('View name', 'web-to-print-online-designer'); ?></th>
                            <th><?php _e('View base', 'web-to-print-online-designer'); ?></th>
                            <th><?php _e('Action', 'web-to-print-online-designer'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="(vIndex, view) in options.views">
                            <td><input style="width: 150px;" ng-model="view.name" type="text"/></td>
                            <td>
                                <div class="image-icon-wrap">
                                    <span class="dashicons dashicons-no remove-image-icon" ng-click="remove_view_base(vIndex)"></span>
                                    <img ng-click="set_view_base(vIndex)" ng-src="{{view.base != 0 ? view.base_url : '<?php echo NBDESIGNER_ASSETS_URL . 'images/placeholder.png' ?>'}}" />
                                </div>
                            </td>
                            <td>
                                <a class="button btn-primary nbd-mini-btn" ng-click="removeView(vIndex)" title="<?php _e('Delete View', 'web-to-print-online-designer'); ?>"><span class="dashicons dashicons-no-alt"></span></a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr><td colspan="3"><a class="button btn-primary" ng-click="addView()"><?php _e('Add View', 'web-to-print-online-designer'); ?></a></td></tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Component icon', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="image-icon-wrap">
                <span class="dashicons dashicons-no remove-image-icon" ng-click="remove_component_icon(fieldIndex)"></span>
                <input ng-hide="true" ng-model="field.general.component_icon" name="options[fields][{{fieldIndex}}][general][component_icon]"/>
                <img ng-click="set_component_icon(fieldIndex)" ng-src="{{field.general.component_icon != 0 ? field.general.component_icon_url : '<?php echo NBDESIGNER_ASSETS_URL . 'images/placeholder.png' ?>'}}" />
            </div>
        </div>
    </div>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div>
                <b><?php _e('Component configurations', 'web-to-print-online-designer'); ?></b>
                <nbd-tip data-tip="<?php _e('All images in the same view must have the same size.', 'web-to-print-online-designer'); ?>" ></nbd-tip>
            </div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table" style="text-align: center;">
                    <thead>
                        <tr>
                            <th rowspan="2"><?php _e('Attribute', 'web-to-print-online-designer'); ?></th>
                            <th rowspan="2"><?php _e('Sub attribute', 'web-to-print-online-designer'); ?></th>
                            <th colspan="{{options.views.length}}"><?php _e('View', 'web-to-print-online-designer'); ?></th>
                        </tr>
                        <tr>
                            <th ng-repeat="view in options.views">{{view.name}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="pbcon in field.general.pb_config_flat">
                            <td ng-if="pbcon.attr_rowspan > 0" rowspan="{{pbcon.attr_rowspan}}">{{field.general.attributes.options[pbcon.attr_index].name}}</td>
                            <td>{{pbcon.has_sattr ? field.general.attributes.options[pbcon.attr_index].sub_attributes[pbcon.sattr_index].name : ''}}</td>
                            <td ng-repeat="view in options.views" style="text-align: left;">
                                <label class="view-config">
                                    <?php _e('Show in view', 'web-to-print-online-designer'); ?>
                                    <input ng-model="field.general.pb_config[pbcon.attr_index][pbcon.sattr_index].views[$index].display" name="options[fields][{{fieldIndex}}][general][pb_config][{{pbcon.attr_index}}][{{pbcon.sattr_index}}][views][{{$index}}][display]" type="checkbox" />
                                </label>
                                <label class="view-config view-config-image">
                                    <?php _e('Image', 'web-to-print-online-designer'); ?>
                                    <div class="image-icon-wrap">
                                        <input ng-model="field.general.pb_config[pbcon.attr_index][pbcon.sattr_index].views[$index].image" name="options[fields][{{fieldIndex}}][general][pb_config][{{pbcon.attr_index}}][{{pbcon.sattr_index}}][views][{{$index}}][image]" ng-hide="true" />
                                        <span class="dashicons dashicons-no remove-image-icon" ng-click="remove_view_config_image(fieldIndex, pbcon.attr_index, pbcon.sattr_index, $index)"></span>
                                        <img ng-click="set_view_config_image(fieldIndex, pbcon.attr_index, pbcon.sattr_index, $index)" ng-src="{{field.general.pb_config[pbcon.attr_index][pbcon.sattr_index].views[$index].image != 0 ? field.general.pb_config[pbcon.attr_index][pbcon.sattr_index].views[$index].image_url : '<?php echo NBDESIGNER_ASSETS_URL . 'images/placeholder.png' ?>'}}" />
                                    </div>
                                </label>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php echo '</script>'; ?>
<?php echo '<script type="text/ng-template" id="nbd.nbpb_text">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Default text', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <input type="text" ng-model="field.general.nbpb_text_configs.default_text" name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][default_text]"/>
        </div>
    </div>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Allow change font family', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][allow_font_family]" ng-model="field.general.nbpb_text_configs.allow_font_family">
                <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
    <div class="nbd-field-info" ng-show="field.general.nbpb_text_configs.allow_font_family == 'y'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Allow all fonts', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][allow_all_font]" ng-model="field.general.nbpb_text_configs.allow_all_font">
                <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
            </select>
            <br /><?php _e('Manage fonts', 'web-to-print-online-designer'); ?> <a target="_blank" href="<?php echo esc_url(admin_url('admin.php?page=nbdesigner_manager_fonts')); ?>"><?php _e('here', 'web-to-print-online-designer'); ?></a>
        </div>
    </div>
    <div class="nbd-field-info" ng-show="field.general.nbpb_text_configs.allow_font_family == 'y' && field.general.nbpb_text_configs.allow_all_font == 'n'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Custom fonts', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <?php
                $custom_fonts = array();
                if(file_exists( NBDESIGNER_DATA_DIR . '/fonts.json') ){
                    $custom_fonts = (array)json_decode( file_get_contents( NBDESIGNER_DATA_DIR . '/fonts.json' ) );        
                }
            ?>
            <select nbd-select2 name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][custom_fonts][]" ng-model="field.general.nbpb_text_configs.custom_fonts" multiple="multiple">
                <?php foreach($custom_fonts as $font): ?>
                <option value="<?php echo $font->id; ?>"><?php echo $font->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="nbd-field-info" ng-show="field.general.nbpb_text_configs.allow_font_family == 'y' && field.general.nbpb_text_configs.allow_all_font == 'n'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Google fonts', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <?php
                $google_fonts = array();
                if(file_exists( NBDESIGNER_DATA_DIR . '/googlefonts.json') ){
                    $google_fonts = (array)json_decode( file_get_contents( NBDESIGNER_DATA_DIR . '/googlefonts.json' ) );        
                }
            ?>
            <select nbd-select2 name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][google_fonts][]" ng-model="field.general.nbpb_text_configs.google_fonts" multiple="multiple">
                <?php foreach($google_fonts as $font): ?>
                <option value="<?php echo $font->id; ?>"><?php echo $font->name; ?></option>
                <?php endforeach; ?>
            </select>
        </div>
    </div>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Allow change color', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][allow_change_color]" ng-model="field.general.nbpb_text_configs.allow_change_color">
                <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
    <div class="nbd-field-info" ng-show="field.general.nbpb_text_configs.allow_change_color == 'y'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Allow all colors', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <select name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][allow_all_color]" ng-model="field.general.nbpb_text_configs.allow_all_color">
                <option value="y"><?php _e('Yes', 'web-to-print-online-designer'); ?></option>
                <option value="n"><?php _e('No', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
    <div class="nbd-field-info" ng-show="field.general.nbpb_text_configs.allow_change_color == 'y' && field.general.nbpb_text_configs.allow_all_color == 'n'">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Colors', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table nbpb-text-configs" style="text-align: center;">
                    <thead>
                        <tr>
                            <th><?php _e('Color name', 'web-to-print-online-designer'); ?></th>
                            <th><?php _e('Color', 'web-to-print-online-designer'); ?></th>
                            <th><?php _e('Action', 'web-to-print-online-designer'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="(clIndex, color) in field.general.nbpb_text_configs.colors">
                            <td>
                                <input type="text" class="nbd-short-ip" ng-model="color.name" name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][colors][{{clIndex}}][name]"/>
                            </td>
                            <td>
                                <input type="text" class="nbd-short-ip" nbd-color-picker="color.color" ng-model="color.color" name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][colors][{{clIndex}}][color]"/>
                            </td>
                            <td>
                                <a class="button nbd-mini-btn" ng-click="remove_text_configs_color(fieldIndex, clIndex)" title="<?php _e('Delete', 'web-to-print-online-designer'); ?>"><span class="dashicons dashicons-no-alt"></span></a>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" style="text-align: left;">
                                <a ng-click="add_text_configs_color(fieldIndex)" class="button button-primary"><?php _e('Add color', 'web-to-print-online-designer'); ?></a>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Show in view', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table" style="text-align: center;">
                    <thead>
                        <tr>
                            <th ng-repeat="view in options.views">{{view.name}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td ng-repeat="view in options.views">
                                <input ng-model="field.general.nbpb_text_configs.views[$index].display" name="options[fields][{{fieldIndex}}][general][nbpb_text_configs][views][{{$index}}][display]" type="checkbox" />
                            </td>
                        </tr>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php echo '</script>'; ?>
<?php echo '<script type="text/ng-template" id="nbd.nbpb_image">'; ?>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><b><?php _e('Show in view', 'web-to-print-online-designer'); ?></b></div>
        </div>
        <div class="nbd-field-info-2">
            <div class="nbd-table-wrap">
                <table class="nbd-table" style="text-align: center;">
                    <thead>
                        <tr>
                            <th ng-repeat="view in options.views">{{view.name}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td ng-repeat="view in options.views">
                                <input ng-model="field.general.nbpb_image_configs.views[$index].display" name="options[fields][{{fieldIndex}}][general][nbpb_image_configs][views][{{$index}}][display]" type="checkbox" />
                            </td>
                        </tr>    
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php echo '</script>';