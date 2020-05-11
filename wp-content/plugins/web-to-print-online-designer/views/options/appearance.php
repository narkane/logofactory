<?php if (!defined('ABSPATH')) exit; ?>
<div class="nbd-field-info">
    <div class="nbd-field-info-1">
        <div><label><b><?php _e('Display type', 'web-to-print-online-designer'); ?></b></label></div>
    </div>
    <div class="nbd-field-info-2">
        <div>
            <select convert-to-number name="options[display_type]" ng-model="options.display_type">
                <option <?php selected( $options['display_type'], 1 ); ?> value="1"><?php _e('Default', 'web-to-print-online-designer'); ?></option>
                <option <?php selected( $options['display_type'], 2 ); ?> value="2"><?php _e('Price Matrix', 'web-to-print-online-designer'); ?></option>
                <option <?php selected( $options['display_type'], 3 ); ?> value="3"><?php _e('Bulk variation', 'web-to-print-online-designer'); ?></option>
            </select>
        </div>
    </div>
</div>
<div class="nbd-field-info" ng-if="options.display_type == 2">
    <p><?php _e('Allow fields with options: Data type - Multiple options | Enable - Yes | has at least one attribute | Field Conditional Logic - No', 'web-to-print-online-designer'); ?></p>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><label><b><?php _e('Horizontal field', 'web-to-print-online-designer'); ?></b></label></div>
        </div>
        <div class="nbd-field-info-2">
            <div>
                <select name="options[pm_hoz][]" multiple ng-model="options.pm_hoz">
                    <option value="{{field.field_index}}" ng-repeat="(fieldIndex, field) in availablePmHozFileds">{{options.fields[field.field_index].general.title.value}}</option>
                </select>
            </div>
        </div>    
    </div>   
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><label><b><?php _e('Vertical field', 'web-to-print-online-designer'); ?></b></label></div>
        </div>
        <div class="nbd-field-info-2">
            <div>
                <select name="options[pm_ver][]" multiple ng-model="options.pm_ver">
                    <option  value="{{field.field_index}}" ng-repeat="(fieldIndex, field) in availablePmVerFileds">{{options.fields[field.field_index].general.title.value}}</option>
                </select>                        
            </div>
        </div>    
    </div> 
    <div class="nbd-field-info">
        <table>
            <tbody>
                <tr></tr>
            </tbody>
        </table>
    </div>    
</div>
<div class="nbd-field-info" ng-if="options.display_type == 3">
    <p><?php _e('Allow fields with options: Enable - Yes | Field Conditional Logic - No', 'web-to-print-online-designer'); ?></p>
    <div class="nbd-field-info">
        <div class="nbd-field-info-1">
            <div><label><b><?php _e('Bulk form field', 'web-to-print-online-designer'); ?></b></label></div>
        </div>
        <div class="nbd-field-info-2">
            <div>
                <select name="options[bulk_fields][]" multiple ng-model="options.bulk_fields">
                    <option value="{{field.field_index}}" ng-repeat="(fieldIndex, field) in availableBulkFileds">{{options.fields[field.field_index].general.title.value}}</option>
                </select>
            </div>
        </div>    
    </div>    
</div>