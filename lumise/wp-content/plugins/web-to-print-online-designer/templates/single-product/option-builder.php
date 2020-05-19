<?php 
if (!defined('ABSPATH')) exit;
$in_quick_view = false;
$is_wqv = false;
if( (isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'WC_Quick_View') || (isset($_REQUEST['action']) && $_REQUEST['action'] == 'yith_load_product_quick_view') ){
    $in_quick_view = true;
    if(isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'WC_Quick_View') $is_wqv = true;
}
$appid = "nbo-app-" . time().rand(1,1000);
$display_type = nbdesigner_get_option('nbdesigner_option_display');
$nbd_qv_type = wp_is_mobile() ? '1' : nbdesigner_get_option('nbdesigner_display_product_option'); 
if( isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'NBO_Quick_View' && $nbd_qv_type == '2') $display_type = 1;
$prefix = $display_type == 2 ? '-2' : '';
$style_class = $display_type == 2 ? 'nbo-style-2' : 'nbo-style-1';
$hide_swatch_label = nbdesigner_get_option('nbdesigner_hide_option_swatch_label');
?>
<div class="nbo-wrapper <?php if($is_wqv) echo 'nbd-option-in-wqv'; ?>">
<style>
    /* tipTip */
    #tiptip_holder {
        display: none;
        position: absolute;
        top: 0;
        left: 0;
        z-index: 9999999999;
    }
    #tiptip_holder.tip_top {
        padding-bottom: 5px;
    }
    #tiptip_holder.tip_bottom {
        padding-top: 5px;
    }
    #tiptip_holder.tip_right {
        padding-left: 5px;
    }
    #tiptip_holder.tip_left {
        padding-right: 5px;
    }
    #tiptip_content {
        font-size: 11px;
        color: #fff;
        text-shadow: 0 0 2px #000;
        padding: 4px 8px;
        border: 1px solid rgba(255,255,255,0.25);
        background-color: rgb(25,25,25);
        background-color: rgba(25,25,25,0.92);
        background-image: -webkit-gradient(linear, 0% 0%, 0% 100%, from(transparent), to(#000));
        border-radius: 3px;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        box-shadow: 0 0 3px #555;
        -webkit-box-shadow: 0 0 3px #555;
        -moz-box-shadow: 0 0 3px #555;
    }
    #tiptip_arrow, #tiptip_arrow_inner {
        position: absolute;
        border-color: transparent;
        border-style: solid;
        border-width: 6px;
        height: 0;
        width: 0;
    }
    .rtl #tiptip_arrow{
        right:50%;
        margin-right:-6px
    }
    #tiptip_holder.tip_top #tiptip_arrow {
        border-top-color: #fff;
        border-top-color: rgba(255,255,255,0.35);
    }
    #tiptip_holder.tip_bottom #tiptip_arrow {
        border-bottom-color: #fff;
        border-bottom-color: rgba(255,255,255,0.35);
    }
    #tiptip_holder.tip_right #tiptip_arrow {
        border-right-color: #fff;
        border-right-color: rgba(255,255,255,0.35);
    }
    #tiptip_holder.tip_left #tiptip_arrow {
        border-left-color: #fff;
        border-left-color: rgba(255,255,255,0.35);
    }
    #tiptip_holder.tip_top #tiptip_arrow_inner {
        margin-top: -7px;
        margin-left: -6px;
        border-top-color: rgb(25,25,25);
        border-top-color: rgba(25,25,25,0.92);
    }
    .rtl #tiptip_holder.tip_top #tiptip_arrow_inner{
        margin-right:-6px;
    }
    #tiptip_holder.tip_bottom #tiptip_arrow_inner {
        margin-top: -5px;
        margin-left: -6px;
        border-bottom-color: rgb(25,25,25);
        border-bottom-color: rgba(25,25,25,0.92);
    }
    .rtl #tiptip_holder.tip_bottom #tiptip_arrow_inner {
        margin-right:-6px;
    }
    #tiptip_holder.tip_right #tiptip_arrow_inner {
        margin-top: -6px;
        margin-left: -5px;
        border-right-color: rgb(25,25,25);
        border-right-color: rgba(25,25,25,0.92);
    }
    .rtl #tiptip_holder.tip_right #tiptip_arrow_inner {
        margin-right:-5px;
    }
    #tiptip_holder.tip_left #tiptip_arrow_inner {
        margin-top: -6px;
        margin-left: -7px;
        border-left-color: rgb(25,25,25);
        border-left-color: rgba(25,25,25,0.92);
    }
    .rtl #tiptip_holder.tip_left #tiptip_arrow_inner {
        margin-right:-7px;
    }
    @media screen and (-webkit-min-device-pixel-ratio:0) {	
        #tiptip_content {
            padding: 4px 8px 5px 8px;
            background-color: rgba(45,45,45,0.88);
        }
        #tiptip_holder.tip_bottom #tiptip_arrow_inner { 
            border-bottom-color: rgba(45,45,45,0.88);
        }
        #tiptip_holder.tip_top #tiptip_arrow_inner { 
            border-top-color: rgba(20,20,20,0.92);
        }
    }
    .nbo-disabled {
        opacity: 0.3;
        pointer-events: none;
    }
    .nbo-prevent-pointer {
        pointer-events: none;
    }
    .nbd-help-tip {
        vertical-align: middle;
        cursor: help;
        margin: -2px -24px 0 5px;
        line-height: 1;
        color: #fff !important;
        background: #333333;
        border-radius: 50%;
        display: inline-block;
        font-size: 10px;
        font-style: normal;
        height: 12px;
        position: relative;
        width: 12px;  
    }
    .nbd-help-tip::after {
        font-family: Dashicons;
        speak: none;
        font-weight: 400;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        text-indent: 0px;
        position: absolute;
        top: 1px;
        left: 0px;
        width: 100%;
        height: 100%;
        text-align: center;
        content: "?";
        cursor: help;
        font-variant: normal;
        margin: 0px;
    }
    .rtl .nbd-help-tip::after {
        top:0;
        right:0;
    }
    .rtl .nbd-help-tip {
        margin: 0 9px 0 0;
    }
    /* End tipTip */ 
    /* nbd-radio */
    @keyframes ripple {
        0% {
            box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0);
        }
        50% {
            box-shadow: 0px 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        100% {
            box-shadow: 0px 0px 0px 15px rgba(0, 0, 0, 0);
        }
    }    
    @-webkit-keyframes ripple {
        0% {
            box-shadow: 0px 0px 0px 1px rgba(0, 0, 0, 0);
        }
        50% {
            box-shadow: 0px 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        100% {
            box-shadow: 0px 0px 0px 15px rgba(0, 0, 0, 0);
        }
    } 
    .nbd-radio input[type="radio"]:checked + label:before,
    .nbo-sub-attr-r input[type="radio"]:checked + label:before {
        border-color: #404762;
        animation: ripple 0.2s linear forwards;
    }
    .nbd-radio input[type="radio"]:checked + label:after,
    .nbo-sub-attr-r input[type="radio"]:checked + label:after {
        transform: scale(1);
    }
    .nbo-sub-attr-r label {
        background: unset !important;
        color: unset !important;
    }
    .nbd-radio label,
    .nbo-sub-attr-r label {
        display: inline-block;
        height: 20px;
        position: relative;
        padding: 0 30px;
        margin-bottom: 0;
        cursor: pointer;
        line-height: 20px;
    }
    .nbd-radio label:before, .nbd-radio label:after,
    .nbo-sub-attr-r label:before, .nbo-sub-attr-r label:after {
        position: absolute;
        content: '';
        border-radius: 50%;
        transition: all .3s ease;
        transition-property: transform, border-color;
        box-sizing: border-box;
    }
    .nbd-radio label:before,
    .nbo-sub-attr-r label:before {
        left: 0;
        top: 0;
        width: 20px;
        height: 20px;
        border: 2px solid rgba(0, 0, 0, 0.54);
    }
    .nbd-radio label:after,
    .nbo-sub-attr-r label:after {
        top: 5px;
        left: 5px;
        width: 10px;
        height: 10px;
        transform: scale(0);
        background: #404762;
    }    
    .nbo-sub-attr-l label:after, .nbo-sub-attr-l label:before {
        display: none;
    }
    /* end. nbd-radio */
    [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak {
      display: none !important;
    }
    .nbd-option-wrapper {
        margin-bottom: 1.1em;
    }
    .nbd-option-field {
/*        -webkit-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        -moz-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        -ms-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        border-radius: 4px;*/
        background-color: #fff;
        margin-bottom: 1.1em;
        border: 1px solid #f8f8f8;
    }
    .nbd-option-field select,
    .nbd-option-field input[type="text"]{
        min-width: 150px;
    }
    td .nbo-dimension-label{
        min-width: 50px;
        display: inline-block;
    }
    .nbd-field-header {
        padding: 10px;
        background: #f8f8f8;
        color: #404762;
        font-weight: bold;
    }
    .nbd-field-header label {
        font-weight: bold;
    }
    .nbd-field-content {
        padding: 10px;
    }    
    .nbd-field-content:after,
    .nbd-field-header:after {
        content: '';
        display: block;
        clear: both;
    }
    .nbd-option-wrapper label {
        cursor: pointer;
        margin: 0 !important;
        margin: 0px 4px 2px!important;
    }
    .nbd-swatch {
        width: 36px;
        height: 36px;
        display: inline-block;
        border-radius: 50%; 
        cursor: pointer;
        border: 2px solid #ddd;
        position: relative;
    }
    .nbo-checkbox {
        width: 36px;
        height: 36px;
        display: inline-block;
        cursor: pointer;
        border: 2px solid #ddd;
    }
    .nbd-option-wrapper input[type="radio"], .nbo-checkbox-wrap input[type="checkbox"] {
        display: none;
    }
    .nbd-swatch-wrap input[type="radio"]:checked + label,
    .nbo-checkbox-wrap input[type="checkbox"]:checked + label {
        border: 2px solid #404762;
        position: relative;
        display: inline-block;
    }
    .nbd-swatch-wrap input[type="radio"]:checked + label:before {
        display: block;
        top: 0;
        left: 0;
        border: 2px solid #fff;
        position: absolute;
        z-index: 2;
        width: 100%;
        height: 100%;
        content: '';
        border-radius: 100%;
        box-sizing: border-box;
    }
    .nbd-swatch-wrap input[type="radio"]:checked + label:after, 
    .nbo-checkbox-wrap input[type="checkbox"]:checked + label:after {
        -webkit-transform: rotate(45deg);
        -moz-transform: rotate(45deg);
        transform: rotate(45deg);
        content: "";
        width: 6px;
        height: 10px;
        display: block;
        border: solid #fff;
        border-width: 0 2px 2px 0;
        position: absolute;
        top: 7px;
        left: 12px;        
    }
    .nbo-dropdown {
        border: 1px solid #EEE;
        height: 36px;
        padding: 3px 36px 3px 8px;
        background-color: transparent;
        line-height: 100%;
        outline: 0;
        background-image: url(<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/arrow.png'; ?>);
        background-position: right;
        background-repeat: no-repeat;
        position: relative;
        cursor: pointer;
        -webkit-appearance: none;
        -moz-appearance: none;        
    }
    .nbd-label, .nbo-sub-attr-l label{
        border-radius: 36px;
        height: 36px;
        line-height: 36px;
        padding: 0 20px;
        background: #ddd;
        text-transform: uppercase;
        font-size: 13px;
        display: inline-block;
        margin: 0 5px 5px 0;
        -webkit-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        -moz-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        -ms-box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        box-shadow: 0 1px 4px 0 rgba(0,0,0,0.14);
        color: #757575;
        -webkit-transition: all 0.4s;
        -moz-transition: all 0.4s;
        -ms-transition: all 0.4s;
        transition: all 0.4s;
        background: #eee;  
        max-width: 100%;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
    }
    .nbd-label-wrap input[type="radio"]:checked + label,
    .nbo-sub-attr-l input[type="radio"]:checked + label {
        background: #404762;
        color: #fff;
    }
    .nbd-label:hover, .nbo-sub-attr-l label:hover {
        -webkit-box-shadow: 0 3px 10px 0 rgba(75,79,84,.3);
        -moz-box-shadow: 0 3px 10px 0 rgba(75,79,84,.3);
        -ms-box-shadow: 0 3px 10px 0 rgba(75,79,84,.3);
        box-shadow: 0 3px 10px 0 rgba(75,79,84,.3);        
    }    
    .nbd-swatch-wrap .nbd-field-content{
        font-size: 0;
    }
    .nbd-required {
        color: red !important;
    }
    .nbd-field-input-wrap input[type="number"] {
        padding: 0.418047em;
        background-color: #f2f2f2;
        color: #43454b;
        outline: 0;
        border: 0;
        -webkit-appearance: none;
        box-sizing: border-box;
        font-weight: 400;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.125);
        width: 4.235801032em;
        text-align: center;        
    }
    .nbd-field-content input[type="range"] {
        margin-left: 0;
        flex: 1
    }
    .nbd-field-content input[type="range"] {
        padding: 0;
        -webkit-appearance: none;
        background: transparent;
        border: none;
        box-shadow: none;        
    }
    .nbd-field-content input[type="range"][name="nbo-quantity"]{
        max-width: 200px;
    }
    .nbd-field-content input[type="range"]::-webkit-slider-thumb {
        -webkit-appearance: none;
        height: 24px;
        width: 24px;
        border-radius: 50%;
        cursor: pointer;
        background: #404762;
        border: 4px solid transparent;
        background-clip: padding-box;
        box-sizing: border-box;
        margin-top: -10px
    }
    .nbd-field-content input[type="range"]::-moz-range-thumb {
        height: 18px;
        width: 18px;
        border-radius: 50%;
        cursor: pointer;
        background: #404762;
        border: 4px solid transparent;
        background-clip: padding-box;
        box-sizing: border-box
    }
    .nbd-field-content input[type="range"]::-ms-thumb {
        border-radius: 50%;
        cursor: pointer;
        background: #404762;
        border: 4px solid transparent;
        background-clip: padding-box;
        box-sizing: border-box;
        margin-top: 0;
        height: 14px;
        width: 14px;
        border: 2px solid transparent
    }
    .nbd-field-content input[type="range"]:focus {
        outline: none
    }
    .nbd-field-content input[type="range"]:focus::-webkit-slider-thumb {
        background-color: #fff;
        color: #191e23;
        box-shadow: inset 0 0 0 1px #6c7781,inset 0 0 0 2px #fff;
        outline: 2px solid transparent;
        outline-offset: -2px
    }
    .nbd-field-content input[type="range"]:focus::-moz-range-thumb {
        background-color: #fff;
        color: #191e23;
        box-shadow: inset 0 0 0 1px #6c7781,inset 0 0 0 2px #fff;
        outline: 2px solid transparent;
        outline-offset: -2px
    }
    .nbd-field-content input[type="range"]:focus::-ms-thumb {
        background-color: #fff;
        color: #191e23;
        box-shadow: inset 0 0 0 1px #6c7781,inset 0 0 0 2px #fff;
        outline: 2px solid transparent;
        outline-offset: -2px
    }
    .nbd-field-content input[type="range"]::-webkit-slider-runnable-track {
        height: 3px;
        cursor: pointer;
        background: #e2e4e7;
        border-radius: 1.5px;
        margin-top: -4px
    }
    .nbd-field-content input[type="range"]::-moz-range-track {
        height: 3px;
        cursor: pointer;
        background: #e2e4e7;
        border-radius: 1.5px
    }
    .nbd-field-content input[type="range"]::-ms-track {
        margin-top: -4px;
        background: transparent;
        border-color: transparent;
        color: transparent;
        height: 3px;
        cursor: pointer;
        background: #e2e4e7;
        border-radius: 1.5px
    }
    .nbd-field-content .nbd-invalid-notice {
        display: none;
        font-size: 0.75em;
        color: red;
    }
    .nbd-field-content input.ng-invalid-min ~ .nbd-invalid-min {
        display: inline-block;
    }
    .nbd-field-content input.ng-invalid-max ~ .nbd-invalid-max {
        display: inline-block;
    } 
    .nbd-invalid-form {
        color: red;        
    }
    .nbo-disabled {
        opacity: .5!important;
        cursor: not-allowed;        
    }
    .nbo-hidden {
        display: none;
    }
    .nbo-table-wrap {
        margin: 0 0 1.41575em;
    }
    .nbo-price-matrix, .nbo-table-wrap {
        max-width: 100%;
        overflow-x: scroll;
        overflow: auto;
    }
    .nbo-table-wrap table {
        margin: 0 !important;
    }
    .nbo-table-wrap table input[type="number"]{
        width: 4em;
        padding: 0.418047em;
        background-color: #f2f2f2;
        color: #43454b;
        outline: 0;
        border: 0;
        -webkit-appearance: none;
        box-sizing: border-box;
        font-weight: 400;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.125);
        width: 4.235801032em;
        text-align: center;        
    }    
    .nbo-price-matrix table, .nbo-table-wrap table{
        border-collapse: collapse;
    }
    .nbo-price-matrix table, .nbo-price-matrix td, .nbo-price-matrix th,
    .nbo-table-wrap table, .nbo-table-wrap td, .nbo-table-wrap th {
        text-align: center;
        border: 1px solid #ddd;
        vertical-align: middle;
        padding: 0.75em 0.75em;
    }
    .nbo-price-matrix td, .nbo-table-wrap td {
        cursor: pointer;
    }
    .nbo-pm-empty, .nbo-price-matrix th {
        pointer-events: none;
    }
    .nbo-price-matrix td.selected {
        background: #404762 !important;
        color: #fff !important;
    }
    .nbo-dimension {
        width: 4em;
        background: #fff !important;
        box-shadow: none !important;
        height: 36px !important;
    }
    input.nbo-dimension::-webkit-outer-spin-button,
    input.nbo-dimension::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input.nbo-dimension {
        -moz-appearance:textfield;
    }
    .nbo-dimension-wrap {
        border: solid 1px #eee;
        -webkit-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
        display: inline-block;
    }
    .nbo-updown-dimension {
        display: inline-block;
        width: 36px;
        height: 36px;
        vertical-align: top;
        line-height: 36px;
        text-align: center;
        background: #eee;
        font-size: 24px !important;
        cursor: pointer;
    }
    .nbd-input-range {
        margin-left: 5px;
        background: #404762;
        padding: 0 15px;
        line-height: 20px;
        vertical-align: middle;
        color: #fff;
        border-radius: 20px;
        display: inline-block;
    }    
    .nbo-final-price {
        background: #eee;
        color: #404762;
        font-weight: bold;        
    }
    .nbd-swatch-label-wrap {
        display: flex;
        align-items: center;
    }
    .nbd-swatch-label-wrap:not(:last-child){
        margin-bottom: 10px;  
    }
    .nbd-swatch-description {
        font-size: 14px;
        margin-left: 10px;        
    }
    .nbo-clear-option-wrap {
        text-align: right;
        margin-bottom: 1em;
        overflow: hidden;
    }
    .nbo-clear-option-wrap:after{
        content: '';
        display: block;
        clear: both;
    }
    .nbo-clear-option-wrap .nbd-button {
        float: right !important;
    }
    .nbo-style-1 {
        border: 1px solid #f8f8f8;
        margin-bottom: 1em;
    }
    .nbo-style-1 .nbo-summary-title,
    .nbo-style-1 .nbo-table-pricing-title{
        padding: 10px;
        background: #f8f8f8;
        margin: 0;
        display: flex;
        justify-content: space-between;
    }
    .nbo-style-1 .nbo-summary-title:after {
        clear: both;
    }
    .nbo-style-1 .nbo-summary-table ,
    .nbo-style-1 .nbo-table-pricing {
        margin: 0;
        padding: 3px;
    }
    .nbo-toggle {
        text-align: center;
        cursor: pointer;
    }
    .nbo-toggle svg {
        vertical-align: top;
        height: 100%;
    }
    .nbd-swatch-tooltip {
        background: #404762;
        color: #fff !important;
        border-radius: 4px;
        font-size: 14px;
        font-weight: bold;
        position:  absolute;
        bottom: 50%;
        left: 50%;
        pointer-events: none;
        padding: 5px 7px;
        visibility: hidden;
        opacity: 0;
        -webkit-transform: translate3d(-50%,0%,0);
        -moz-transform: translate3d(-50%,0%,0);
        transform: translate3d(-50%,0%,0);
        width: -webkit-max-content;
        width: -moz-max-content;
        width: max-content;
        max-width: 200px;      
        z-index: 99;
        -webkit-transition: all .4s;
        -moz-transition: all .4s;
        transition: all .4s; 
    }
    .nbd-swatch-tooltip:before {
        content: '';
        border-left: 5px solid transparent;
        border-right: 5px solid transparent;
        border-top: 5px solid #404762;
        position: absolute;
        bottom: -5px;
        margin-left: -3px;
        left: 50%;
    }
    .nbd-swatch:hover .nbd-swatch-tooltip {
        bottom: 40px;
        visibility: visible;
        opacity: 1; 
    }
    .nbo-wrapper:afte {
        content: '';
        display: block;
        clear: both;
    }
    .nbo-bold {
        font-weight: bold;
    }
    .nbo-sub-attr-wrap {
        padding: 10px;
        border: 1px solid #f8f8f8;
        margin-top: 5px;
    }
    .nbd-input-u {
        cursor: pointer;
    }
    @media (max-width:768px){
        .nbd-swatch-tooltip {
            display: none;
        }
        .nbd-tb-options td {
            display: inline-block !important;
            width: 100%;
            padding: 10px !important;
        }
    }
</style>
<div class="nbd-option-wrapper" <?php //if(!$in_quick_view) echo 'ng-app="nboApp"'; ?> id="<?php echo $appid; ?>">
    <div ng-controller="optionCtrl" ng-form="nboForm" id="nbo-ctrl-<?php echo $appid; ?>" ng-cloak>
        <div class="nbo-fields-wrapper">
    <?php if( $display_type == 2 ): ?>
        <table class="nbd-tb-options">
            <tbody>
<?php endif; 
$html_field = '';
$has_nbpb = false;
if( $cart_item_key != '' && $options['display_type'] == 3) $options['display_type'] = 1;
if( $options['display_type'] == 2 ){
    $pm_field_indexes = array_merge($options['pm_hoz'], $options['pm_ver']);
}
foreach($options["fields"] as $key => $field){
    if( $options['display_type'] == 2 ){
        $class = !in_array($key, $pm_field_indexes) ? '' : 'nbo-hidden';
    }else if( $options['display_type'] == 3 ){
        $class = !in_array($key, $options['bulk_fields']) ? '' : 'nbo-hidden';
    }else{
        $class = '';
    }
    if( $nbdpb_enable == '1' && isset($field['nbpb_type']) && ( $field['nbpb_type'] == 'nbpb_com' || $field['nbpb_type'] == 'nbpb_text' || $field['nbpb_type'] == 'nbpb_image' ) ){
        $class = 'nbo-hidden';
        $has_nbpb = true;
    }
    $need_show = true;
    if( $field['general']['data_type'] == 'i' ){
        $tempalte = NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/input'.$prefix.'.php'; 
    }else{
        if( count($field['general']['attributes']["options"]) == 0){
            $need_show = false;
        }
        if(isset($field['nbd_type']) && $field['nbd_type'] == 'page' && $field['general']['data_type'] == 'm'){
            $tempalte = NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/page'.$prefix.'.php';
        }else{
            switch($field['appearance']['display_type']){
                case 's':
                    $tempalte = NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/swatch'.$prefix.'.php';
                    break;
                case 'l':
                    $tempalte = NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/label'.$prefix.'.php';
                    break;            
                case 'r':
                    $tempalte = NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/radio'.$prefix.'.php';
                    break;
                default:
                    $tempalte = NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/dropdown'.$prefix.'.php';
                    break;            
            }
        }
    }
    if( $field['general']['enabled'] == 'y' && $need_show ) include($tempalte);
}
$disable_quantity_input = false;
$show_quantity_option = false;
if( $options['quantity_enable'] == 'y' && !$is_sold_individually && !($options['display_type'] == 3 && count($options['bulk_fields'])) ){
    $disable_quantity_input = $options['quantity_type'] != 'r' ? true : false;
    $show_quantity_option = true;
    include(NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/quantity'.$prefix.'.php');
}
if( $display_type == 2 ): ?>
            </tbody>
        </table> 
<?php endif;
if($has_nbpb) do_action('nbo_after_default_options');
if( $options['display_type'] == 2 && count($pm_field_indexes) ){
    include(NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/price-matrix.php');
}else if( $options['display_type'] == 3 && count($options['bulk_fields']) ){
    include(NBDESIGNER_PLUGIN_DIR .'templates/single-product/options-builder/bulk-options.php');
}
if( $cart_item_key != ''){ ?>
        <input type="hidden" value="<?php echo $cart_item_key; ?>" name="nbo_cart_item_key"/>
<?php } ?>
            <div ng-if="fields.length" class="nbo-clear-option-wrap">
                <?php if( isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'NBO_Quick_View' && $nbd_qv_type == '2') : ?>
                <a ng-class="printingOptionsAvailable ? '' : 'nbd-disabled'" class="nbd-button nbo-apply" ng-click="applyOptions()">{{settings.task2 == '' ? "<?php _e('Apply options','web-to-print-online-designer'); ?>" : "<?php _e('Start design','web-to-print-online-designer'); ?>" }}</a>
                <?php endif; ?>
                <a class="button nbd-button" ng-click="reset_options()"><?php _e('Clear selection', 'web-to-print-online-designer'); ?></a>
            </div>
            <input type="hidden" value="<?php echo $product_id; ?>" name="nbo-add-to-cart"/>
            <p ng-if="!valid_form" class="nbd-invalid-form"><?php _e('Please check invalid fields and quantity input!', 'web-to-print-online-designer'); ?></p>
        </div>
        <div class="nbo-summary-wrapper">
            <?php if( nbdesigner_get_option('nbdesigner_hide_summary_options') != 'yes' && $options['display_type'] != 3): ?>
            <div ng-if="valid_form" class="<?php echo $style_class; ?>">
                <p class="nbo-summary-title" ng-init="showNboSummary = true">
                    <b><?php _e('Summary options', 'web-to-print-online-designer'); ?></b>
                    <?php if( $display_type == 1 ): ?>
                    <span class="nbo-minus nbo-toggle" ng-show="showNboSummary" ng-click="showNboSummary = !showNboSummary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13H5v-2h14v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </span>
                    <span class="nbo-plus nbo-toggle" ng-show="!showNboSummary" ng-click="showNboSummary = !showNboSummary">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </span>
                    <?php endif; ?>
                </p>
                <table class="nbo-summary-table" ng-show="showNboSummary">
                    <tbody>
                        <tr ng-repeat="(key, field) in nbd_fields" ng-show="field.enable"><td>{{field.title}} : <b>{{field.value_name}}</b></td><td ng-bind-html="field.price | to_trusted"></td></tr>
                    </tbody>
                    <tfoot style="border-top: 1px solid #404762;">
                        <tr>
                            <td><b><?php _e('Options price', 'web-to-print-online-designer'); ?></b></td>
                            <td><span id="nbd-option-total"><span ng-bind-html="total_price | to_trusted"></span> / <?php _e('1 item', 'web-to-print-online-designer'); ?></span></td>
                        </tr>
                        <tr>
                            <td><b><?php _e('Quantity Discount', 'web-to-print-online-designer'); ?></b></td>
                            <td><span id="nbd-option-total"><span ng-bind-html="discount_by_qty | to_trusted"></span> / <?php _e('1 item', 'web-to-print-online-designer'); ?></span></td>
                        </tr>
                        <tr class="nbo-final-price">
                            <td><b><?php _e('Final price', 'web-to-print-online-designer'); ?></b></td>
                            <td><span id="nbd-option-total"><span ng-bind-html="final_price | to_trusted"></span> / <?php _e('1 item', 'web-to-print-online-designer'); ?></span></td>
                        </tr> 
                        <?php if($options['display_type'] != 3 || count($options['bulk_fields']) == 0): ?>
                        <tr class="nbo-final-price nbo-total-price" ng-if="_qty > 1">
                            <td><b><?php _e('Total price', 'web-to-print-online-designer'); ?></b></td>
                            <td><span id="nbd-option-total"><span ng-bind-html="total_cart_price | to_trusted"></span> / {{_qty}} <?php _e('items', 'web-to-print-online-designer'); ?></span></td>
                        </tr>
                        <?php endif; ?>
                    </tfoot>
                </table>
            </div>
            <?php endif; ?>
            <?php if( nbdesigner_get_option('nbdesigner_hide_table_pricing') == 'no' && $options['display_type'] != 3 ): ?>
            <div ng-if="valid_form && price_table.length > 1" class="<?php echo $style_class; ?>">
                <p class="nbo-table-pricing-title" ng-init="showNboTablePricing = true">
                    <b><?php _e('Table pricing', 'web-to-print-online-designer'); ?></b>
                    <?php if( $display_type == 1 ): ?>
                    <span class="nbo-minus nbo-toggle" ng-show="showNboTablePricing" ng-click="showNboTablePricing = !showNboTablePricing">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13H5v-2h14v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </span>
                    <span class="nbo-plus nbo-toggle" ng-show="!showNboTablePricing" ng-click="showNboTablePricing = !showNboTablePricing">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M19 13h-6v6h-2v-6H5v-2h6V5h2v6h6v2z"/><path d="M0 0h24v24H0z" fill="none"/></svg>
                    </span>
                    <?php endif; ?>
                </p>
                <table class="nbo-table-pricing" ng-show="showNboTablePricing">
                    <thead>
                        <tr>
                            <th><?php _e('From', 'web-to-print-online-designer'); ?></th>
                            <th><?php _e('Up to', 'web-to-print-online-designer'); ?></th>
                            <th><?php _e('Price / 1 item', 'web-to-print-online-designer'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr ng-repeat="pt in price_table" ng-class="pt.in_range ? 'nbo-bold' : ''">
                            <td>{{pt.from}}</td>
                            <td>{{pt.up != '**' ? pt.up : '<?php echo _e('or more', 'web-to-print-online-designer'); ?>'}}</td>
                            <td ng-bind-html="pt.final_price | to_trusted"></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<script type="text/javascript">
    (function($){
        $.fn.tipTip = function(options) {
                var defaults = {
                        activation: "hover",
                        keepAlive: false,
                        maxWidth: "200px",
                        edgeOffset: 3,
                        defaultPosition: "bottom",
                        delay: 400,
                        fadeIn: 200,
                        fadeOut: 200,
                        attribute: "title",
                        content: false, // HTML or String to fill TipTIp with
                        enter: function(){},
                        exit: function(){}
                };
                var opts = $.extend(defaults, options);

                // Setup tip tip elements and render them to the DOM
                if($("#tiptip_holder").length <= 0){
                        var tiptip_holder = $('<div id="tiptip_holder" style="max-width:'+ opts.maxWidth +';"></div>');
                        var tiptip_content = $('<div id="tiptip_content"></div>');
                        var tiptip_arrow = $('<div id="tiptip_arrow"></div>');
                        $("body").append(tiptip_holder.html(tiptip_content).prepend(tiptip_arrow.html('<div id="tiptip_arrow_inner"></div>')));
                } else {
                        var tiptip_holder = $("#tiptip_holder");
                        var tiptip_content = $("#tiptip_content");
                        var tiptip_arrow = $("#tiptip_arrow");
                }

                return this.each(function(){
                        var org_elem = $(this);
                        if(opts.content){
                                var org_title = opts.content;
                        } else {
                                var org_title = org_elem.attr(opts.attribute);
                        }
                        if(org_title != ""){
                                if(!opts.content){
                                        org_elem.removeAttr(opts.attribute); //remove original Attribute
                                }
                                var timeout = false;

                                if(opts.activation == "hover"){
                                        org_elem.hover(function(){
                                                active_tiptip();
                                        }, function(){
                                                if(!opts.keepAlive){
                                                        deactive_tiptip();
                                                }
                                        });
                                        if(opts.keepAlive){
                                                tiptip_holder.hover(function(){}, function(){
                                                        deactive_tiptip();
                                                });
                                        }
                                } else if(opts.activation == "focus"){
                                        org_elem.focus(function(){
                                                active_tiptip();
                                        }).blur(function(){
                                                deactive_tiptip();
                                        });
                                } else if(opts.activation == "click"){
                                        org_elem.click(function(){
                                                active_tiptip();
                                                return false;
                                        }).hover(function(){},function(){
                                                if(!opts.keepAlive){
                                                        deactive_tiptip();
                                                }
                                        });
                                        if(opts.keepAlive){
                                                tiptip_holder.hover(function(){}, function(){
                                                        deactive_tiptip();
                                                });
                                        }
                                }

                                function active_tiptip(){
                                        opts.enter.call(this);
                                        tiptip_content.html(org_title);
                                        tiptip_holder.hide().removeAttr("class").css("margin","0");
                                        tiptip_arrow.removeAttr("style");

                                        var top = parseInt(org_elem.offset()['top']);
                                        var left = parseInt(org_elem.offset()['left']);
                                        var org_width = parseInt(org_elem.outerWidth());
                                        var org_height = parseInt(org_elem.outerHeight());
                                        var tip_w = tiptip_holder.outerWidth();
                                        var tip_h = tiptip_holder.outerHeight();
                                        var w_compare = Math.round((org_width - tip_w) / 2);
                                        var h_compare = Math.round((org_height - tip_h) / 2);
                                        var marg_left = Math.round(left + w_compare);
                                        var marg_top = Math.round(top + org_height + opts.edgeOffset);
                                        var t_class = "";
                                        var arrow_top = "";
                                        var arrow_left = Math.round(tip_w - 12) / 2;

                    if(opts.defaultPosition == "bottom"){
                        t_class = "_bottom";
                        } else if(opts.defaultPosition == "top"){
                                t_class = "_top";
                        } else if(opts.defaultPosition == "left"){
                                t_class = "_left";
                        } else if(opts.defaultPosition == "right"){
                                t_class = "_right";
                        }

                                        var right_compare = (w_compare + left) < parseInt($(window).scrollLeft());
                                        var left_compare = (tip_w + left) > parseInt($(window).width());

                                        if((right_compare && w_compare < 0) || (t_class == "_right" && !left_compare) || (t_class == "_left" && left < (tip_w + opts.edgeOffset + 5))){
                                                t_class = "_right";
                                                arrow_top = Math.round(tip_h - 13) / 2;
                                                arrow_left = -12;
                                                marg_left = Math.round(left + org_width + opts.edgeOffset);
                                                marg_top = Math.round(top + h_compare);
                                        } else if((left_compare && w_compare < 0) || (t_class == "_left" && !right_compare)){
                                                t_class = "_left";
                                                arrow_top = Math.round(tip_h - 13) / 2;
                                                arrow_left =  Math.round(tip_w);
                                                marg_left = Math.round(left - (tip_w + opts.edgeOffset + 5));
                                                marg_top = Math.round(top + h_compare);
                                        }

                                        var top_compare = (top + org_height + opts.edgeOffset + tip_h + 8) > parseInt($(window).height() + $(window).scrollTop());
                                        var bottom_compare = ((top + org_height) - (opts.edgeOffset + tip_h + 8)) < 0;

                                        if(top_compare || (t_class == "_bottom" && top_compare) || (t_class == "_top" && !bottom_compare)){
                                                if(t_class == "_top" || t_class == "_bottom"){
                                                        t_class = "_top";
                                                } else {
                                                        t_class = t_class+"_top";
                                                }
                                                arrow_top = tip_h;
                                                marg_top = Math.round(top - (tip_h + 5 + opts.edgeOffset));
                                        } else if(bottom_compare | (t_class == "_top" && bottom_compare) || (t_class == "_bottom" && !top_compare)){
                                                if(t_class == "_top" || t_class == "_bottom") {
                                t_class = "_bottom";
                            } else {
                                t_class = t_class + "_bottom";
                            }
                            arrow_top = -12;
                            marg_top = Math.round(top + org_height + opts.edgeOffset);
                        }

                        if (t_class == "_right_top" || t_class == "_left_top") {
                            marg_top = marg_top + 5;
                        } else if (t_class == "_right_bottom" || t_class == "_left_bottom") {
                            marg_top = marg_top - 5;
                        }
                        if (t_class == "_left_top" || t_class == "_left_bottom") {
                            marg_left = marg_left + 5;
                        }
                        tiptip_arrow.css({"margin-left": arrow_left + "px", "margin-top": arrow_top + "px"});
                        tiptip_holder.css({"margin-left": marg_left + "px", "margin-top": marg_top + "px"}).attr("class", "tip" + t_class);

                        if (timeout) {
                            clearTimeout(timeout);
                        }
                        timeout = setTimeout(function () {
                            tiptip_holder.stop(true, true).fadeIn(opts.fadeIn);
                        }, opts.delay);
                    }

                    function deactive_tiptip() {
                        opts.exit.call(this);
                        if (timeout) {
                            clearTimeout(timeout);
                        }
                        tiptip_holder.fadeOut(opts.fadeOut);
                    }
                }
            });
        }
    })(jQuery); 
    !function(e){if("function"==typeof define&&define.amd)define(["jquery"],e);else if("object"==typeof exports){var n=require("jquery");module.exports=e(n)}else e(window.jQuery||window.Zepto||window.$)}(function(e){"use strict";e.fn.serializeJSON=function(n){var r,s,t,i,a,u,l,o,p,c,d,f,y;return r=e.serializeJSON,s=this,t=r.setupOpts(n),i=s.serializeArray(),r.readCheckboxUncheckedValues(i,t,s),a={},e.each(i,function(e,n){u=n.name,l=n.value,p=r.extractTypeAndNameWithNoType(u),c=p.nameWithNoType,(d=p.type)||(d=r.attrFromInputWithName(s,u,"data-value-type")),r.validateType(u,d,t),"skip"!==d&&(f=r.splitInputNameIntoKeysArray(c),o=r.parseValue(l,u,d,t),(y=!o&&r.shouldSkipFalsy(s,u,c,d,t))||r.deepSet(a,f,o,t))}),a},e.serializeJSON={defaultOptions:{checkboxUncheckedValue:void 0,parseNumbers:!1,parseBooleans:!1,parseNulls:!1,parseAll:!1,parseWithFunction:null,skipFalsyValuesForTypes:[],skipFalsyValuesForFields:[],customTypes:{},defaultTypes:{string:function(e){return String(e)},number:function(e){return Number(e)},boolean:function(e){return-1===["false","null","undefined","","0"].indexOf(e)},null:function(e){return-1===["false","null","undefined","","0"].indexOf(e)?e:null},array:function(e){return JSON.parse(e)},object:function(e){return JSON.parse(e)},auto:function(n){return e.serializeJSON.parseValue(n,null,null,{parseNumbers:!0,parseBooleans:!0,parseNulls:!0})},skip:null},useIntKeysAsArrayIndex:!1},setupOpts:function(n){var r,s,t,i,a,u;u=e.serializeJSON,null==n&&(n={}),t=u.defaultOptions||{},s=["checkboxUncheckedValue","parseNumbers","parseBooleans","parseNulls","parseAll","parseWithFunction","skipFalsyValuesForTypes","skipFalsyValuesForFields","customTypes","defaultTypes","useIntKeysAsArrayIndex"];for(r in n)if(-1===s.indexOf(r))throw new Error("serializeJSON ERROR: invalid option '"+r+"'. Please use one of "+s.join(", "));return i=function(e){return!1!==n[e]&&""!==n[e]&&(n[e]||t[e])},a=i("parseAll"),{checkboxUncheckedValue:i("checkboxUncheckedValue"),parseNumbers:a||i("parseNumbers"),parseBooleans:a||i("parseBooleans"),parseNulls:a||i("parseNulls"),parseWithFunction:i("parseWithFunction"),skipFalsyValuesForTypes:i("skipFalsyValuesForTypes"),skipFalsyValuesForFields:i("skipFalsyValuesForFields"),typeFunctions:e.extend({},i("defaultTypes"),i("customTypes")),useIntKeysAsArrayIndex:i("useIntKeysAsArrayIndex")}},parseValue:function(n,r,s,t){var i,a;return i=e.serializeJSON,a=n,t.typeFunctions&&s&&t.typeFunctions[s]?a=t.typeFunctions[s](n):t.parseNumbers&&i.isNumeric(n)?a=Number(n):!t.parseBooleans||"true"!==n&&"false"!==n?t.parseNulls&&"null"==n?a=null:t.typeFunctions&&t.typeFunctions.string&&(a=t.typeFunctions.string(n)):a="true"===n,t.parseWithFunction&&!s&&(a=t.parseWithFunction(a,r)),a},isObject:function(e){return e===Object(e)},isUndefined:function(e){return void 0===e},isValidArrayIndex:function(e){return/^[0-9]+$/.test(String(e))},isNumeric:function(e){return e-parseFloat(e)>=0},optionKeys:function(e){if(Object.keys)return Object.keys(e);var n,r=[];for(n in e)r.push(n);return r},readCheckboxUncheckedValues:function(n,r,s){var t,i,a;null==r&&(r={}),e.serializeJSON,t="input[type=checkbox][name]:not(:checked):not([disabled])",s.find(t).add(s.filter(t)).each(function(s,t){if(i=e(t),null==(a=i.attr("data-unchecked-value"))&&(a=r.checkboxUncheckedValue),null!=a){if(t.name&&-1!==t.name.indexOf("[]["))throw new Error("serializeJSON ERROR: checkbox unchecked values are not supported on nested arrays of objects like '"+t.name+"'. See https://github.com/marioizquierdo/jquery.serializeJSON/issues/67");n.push({name:t.name,value:a})}})},extractTypeAndNameWithNoType:function(e){var n;return(n=e.match(/(.*):([^:]+)$/))?{nameWithNoType:n[1],type:n[2]}:{nameWithNoType:e,type:null}},shouldSkipFalsy:function(n,r,s,t,i){var a=e.serializeJSON.attrFromInputWithName(n,r,"data-skip-falsy");if(null!=a)return"false"!==a;var u=i.skipFalsyValuesForFields;if(u&&(-1!==u.indexOf(s)||-1!==u.indexOf(r)))return!0;var l=i.skipFalsyValuesForTypes;return null==t&&(t="string"),!(!l||-1===l.indexOf(t))},attrFromInputWithName:function(e,n,r){var s,t;return s=n.replace(/(:|\.|\[|\]|\s)/g,"\\$1"),t='[name="'+s+'"]',e.find(t).add(e.filter(t)).attr(r)},validateType:function(n,r,s){var t,i;if(i=e.serializeJSON,t=i.optionKeys(s?s.typeFunctions:i.defaultOptions.defaultTypes),r&&-1===t.indexOf(r))throw new Error("serializeJSON ERROR: Invalid type "+r+" found in input name '"+n+"', please use one of "+t.join(", "));return!0},splitInputNameIntoKeysArray:function(n){var r;return e.serializeJSON,r=n.split("["),""===(r=e.map(r,function(e){return e.replace(/\]/g,"")}))[0]&&r.shift(),r},deepSet:function(n,r,s,t){var i,a,u,l,o,p;if(null==t&&(t={}),(p=e.serializeJSON).isUndefined(n))throw new Error("ArgumentError: param 'o' expected to be an object or array, found undefined");if(!r||0===r.length)throw new Error("ArgumentError: param 'keys' expected to be an array with least one element");i=r[0],1===r.length?""===i?n.push(s):n[i]=s:(a=r[1],""===i&&(o=n[l=n.length-1],i=p.isObject(o)&&(p.isUndefined(o[a])||r.length>2)?l:l+1),""===a?!p.isUndefined(n[i])&&e.isArray(n[i])||(n[i]=[]):t.useIntKeysAsArrayIndex&&p.isValidArrayIndex(a)?!p.isUndefined(n[i])&&e.isArray(n[i])||(n[i]=[]):!p.isUndefined(n[i])&&p.isObject(n[i])||(n[i]={}),u=r.slice(1),p.deepSet(n[i],u,s,t))}}});
    var in_quick_view = <?php echo $in_quick_view ? 1 : 0; ?>;
    var nbOption = {
        status: false,
        initialed: false,
        options: <?php echo json_encode($options); ?>,
        nbd_fields: {},
        odOption: {},
        lastOdOption: {},
        crtlId: 'nbo-ctrl-<?php echo $appid; ?>',
        updateVariations: function(){
            var scope = angular.element(document.getElementById(nbOption.crtlId)).scope();
            scope.updateVariations();
        }
    };
    jQuery('.variations_form').on('woocommerce_variation_has_changed wc_variation_form', function(){
        startApp();
    });
    jQuery('.variations_form').on('found_variation', function(){
        setTimeout(function(){
            startApp();
        }, 100);
    });
    jQuery(document).ready(function(){
        jQuery('input[name="quantity"]').on('input change change.nbo', function(event){
            if( event.namespace == 'nbo' ){
                startApp();
            }else{
                startApp( true );
            }
        });
        <?php if($disable_quantity_input): ?>
            jQuery('input[name="quantity"]').on('click', function(){
                if( nbOption.status ){
                    jQuery('html,body').animate({
                        scrollTop: jQuery("#nbo-quantity-option-wrap").offset().top
                    }, 'slow');
                }
            });
        <?php endif; ?>
    });
    function startApp( updateQty ){
        if( nbOption.status ){
            var scope = angular.element(document.getElementById("nbo-ctrl-<?php echo $appid; ?>")).scope();
            scope.check_valid();
            scope.update_app(); 
            <?php if($show_quantity_option && !$disable_quantity_input): ?>
                if( angular.isDefined(updateQty) ){
                    scope.quantity = scope.validate_int( jQuery('input[name="quantity"]').val());
                }
            <?php endif; ?>
        }
    };
    var option_selector = "<?php echo nbdesigner_get_option('nbdesigner_selector_increase_qty_btn'); ?>";
    var quantity_selector = '.quantity:not(.buttons_added) .minus, .quantity:not(.buttons_added) .plus, .quantity-plus, .quantity-minus';
    var qty_selector = option_selector != '' ? quantity_selector + ', ' + option_selector : quantity_selector;
    jQuery(document).off('click.nbo', qty_selector)
            .on('click.nbo', qty_selector, function(){
                jQuery('input[name="quantity"]').trigger( 'change.nbo' );
            });
    <?php if( isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'NBO_Quick_View') : ?>
    var nboApp = nbdApp;
    <?php else: ?>
    var nboApp = angular.module('nboApp', []);
    <?php endif; ?>
    nboApp.controller('optionCtrl', ['$scope', '$timeout', function($scope, $timeout){
        $scope.product_id = <?php echo $product_id; ?>;
        $scope.options = nbOption.options;
        $scope.fields = $scope.options["fields"];
        $scope.price = "<?php echo $price; ?>";
        $scope.type = "<?php echo $type; ?>";
        $scope.variations = <?php echo $variations; ?>;
        $scope.form_values = <?php echo json_encode($form_values); ?>;
        $scope.is_sold_individually = "<?php echo $is_sold_individually; ?>";
        $scope.valid_form = false;
        $scope.product_image = [];
        $scope.product_img = [];
        $scope.price_table = [];
        $scope.has_price_matrix = false;
        $scope.can_start_design = true;
        $scope.check_valid = function( calculate_pm, pro ){
            $timeout(function(){
                var check = {}, total_check = true;
                angular.forEach($scope.nbd_fields, function(field, field_id){
                    $scope.check_depend(field_id);
                    check[field_id] = ( field.enable && field.required == 'y' && (field.value === '' || angular.isUndefined(field.value) ) ) ? false : true;
                    var origin_field = $scope.get_field(field_id);
                    if( origin_field.general.data_type == 'i' ){
                        if( origin_field.general.input_type != 't' ){
                            if( angular.isUndefined(field.value) ) check[field_id] = false;
                            if( origin_field.general.input_type == 'u' && field.required != 'y' ) check[field_id] = true;
                        }else{
                            if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'dimension' ){
                                if( angular.isUndefined(field.width) || angular.isUndefined(field.height) ) check[field_id] = false;
                            }else if( angular.isDefined(field.value) ){
                                if( field.enable && field.required == 'y' ){
                                    if( angular.isDefined(origin_field.general.text_option.min) && origin_field.general.text_option.min != '' ){
                                        var min = $scope.validate_int(origin_field.general.text_option.min);
                                        if( field.value.length < min ) check[field_id] = false;
                                    }
                                    if( angular.isDefined(origin_field.general.text_option.max) && origin_field.general.text_option.max != '' ){
                                        var max = $scope.validate_int(origin_field.general.text_option.max);
                                        if( field.value.length > max ) check[field_id] = false;
                                    }
                                }
                            }
                        }
                        field.value_name = '';
                        if( angular.isDefined(field.value) ){
                            if( origin_field.general.input_type != 'u' ){
                                field.value_name = field.value;
                            }else if( angular.isDefined(field.value.name) ){
                                field.value_name = field.value.name;
                            }
                        }
                    }else{
                        if( angular.isDefined(field.values) ){
                            field.value_name = '';
                            angular.forEach(field.values, function(val, index){
                                field.value_name += (index == 0 ? '' : ', ') + origin_field.general.attributes.options[val].name;
                            });
                            if( origin_field.nbd_type == "page" ){
                                $scope.can_start_design = field.values.length == 0 ? false: true;
                            }
                        }else{
                            var selected_option = origin_field.general.attributes.options[field.value];
                            field.value_name = selected_option.name;
                            if( angular.isDefined($scope.nbd_fields[field_id]) ){
                                $scope.nbd_fields[field_id].form_name = '';
                                if( angular.isDefined(selected_option.enable_subattr) && selected_option.enable_subattr == 'on' ){
                                    if( angular.isDefined(selected_option.sub_attributes) && selected_option.sub_attributes.length > 0 ){
                                        $scope.nbd_fields[field_id].form_name = selected_option.form_name;
                                        if( angular.isUndefined( selected_option.sub_attributes[$scope.nbd_fields[field_id].sub_value] ) ){
                                            $scope.nbd_fields[field_id].sub_value = '0';
                                        }
                                        field.value_name += ' - ' + selected_option.sub_attributes[$scope.nbd_fields[field_id].sub_value].name;
                                    }
                                }
                            }
                        }
                    }
                    if( !field.enable ) check[field_id] = true;
                });
                angular.forEach(check, function(c){
                    total_check = total_check && c;
                });
                /*if( $scope.options.display_type == 3 ){
                    var check_bulk_quantity = false;
                    if( jQuery('.nbb-qty-field').length == 0 ) check_bulk_quantity = true;
                    jQuery.each(jQuery('.nbb-qty-field'), function(key, el){
                        if(jQuery(el).val() != '') check_bulk_quantity = true;
                    });
                    total_check = total_check && check_bulk_quantity;
                }*/
                if(total_check){
                    $scope.postOptionsToEditor();
                    $scope.calculate_price();
                    $scope.calculate_price_table();
                    $scope.valid_form = true;
                    jQuery('.single_add_to_cart_button').removeClass( "nbo-disabled nbo-hidden");
                    if($scope.can_start_design){
                        jQuery('#triggerDesign').removeClass('nbdesigner_disable');
                    }else{
                        jQuery('#triggerDesign').addClass('nbdesigner_disable');
                    };
                }else{
                    jQuery(document).triggerHandler( 'invalid_nbo_options' );
                    jQuery('.single_add_to_cart_button').addClass( "nbo-disabled");
                    if( nbds_frontend.nbdesigner_hide_add_cart_until_form_filled == 'yes' ){
                        jQuery('.single_add_to_cart_button').addClass( "nbo-hidden");
                    }                    
                    $scope.valid_form = false;
                    jQuery('#triggerDesign').addClass('nbdesigner_disable');
                }
                $scope.may_be_change_product_image();
                if( $scope.has_price_matrix && ( angular.isUndefined( calculate_pm ) || calculate_pm ) ){
                    $scope.calculate_price_matrix();
                }
                angular.copy($scope.nbd_fields, nbOption.nbd_fields);
                if( !nbOption.initialed ){
                    jQuery(document).triggerHandler( 'initialed_nbo_options' );
                    nbOption.initialed = true;
                }else{
                    jQuery(document).triggerHandler( 'update_nbo_options', { pro: pro } );
                };
            });
        };
        $scope.postOptionsToEditor = function(){
            angular.copy(nbOption.odOption, nbOption.lastOdOption);
            nbOption.odOption = {};
            angular.forEach($scope.nbd_fields, function(field, field_id){
                if(field.enable){
                    var origin_field = $scope.get_field(field_id);
                    if( angular.isDefined(origin_field.nbd_type) ){
                        switch(origin_field.nbd_type){
                            case 'dpi':
                                nbOption.odOption.dpi = $scope.validate_int( field.value );
                                break;
                            case 'color':
                                var option_color = origin_field.general.attributes.options[field.value];
                                nbOption.odOption.color = {
                                    bg_type: origin_field.general.attributes.bg_type,
                                    bg_color: option_color.bg_color,
                                    bg_image: option_color.bg_image_url
                                };
                                break;
                            case 'page':
                                var number_page = $scope.validate_int( field.value );
                                nbOption.odOption.page = {
                                    number: number_page,
                                    page_display: origin_field.general.page_display,
                                    exclude_page: origin_field.general.exclude_page,
                                    field_id: field_id
                                };
                                if( origin_field.general.data_type == 'm' && field.values.length > 0 ){
                                    nbOption.odOption.page.list_page = field.values;
                                }                                
                                break;
                            case 'size':
                                /*var currentFieldIndex = $scope.getFieldIndexById(field_id) + '';
                                if( $scope.options.bulk_fields.includes(currentFieldIndex) ){
                                    nbOption.variations = [];
                                    var bulkForm = jQuery('.nbo-bulk-variation input, .nbo-bulk-variation select').serializeJSON();
                                    angular.forEach(bulkForm['nbb-qty-fields'], function(bf_field, bf_index){
                                        var option_size = origin_field.general.attributes.options[bulkForm['nbb-fields'][field_id][bf_index]];
                                        var first = true, name = '';
                                        angular.forEach(bulkForm['nbb-fields'], function(_bff_field, _bff_id){
                                            var _origin_field = $scope.get_field(_bff_id);
                                            var _option = _origin_field.general.attributes.options[bulkForm['nbb-fields'][_bff_id][bf_index]];
                                            var separate = first ? '' : ', ';
                                            name += separate + _option.name;
                                            first = false;
                                        });
                                        var size = {
                                            product_width: $scope.validate_float( option_size.product_width ),
                                            product_height: $scope.validate_float( option_size.product_height ),
                                            real_width: $scope.validate_float( option_size.real_width ),
                                            real_height: $scope.validate_float( option_size.real_height ),
                                            real_top: $scope.validate_float( option_size.real_top ),
                                            real_left: $scope.validate_float( option_size.real_left )
                                        };
                                        nbOption.variations.push({index: bf_index, qty: $scope.validate_int(bf_field), size: size, name: name});
                                    });
                                }else{*/
                                    if(origin_field.general.attributes.same_size == 'n'){
                                        var option_size = origin_field.general.attributes.options[field.value];
                                        nbOption.odOption.size = {
                                            product_width: $scope.validate_float( option_size.product_width ),
                                            product_height: $scope.validate_float( option_size.product_height ),
                                            real_width: $scope.validate_float( option_size.real_width ),
                                            real_height: $scope.validate_float( option_size.real_height ),
                                            real_top: $scope.validate_float( option_size.real_top ),
                                            real_left: $scope.validate_float( option_size.real_left )
                                        };
                                    }
                                /*}*/
                                break;
                            case 'dimension':
                                nbOption.odOption.dimension = {
                                    width: field.width,
                                    height: field.height
                                };
                                break;
                            case 'orientation':
                                nbOption.odOption.orientation = $scope.validate_int( field.value );
                                break;
                            case 'area':
                                nbOption.odOption.area = $scope.validate_int( parseInt(field.value) + 1 );
                                break;
                            case 'padding':
                                var option = origin_field.general.attributes.options[field.value];
                                nbOption.odOption.padding = parseFloat(option.padding);
                                break;
                        }
                    }
                }
            });
            /* send option to editor */
            if( angular.equals( nbOption.odOption, nbOption.lastOdOption ) ){
                jQuery(document).triggerHandler( 'change_nbo_options_without_od_option' );
            }else{
                jQuery(document).triggerHandler( 'change_nbo_options_with_od_option' );
            };
            jQuery(document).triggerHandler( 'change_nbo_options' );
//            var frame = document.getElementById('onlinedesigner-designer');
//            if( frame ){
//                frame.contentWindow.postMessage('change_nbo_options', window.location.origin);
//            }
        };
        $scope.getFieldIndexById = function(field_id){
            var currentFieldIndex = 0;
            angular.forEach($scope.options.fields, function(__field, __index){
                if(__field.id == field_id) currentFieldIndex = __index;
            });
            return currentFieldIndex;
        };
        $scope.updateVariations = function(){
            nbOption.variations = [];
            var bulkForm = jQuery('.nbo-bulk-variation input, .nbo-bulk-variation select').serializeJSON();
            angular.forEach(bulkForm['nbb-qty-fields'], function(bf_field, bf_index){
                angular.forEach(bulkForm['nbb-fields'], function(bff_field, bff_id){
                    var origin_field = $scope.get_field(bff_id);
                    if( origin_field.nbd_type == 'size' ){
                        var first = true, name = '';
                        angular.forEach(bulkForm['nbb-fields'], function(_bff_field, _bff_id){
                            var _origin_field = $scope.get_field(_bff_id);
                            var _option = _origin_field.general.attributes.options[bulkForm['nbb-fields'][_bff_id][bf_index]];
                            var separate = first ? '' : ', ';
                            name += separate + _option.name;
                            first = false;
                        });
                        var option_size = origin_field.general.attributes.options[bulkForm['nbb-fields'][bff_id][bf_index]];
                        var size = {
                            product_width: $scope.validate_float( option_size.product_width ),
                            product_height: $scope.validate_float( option_size.product_height ),
                            real_width: $scope.validate_float( option_size.real_width ),
                            real_height: $scope.validate_float( option_size.real_height ),
                            real_top: $scope.validate_float( option_size.real_top ),
                            real_left: $scope.validate_float( option_size.real_left )
                        };
                        nbOption.variations.push({index: bf_index, qty: $scope.validate_int(bf_field), size: size, name: name});
                    }
                });
            });
            if(nbOption.variations.length){
                jQuery(document).triggerHandler( 'change_nbo_size_variations' );
            }
        };
        $scope.updateMultiselectValue = function(field_id){
            $scope.nbd_fields[field_id].values = [];
            angular.forEach($scope.nbd_fields[field_id]._values, function(val, index){
                if(val){
                    $scope.nbd_fields[field_id].values.push(index);
                }
            });
            $scope.nbd_fields[field_id].value = $scope.nbd_fields[field_id].values[0];
            $scope.check_valid();
        };
        $scope.update_dimensionvalue = function(field_id, dir){
            var origin_field = $scope.get_field(field_id),
            current_val = $scope.validate_float( $scope.nbd_fields[field_id][dir] ),
            min_val = $scope.validate_float( origin_field.general['min_' + dir] ),
            max_val = $scope.validate_float( origin_field.general['max_' + dir] );
            current_val = ( current_val < min_val ) ? min_val : current_val;
            current_val = ( max_val != 0 && current_val > max_val ) ? max_val : current_val;
            $scope.nbd_fields[field_id][dir] = current_val;
            $scope.nbd_fields[field_id].value = $scope.nbd_fields[field_id].width + 'x' + $scope.nbd_fields[field_id].height;
            $scope.check_valid();
        };
        $scope.update_dimension = function(field_id, dir, operator){
            var origin_field = $scope.get_field(field_id),
            current_val = $scope.validate_float( $scope.nbd_fields[field_id][dir] ),
            min_val = $scope.validate_float( origin_field.general['min_' + dir] ),
            max_val = $scope.validate_float( origin_field.general['max_' + dir] ),
            step_val = $scope.validate_float( origin_field.general['step_' + dir] );
            step_val = ( step_val == 0 ) ? 1 : step_val;
            if( operator == 'minus' ){
                current_val = (current_val - step_val) >= min_val ? (current_val - step_val) : min_val;
            }else{
                if( max_val != 0 ){
                    current_val = (current_val + step_val) <= max_val ? (current_val + step_val) : max_val;
                }
            }
            $scope.nbd_fields[field_id][dir] = current_val;
            $scope.update_dimensionvalue( field_id );
        };
        $scope.lastTickDpi = new Date().getTime();
        $scope.update_dpi = function(){
            $scope.lastTickDpi = new Date().getTime();
            $timeout(function() {
                var current = new Date().getTime();
                if( (current - $scope.lastTickDpi) >= 500){
                    $scope.check_valid();
                };
            }, 500);
        };
        $scope.set_product_image_attr = function(ele, attr, value, id){
            if( angular.isUndefined($scope.product_image[id]) || angular.isUndefined($scope.product_image[id][attr]) ){
                if( angular.isUndefined($scope.product_image[id]) ) $scope.product_image[id] = {};
                $scope.product_image[id][attr] = ele.attr( attr );
            }
            if ( false === value ) {
                ele.removeAttr( attr );
            }else{
                ele.attr( attr, value );
            }
        };
        $scope.reset_product_image_attr = function(ele, attr, id){
            ele.attr( attr, $scope.product_image[id][attr] );
            delete $scope.product_image[id][attr];
        };
        $scope.may_be_change_product_image = function(){
            $scope.product_img = [];
            angular.forEach($scope.nbd_fields, function(_field, field_id){
                var field = $scope.get_field(field_id);
                if( field.general.data_type == 'm' && field.appearance.change_image_product == 'y' && field.general.attributes.options[_field.value].imagep == 'y' ){
                    $scope.product_img.field_id  = field_id;
                    $scope.product_img.option_index  = _field.value;
                }
            });
            if( angular.isDefined($scope.product_img.field_id) && angular.isDefined($scope.product_img.option_index) ){
                $scope.change_product_image($scope.product_img.field_id, $scope.product_img.option_index);
            }
        };
        $scope.change_product_image = function( field_id, option_index ){
            var field = $scope.get_field(field_id);
            if( field.appearance.change_image_product == 'y' && field.general.attributes.options[option_index].imagep == 'y' ){
                var product_element = jQuery( '#product-'+ $scope.product_id );
                var product_image = product_element.find( '.woocommerce-product-gallery__image:not(.clone), .woocommerce-product-gallery__image--placeholder:not(.clone)' ).eq( 0 ).find( '.wp-post-image' ).first();
                if ( product_image.length === 0 ) {
                    product_image = product_element.find( "a.woocommerce-main-image img, img.woocommerce-main-image,a img" ).not( '.thumbnails img,.product_list_widget img' ).first();
                }
                if ( jQuery( product_image ).length > 1 ) {
                    product_image = jQuery( product_image ).first();
                }  
                var gallery_image = product_element.find( '.flex-control-nav li:eq(0) img' ),
                gallery_wrapper = product_element.find( '.woocommerce-product-gallery__wrapper ' ),
                product_image_wrap = gallery_wrapper.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' ).eq( 0 ),
                product_link = product_image.closest( 'a' );
                var option_data = field.general.attributes.options[option_index];
                if( !option_data.full_src ) option_data.full_src = option_data.image_link;
                if (product_image.length){
                    if( !option_data.full_src_w ) option_data.full_src = product_image.attr('data-large_image_width');
                    if( !option_data.full_src_h ) option_data.full_src_h = product_image.attr('data-large_image_height');
                    $scope.set_product_image_attr(product_image, 'src', option_data.image_link, 0);
                    $scope.set_product_image_attr(product_image, 'srcset', option_data.image_srcset, 0);
                    $scope.set_product_image_attr(product_image, 'sizes', option_data.image_sizes, 0);
                    $scope.set_product_image_attr(product_image, 'title', option_data.image_title, 0);
                    $scope.set_product_image_attr(product_image, 'alt', option_data.image_alt, 0);
                    $scope.set_product_image_attr(product_image, 'data-src', option_data.full_src, 0);
                    $scope.set_product_image_attr(product_image, 'data-large_image', option_data.full_src, 0);
                    $scope.set_product_image_attr(product_image, 'data-large_image_width', option_data.full_src_w, 0);
                    $scope.set_product_image_attr(product_image, 'data-large_image_height', option_data.full_src_h, 0);

                    $scope.set_product_image_attr(product_image, 'alt', option_data.alt, 0);
                    $scope.set_product_image_attr(product_image_wrap, 'data-thumb', option_data.image_link, 1);
                }
                if (gallery_image.length){
                    $scope.set_product_image_attr(gallery_image, 'src', option_data.image_link, 2);
                }
                if (product_link.length){
                    $scope.set_product_image_attr(product_link, 'href', option_data.full_src, 3);
                    $scope.set_product_image_attr(product_link, 'title', option_data.image_caption, 3);
                }
                $scope.init_product_gallery_and_zoom();
            }
        };
        $scope.change_product_image_without_field = function( option ){
            var product_element = jQuery( '#product-'+ $scope.product_id );
            var product_image = product_element.find( '.woocommerce-product-gallery__image:not(.clone), .woocommerce-product-gallery__image--placeholder:not(.clone)' ).eq( 0 ).find( '.wp-post-image' ).first();
            if ( product_image.length === 0 ) {
                product_image = product_element.find( "a.woocommerce-main-image img, img.woocommerce-main-image,a img" ).not( '.thumbnails img,.product_list_widget img' ).first();
            }
            if ( jQuery( product_image ).length > 1 ) {
                product_image = jQuery( product_image ).first();
            }  
            var gallery_image = product_element.find( '.flex-control-nav li:eq(0) img' ),
            gallery_wrapper = product_element.find( '.woocommerce-product-gallery__wrapper ' ),
            product_image_wrap = gallery_wrapper.find( '.woocommerce-product-gallery__image, .woocommerce-product-gallery__image--placeholder' ).eq( 0 ),
            product_link = product_image.closest( 'a' );
            if (product_image.length){
                $scope.set_product_image_attr(product_image, 'src', option.image_link, 0);
                $scope.set_product_image_attr(product_image, 'srcset', option.image_srcset, 0);
                $scope.set_product_image_attr(product_image, 'sizes', option.image_sizes, 0);
                $scope.set_product_image_attr(product_image, 'title', option.image_title, 0);
                $scope.set_product_image_attr(product_image, 'alt', option.image_alt, 0);
                $scope.set_product_image_attr(product_image, 'data-src', option.full_src, 0);
                $scope.set_product_image_attr(product_image, 'data-large_image', option.full_src, 0);
                $scope.set_product_image_attr(product_image, 'data-large_image_width', option.full_src_w, 0);
                $scope.set_product_image_attr(product_image, 'data-large_image_height', option.full_src_h, 0);

                $scope.set_product_image_attr(product_image, 'alt', option.alt, 0);
                $scope.set_product_image_attr(product_image_wrap, 'data-thumb', option.image_link, 1);
            }
            if (gallery_image.length){
                $scope.set_product_image_attr(gallery_image, 'src', option.image_link, 2);
            }
            if (product_link.length){
                $scope.set_product_image_attr(product_link, 'href', option.full_src, 3);
                $scope.set_product_image_attr(product_link, 'title', option.image_caption, 3);
            }
            $scope.init_product_gallery_and_zoom();
        };
        $scope.init_product_gallery_and_zoom = function(){
            var product_element = jQuery( '#product-'+ $scope.product_id );
            var gallery_element = product_element.find( '.woocommerce-product-gallery' );
            if( gallery_element.length && gallery_element.data( 'flexslider' ) ){
                gallery_element.flexslider( 0 );
                window.setTimeout( function () {
                    gallery_element.trigger( 'woocommerce_gallery_init_zoom' );
                    jQuery( window ).trigger( 'resize' );
                }, 10 );
            }
            var zoom_images = product_element.find( '.woocommerce-product-gallery__image' ),
                galleryWidth = product_element.find( '.woocommerce-product-gallery--with-images' ).width(),
                zoomEnabled  = false;
            jQuery( zoom_images ).each( function( index, target ) {
                var image = jQuery( target ).find( 'img.wp-post-image' );
                if ( image.attr( 'data-large_image_width' ) > galleryWidth ) {
                    zoomEnabled = true;
                    return false;
                }
            } ); 
            if ( zoomEnabled ){
                var zoom_options = {
                    touch: false
                };
                if ( 'ontouchstart' in window ) {
                    zoom_options.on = 'click';
                }
                zoom_images.trigger( 'zoom.destroy' );
                zoom_images.zoom( zoom_options );
            }else{
                zoom_images.trigger( 'zoom.destroy' );
            }
        };
        $scope.debug = function(){
            jQuery('input[name="quantity"]').val( 100 );
            jQuery('input[name="quantity"]').trigger( 'change.nbo' );
        };
        $scope.get_field = function(field_id){
            var _field = null;
            angular.forEach($scope.fields, function(field){
                if( field.id == field_id ) _field = field;
            });
            return _field;
        };
        $scope.check_depend = function( field_id ){
            if( angular.isUndefined($scope.nbd_fields[field_id]) ) return;
            var field = $scope.get_field(field_id),
            check = [];
            $scope.nbd_fields[field_id].enable = true;
            if( field.conditional.enable == 'n' ) return true;
            if( angular.isUndefined(field.conditional.depend) ) return true;
            if( field.conditional.depend.length == 0 ) return true;
            var show = field.conditional.show,
            logic = field.conditional.logic,
            total_check = logic == 'a' ? true : false;
            angular.forEach(field.conditional.depend, function(con, key){
                if( con.id != '' ){
                    if( angular.isUndefined($scope.nbd_fields[con.id]) || !$scope.nbd_fields[con.id].enable ){
                        check[key] = false;
                    }else{
                        switch(con.operator){
                            case 'i':
                                check[key] = $scope.nbd_fields[con.id].value == con.val ? true : false;
                                break;
                            case 'n':
                                check[key] = $scope.nbd_fields[con.id].value != con.val ? true : false;
                                break;  
                            case 'e':
                                check[key] = $scope.nbd_fields[con.id].value == '' ? true : false;
                                break;
                            case 'ne':
                                check[key] = $scope.nbd_fields[con.id].value != '' ? true : false;
                                break;                         
                        }
                    }
                }else{
                    check[key] = true;
                }
            });
            angular.forEach(check, function(c){
                total_check = logic == 'a' ? (total_check && c) : (total_check || c);
            });
            $scope.nbd_fields[field_id].enable = show == 'y' ? total_check : !total_check;
            return $scope.nbd_fields[field_id].enable;
        };
        $scope.init = function(){
            nbOption.status = true; 
            <?php if($options['display_type'] == 3 && count($options['bulk_fields'])): ?>
            jQuery('input[name="add-to-cart"]').remove();
            jQuery('button[name="add-to-cart"]').attr('name', 'nbo-add-to-cart');
            jQuery('input[name="quantity"], .quantity .screen-reader-text').remove();
            <?php endif; ?>
            <?php if($show_quantity_option): ?>
                $scope.quantity = $scope.validate_int("<?php echo $quantity; ?>");
                <?php if($disable_quantity_input): ?>
                    jQuery(qty_selector + ', input[name="quantity"]').addClass('nbo-disabled');
                <?php endif; ?>
                jQuery('input[name="quantity"]').val($scope.quantity);
            <?php endif; ?>
            <?php if($change_base == 'yes'): ?>
                <?php if( isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'NBO_Quick_View' && $nbd_qv_type == '2') : ?>
                var wrapEl = '#nbo-options-wrap .price';
                <?php else: ?>
                var wrapEl = '#product-' + $scope.product_id + ' .summary .price';
                <?php endif; ?>
            if(this.type == 'variable'){
                var price_html = jQuery(wrapEl + ' .woocommerce-Price-amount').first().clone(),
                nbo_price_html = jQuery(wrapEl + ' .nbo-base-price-html-var').clone();
                price_html.removeClass('amount');
                jQuery(wrapEl + ':first').html('').append(nbo_price_html).append(' ').append(price_html);
            }
            jQuery(wrapEl + ' del').remove();
            <?php endif; ?>
            $scope.nbd_fields = {};
            $scope.basePrice = $scope.convert_wc_price_to_float( $scope.price );
            $scope.total_price = 0;
            angular.forEach($scope.fields, function(field){
                if(field.general.enabled == 'y'){
                    $scope.nbd_fields[field.id] = {
                        title: field.general.title,
                        price: $scope.convert_to_wc_price(0),
                        required: field.general.required
                    };
                    if(field.general.data_type == 'i'){
                        if( field.general.input_type != 't' ){
                            if( field.general.input_type != 'u' ){
                                $scope.nbd_fields[field.id].value = field.general.input_option.min != '' ? field.general.input_option.min :  0;
                            }
                        }else{
                            $scope.nbd_fields[field.id].value = '';
                        }
                    }else{
                        if( field.general.attributes.options.length == 0 ){
                            $scope.nbd_fields[field.id].value = '0';
                        }else{
                            $scope.nbd_fields[field.id].value = '0';
                            angular.forEach(field.general.attributes.options, function(op, k){
                                if( op.selected == 'on' ) $scope.nbd_fields[field.id].value = '' + k;
                                op.form_name = '';
                                if( angular.isDefined(op.enable_subattr) && op.enable_subattr == 'on' ){
                                    if(angular.isDefined(op.sub_attributes)){
                                        $scope.nbd_fields[field.id].sub_value = '0';
                                        angular.forEach(op.sub_attributes, function(sop, sk){
                                            if( sop.selected == 'on' ) $scope.nbd_fields[field.id].sub_value = '' + sk;
                                        });
                                        if( op.sub_attributes.length > 0 ) op.form_name = '[value]';
                                    }
                                }
                            });
                            if( $scope.isMultipleSelectPage( field ) ){
                                if( angular.isDefined( $scope.form_values[field.id] ) ){
                                    $scope.nbd_fields[field.id].values = [parseInt($scope.nbd_fields[field.id].value)];
                                }else{
                                    $scope.nbd_fields[field.id].values = [];
                                }
                                $scope.nbd_fields[field.id]._values = [];
                                angular.forEach(field.general.attributes.options, function(op, k){
                                    if( angular.isDefined( $scope.form_values[field.id] ) ){
                                        $scope.nbd_fields[field.id]._values[k] = false;
                                    }else{
                                        $scope.nbd_fields[field.id]._values[k] = true;
                                        $scope.nbd_fields[field.id].values.push(k);
                                    }
                                    //$scope.nbd_fields[field.id]._values[k] = k == 0 ? true : false;
                                });
                            }
                        }
                    }
                }
            });
            angular.forEach($scope.form_values, function(value, field_id){
                if(field_id){
                    if( angular.isDefined(value['sub_value']) ){
                        $scope.nbd_fields[field_id].value = value['value'];
                        $scope.nbd_fields[field_id].sub_value = value['sub_value'];
                    }else{
                        $scope.nbd_fields[field_id].value = value;
                    }
                }
                var origin_field = $scope.get_field(field_id);
                if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'dimension' ){
                    var dimension = value.split("x");
                    $scope.nbd_fields[field_id].width = parseFloat(dimension[0]);
                    $scope.nbd_fields[field_id].height = parseFloat(dimension[1]);
                }
                if( $scope.isMultipleSelectPage( origin_field ) ){
                    $scope.nbd_fields[field_id].value = value[0];
                    $scope.nbd_fields[field_id].values = value;
                    angular.forEach(value, function(val){
                        $scope.nbd_fields[origin_field.id]._values[val] = true;
                    });                    
                }
            });
            angular.forEach($scope.fields, function(field){
                $scope.check_depend(field.id);
            });
            if( $scope.options.display_type == 2 && ( $scope.options.pm_hoz.length > 0 || $scope.options.pm_ver.length > 0 ) ){
                $scope.init_price_matrix();
                $scope.has_price_matrix = true;
            }
            $scope.check_valid();
            jQuery(document).on( 'change_nbo_variations', function(){
                $scope.upDateVaritionQty(NBDESIGNERPRODUCT.variations);
            });
        };
        $scope.upDateVaritionQty = function( variations ){
            jQuery.each(jQuery('.nbb-qty-field'), function(index, ip){
                jQuery(ip).val(variations[index].qty);
            });
        };
        $scope.reset_options = function(){
            <?php if($change_base == 'yes' && !($options['display_type'] == 3 && count($options['bulk_fields']))): ?>
            $scope.basePrice = $scope.validate_float($scope.price);
            if(this.type == 'variable'){
                var variation_id = jQuery('input[name="variation_id"], input.variation_id').val();
                $scope.basePrice = (variation_id != '' && variation_id != 0 ) ? $scope.validate_float($scope.variations[variation_id]) : $scope.validate_float($scope.basePrice);
            }
            <?php if( isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'NBO_Quick_View' && $nbd_qv_type == '2') : ?>
            var wrapEl = '#nbo-options-wrap';
            <?php else: ?>
            var wrapEl = '#product-' + $scope.product_id + ' .summary';
            <?php endif; ?>
            jQuery(wrapEl + ' .price .amount').html($scope.convert_to_wc_price( $scope.basePrice ));
            jQuery(wrapEl + ' .nbo-base-price-html').html(nbds_frontend.total);
            <?php endif; ?>
            $scope.init();
            <?php if( $options['quantity_enable'] == 'y' && !$is_sold_individually ): ?>
            $scope.quantity = $scope.validate_int("<?php echo $options['quantity_breaks'][0]['val']; ?>");
            <?php endif; ?>
            $scope.change_quantity();
            jQuery(document).triggerHandler( 'reset_nbo_options' );
        };
        $scope.change_quantity = function(){
            $timeout(function(){
                jQuery('input[name="quantity"]').val($scope.quantity).trigger( 'change.nbo' );
            });
        };
        $scope.select_all_variation = function( $event ){
            var el = angular.element($event.target),
            list = el.parents('table.nbo-bulk-variation').find('tbody input.nbo-bulk-checkbox'),
            check = el.prop('checked') ? true : false;
            jQuery.each(list, function(){
                jQuery(this).prop('checked', check);
            });
        };
        $scope.add_variaion = function( $event ){
            var el = angular.element($event.target),
            tb = el.parents('table.nbo-bulk-variation').find('tbody'),
            row = tb.find('tr').last().clone();
            tb.append(row);
        };
        $scope.delete_variaions = function( $event ){
            var el = angular.element($event.target),
            tb = el.parents('table.nbo-bulk-variation').find('tbody');
            jQuery.each(tb.find('input.nbo-bulk-checkbox:checked'), function(){
                if( tb.find('tr').length > 1 ) jQuery(this).parents('tr').remove();
            });
            el.parents('table.nbo-bulk-variation').find('input.nbo-bulk-checkbox').prop('checked', false);
        };
        $scope.init_price_matrix = function(){
            $scope.options.pm_num_col = 1;
            $scope.options.pm_num_row = 1;
            $scope.options.pm_hoz_field = [];
            $scope.options.pm_ver_field = [];
            $scope.options.pm_hoz.forEach(function(field, index){
                $scope.options.pm_num_col *= $scope.fields[field].general.attributes.options.length;
                var colspan = 1;
                $scope.options.pm_hoz.forEach(function(field, _index){
                    if(_index > index) colspan *= $scope.fields[field].general.attributes.options.length;
                });
                $scope.options.pm_hoz_field.push({field_id: $scope.fields[field].id, colspan: colspan});
            });
            $scope.options.pm_ver.forEach(function(field, index){
                $scope.options.pm_num_row *= $scope.fields[field].general.attributes.options.length;
                var rowspan = 1;
                $scope.options.pm_ver.forEach(function(field, _index){
                    if(_index > index) rowspan *= $scope.fields[field].general.attributes.options.length;
                });
                $scope.options.pm_ver_field.push({field_id: $scope.fields[field].id, rowspan: rowspan});                    
            });
            var i, j;
            $scope.options.price_matrix = [];
            for( i = 0; i < $scope.options.pm_num_row; i++ ){
                $scope.options.price_matrix[i] = [];
                for( j = 0; j < $scope.options.pm_num_col; j++ ){
                    var h_index = j;
                    $scope.options.price_matrix[i][j] = {
                        fields: {},
                        pm_fields: {},
                        discount_by_qty: 0,
                        total_price: 0,
                        class: '',
                        price: '?'
                    };
                    $scope.options.pm_hoz_field.forEach(function(field, index){
                        var field_val = Math.floor(h_index / field.colspan);
                        var field_index = $scope.options.pm_hoz[index];
                        $scope.options.price_matrix[i][j].pm_fields[$scope.fields[field_index].id] = field_val;
                        $scope.options.price_matrix[i][j].fields[$scope.fields[field_index].id] = {};
                        $scope.options.price_matrix[i][j].fields[$scope.fields[field_index].id].value = field_val;
                        h_index = h_index % field.colspan;
                    });
                    var v_index = i;
                    $scope.options.pm_ver_field.forEach(function(field, index){
                        var field_val = Math.floor(v_index / field.rowspan);
                        var field_index = $scope.options.pm_ver[index];
                        $scope.options.price_matrix[i][j].pm_fields[$scope.fields[field_index].id] = field_val;
                        $scope.options.price_matrix[i][j].fields[$scope.fields[field_index].id] = {};
                        $scope.options.price_matrix[i][j].fields[$scope.fields[field_index].id].value = field_val;                        
                        v_index = v_index % field.rowspan;
                    });
                    if( $scope.form_values ){
                        var _check_class = true;
                        angular.forEach($scope.options.price_matrix[i][j].pm_fields, function(value, field_id){
                            if( value != $scope.form_values[field_id] ) _check_class = false;
                        });
                        if( _check_class ) $scope.options.price_matrix[i][j].class = 'selected';
                        if( $scope.form_values.length == 0 && i == 0 && j == 0 ){
                            $scope.options.price_matrix[i][j].class = 'selected'; 
                        }
                    }
                }
            }
        };
        $scope.calculate_price_matrix = function(){
            var i, j;
            var calculate_price = function( _fields ){
                var basePrice = $scope.price;
                if($scope.type == 'variable'){
                    var variation_id = jQuery('input[name="variation_id"], input.variation_id').val();
                    basePrice = (variation_id != '' && variation_id != 0 ) ? $scope.variations[variation_id] : basePrice;
                }
                basePrice = $scope.convert_wc_price_to_float(basePrice); 
                var total_price = 0,
                discount_by_qty = 0,
                qty = 0;
                if( $scope.is_sold_individually == 1 ){
                    qty = 1;
                }else{
                    qty = $scope.validate_int(jQuery('input[name="quantity"]').val());
                }
                var quantity_break = $scope.get_quantity_break(qty);
                var xfactor = 1;
                angular.forEach(_fields, function(field, field_id){
                    if(field.enable){
                        var origin_field = $scope.get_field(field_id);
                        var factor = null;
                        if( origin_field.general.data_type == 'i' ){
                            if(origin_field.general.depend_quantity == 'n'){
                                factor = origin_field.general.price;
                            }else{
                                if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                                    factor = '';
                                }else{
                                    factor = origin_field.general.price_breaks[quantity_break.index];
                                }
                            }
                        }else{
                            var option = origin_field.general.attributes.options[field.value];
                            if(option){
                                if(origin_field.general.depend_quantity == 'n'){
                                    factor = $scope.validate_float( option.price[0] );
                                }else{
                                    if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                                        factor = 0;
                                    }else{
                                        factor = $scope.validate_float( option.price[quantity_break.index] );
                                    }
                                }
                                if( angular.isDefined(option.enable_subattr) && option.enable_subattr == 'on' ){
                                    if(angular.isDefined(option.sub_attributes) && option.sub_attributes.length > 0){
                                        soption_price = option.sub_attributes[field.sub_value].price;
                                        if(origin_field.general.depend_quantity == 'n'){
                                            factor += $scope.validate_float( soption_price[0] );
                                        }else{
                                            if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){

                                            }else{
                                                factor += $scope.validate_float( soption_price[quantity_break.index] );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if( $scope.isMultipleSelectPage( origin_field ) ){
                            factor = [];
                            angular.forEach(field.values, function(val, v_index){
                                var option = origin_field.general.attributes.options[val];
                                if(origin_field.general.depend_quantity == 'n'){
                                    factor[v_index] = option.price[0];
                                }else{
                                    if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                                        factor[v_index] = '';
                                    }else{
                                        factor[v_index] = option.price[quantity_break.index];
                                    }
                                }                            
                            });
                            field.price = 0;
                            var xfac = 0;
                            angular.forEach(factor, function(fac){
                                fac = $scope.validate_float(fac);
//                                total_price += fac;
//                                field.price += fac;
                                switch(origin_field.general.price_type){
                                    case 'f':
                                        field.price += fac;
                                        total_price += fac;
                                        break;
                                    case 'p':
                                        field.price += $scope.basePrice * fac / 100;
                                        total_price += $scope.basePrice * fac / 100;
                                        break;
                                    case 'p+':
                                        field.price += fac / 100;
                                        xfac += fac / 100;
                                        field.is_pp = 1;
                                        break;
                                }
                            });
                            field.price = $scope.convert_to_wc_price( field.price ); 
                            if(origin_field.general.price_type == 'p+'){
                                xfactor *= (1 + xfac / 100);
                            }
                        }else{
                            factor = $scope.validate_float(factor) ;
                            field.is_pp = 0;
                            if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'page' 
                                    && origin_field.general.data_type == 'i' ){
                                factor *= $scope.validate_int( $scope.nbd_fields[field_id].value );
                            }
                            switch(origin_field.general.price_type){
                                case 'f':
                                    field.price = $scope.convert_to_wc_price( factor );
                                    total_price += factor;
                                    break;
                                case 'p':
                                    field.price = $scope.convert_to_wc_price( basePrice * factor / 100 );
                                    total_price += ($scope.basePrice * factor / 100);
                                    break;
                                case 'p+':
                                    field.price = factor / 100;
                                    xfactor *= (1 + factor / 100);
                                    field.is_pp = 1;
                                    break;
                                case 'c':
                                    field.price = $scope.convert_to_wc_price( factor * $scope.validate_int( field.value ) );
                                    total_price += factor * $scope.validate_int( field.value );
                                    break;
                                case 'cp':
                                    field.price = $scope.convert_to_wc_price( factor * $scope.validate_int( field.value.length ) );
                                    total_price += factor * $scope.validate_int( field.value.length );
                                    break;
                            }
                        }
                    }
                });
                total_price += ( (basePrice + total_price ) * (xfactor - 1 ) );
                angular.forEach(_fields, function(field){
                    if( field.is_pp == 1 ) field.price = $scope.convert_to_wc_price( field.price * (basePrice + total_price ) / ( field.price + 1 ) );
                });
                var qty_factor = null;
                if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                    qty_factor = '';
                }else{
                    qty_factor = $scope.options.quantity_breaks[quantity_break.index].dis;
                }
                qty_factor = $scope.validate_float(qty_factor);
                discount_by_qty = $scope.options.quantity_discount_type == 'f' ? qty_factor : (basePrice + total_price ) * qty_factor / 100;
                var final_price = basePrice + total_price - discount_by_qty;
                return final_price > 0 ? final_price : 0;
            };  
            var check_depend = function( field_id, pm_fields ){
                var field = $scope.get_field(field_id),
                check = [];
                pm_fields[field_id].enable = true;
                if( field.conditional.enable == 'n' ) return true;
                if( angular.isUndefined(field.conditional.depend) ) return true;
                if( field.conditional.depend.length == 0 ) return true;
                var show = field.conditional.show,
                logic = field.conditional.logic,
                total_check = logic == 'a' ? true : false;
                angular.forEach(field.conditional.depend, function(con, key){
                    if( con.id != '' ){
                        switch(con.operator){
                            case 'i':
                                check[key] = pm_fields[con.id].value == con.val ? true : false;
                                break;
                            case 'n':
                                check[key] = pm_fields[con.id].value != con.val ? true : false;
                                break;  
                            case 'e':
                                check[key] = pm_fields[con.id].value == '' ? true : false;
                                break;
                            case 'ne':
                                check[key] = pm_fields[con.id].value != '' ? true : false;
                                break;                         
                        }
                    }else{
                        check[key] = true;
                    }
                });
                angular.forEach(check, function(c){
                    total_check = logic == 'a' ? (total_check && c) : (total_check || c);
                });
                pm_fields[field_id].enable = show == 'y' ? total_check : !total_check;
            };            
            for( i = 0; i < $scope.options.pm_num_row; i++ ){
                for( j = 0; j < $scope.options.pm_num_col; j++ ){
                    angular.forEach($scope.nbd_fields, function(field, field_id){
                        var val = field.value;
                        if( angular.isDefined($scope.options.price_matrix[i][j].pm_fields[field_id]) ){
                            val = $scope.options.price_matrix[i][j].pm_fields[field_id];
                        }else{
                            $scope.options.price_matrix[i][j].fields[field_id] = {};
                        }
                        angular.copy(field, $scope.options.price_matrix[i][j].fields[field_id]);
                        if( val !== null && angular.isDefined(val) ){
                            $scope.options.price_matrix[i][j].fields[field_id].value = '' + val;
                        }else{
                            $scope.options.price_matrix[i][j].fields[field_id].value = val;
                        }
                    });
                    angular.forEach($scope.options.price_matrix[i][j].fields, function(field, field_id){
                        check_depend(field_id, $scope.options.price_matrix[i][j].fields);
                    });
                    var total_price = calculate_price( $scope.options.price_matrix[i][j].fields );
                    $scope.options.price_matrix[i][j].price = $scope.convert_to_wc_price( total_price );
                }
            }
        };
        $scope.select_price_matrix = function(_i, _j){
            var i, j;
            for( i = 0; i < $scope.options.pm_num_row; i++ ){
                for( j = 0; j < $scope.options.pm_num_col; j++ ){
                    $scope.options.price_matrix[i][j].class = '';
                }
            }
            $scope.options.price_matrix[_i][_j].class = 'selected';
            angular.copy($scope.options.price_matrix[_i][_j].fields, $scope.nbd_fields);
            $scope.check_valid( false );
        };
        $scope.convert_to_wc_price = function(price, required){
            <?php if( $hide_zero_price == 'yes' ): ?> 
            var precision = parseInt(nbds_frontend.currency_format_num_decimals);
            if( price.toFixed(precision) == 0 && angular.isUndefined(required) ) return '';
            <?php endif; ?>
            return accounting.formatMoney( price, {
                symbol: nbds_frontend.currency_format_symbol,
                decimal: nbds_frontend.currency_format_decimal_sep,
                thousand: nbds_frontend.currency_format_thousand_sep,
                precision: nbds_frontend.currency_format_num_decimals,
                format: nbds_frontend.currency_format
            });
        };
        $scope.convert_wc_price_to_float = function(price){ return $scope.validate_float(price);
            var c = jQuery.trim(nbds_frontend.currency_format_thousand_sep).toString(), 
                d = jQuery.trim(nbds_frontend.currency_format_decimal_sep).toString();
            return price = price.replace(/ /g, ""), price = "." === c ? price.replace(/\./g, "") : price.replace(new RegExp(c,"g"), ""), price = price.replace(d, "."), price = parseFloat(price);            
        };
        $scope.validate_int = function(input){
            var output = parseInt(input);
            if( isNaN(output) ) output = 0;
            if( output < 0 ) output = 0;
            return output;
        };
        $scope.validate_float = function(input){
            var output = parseFloat(input);
            if( isNaN(output) ) output = 0;
            return output;
        };
        $scope.get_quantity_break = function( qty ){
            var quantity_break = {index: 0, oparator: 'gt'};
            var quantity_breaks = [];
            angular.forEach($scope.options.quantity_breaks, function(_break, key){
                quantity_breaks[key] = $scope.validate_int(_break.val);
            });
            angular.forEach(quantity_breaks, function(_break, key){
                if( key == 0 && qty < _break){
                    quantity_break = {index: 0, oparator: 'lt'};
                }
                if( qty >= _break && key < ( quantity_breaks.length - 1 ) ){
                    quantity_break = {index: key, oparator: 'bw'};
                }
                if( key == ( quantity_breaks.length - 1 ) && qty >= _break){
                    quantity_break = {index: key, oparator: 'gt'};
                }
            });
            return quantity_break;
        };
        $scope.calculate_price = function(){
            $scope.basePrice = $scope.price;
            if(this.type == 'variable'){
                var variation_id = jQuery('input[name="variation_id"], input.variation_id').val();
                $scope.basePrice = (variation_id != '' && variation_id != 0 ) ? $scope.variations[variation_id] : $scope.basePrice;
            }
            $scope.basePrice = $scope.convert_wc_price_to_float($scope.basePrice); 
            $scope.total_price = 0;
            $scope.discount_by_qty = 0;
            var qty = 0; 
            if( $scope.is_sold_individually == 1 ){
                qty = 1;
            }else{
                qty = $scope.validate_int(jQuery('input[name="quantity"]').val());
            }
            $scope._qty = qty;
            var quantity_break = $scope.get_quantity_break(qty);
            var xfactor = 1;
            angular.forEach($scope.nbd_fields, function(field, field_id){
                if(field.enable){
                    var origin_field = $scope.get_field(field_id);
                    var factor = null;
                    if( origin_field.general.data_type == 'i' ){
                        if(origin_field.general.depend_quantity == 'n'){
                            factor = origin_field.general.price;
                        }else{
                            if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                                factor = '';
                            }else{
                                factor = origin_field.general.price_breaks[quantity_break.index];
                            }
                        }
                        if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'dimension' 
                                && origin_field.general.mesure == 'y' && angular.isDefined(origin_field.general.mesure_range) && origin_field.general.mesure_range.length > 0 ){
                            factor = $scope.calculate_price_base_measurement(origin_field.general.mesure_range, field.width, field.height);
                            if( (origin_field.general.price_type == 'f' || origin_field.general.price_type == 'c')
                                    && origin_field.general.mesure_base_pages == 'y' ){
                                if( angular.isDefined(nbOption.odOption.page) ){
                                    var _origin_field = $scope.get_field(nbOption.odOption.page.field_id);
                                    if( _origin_field.general.data_type == 'i' ){
                                        factor *= Math.floor( (nbOption.odOption.page.number + 1) / 2 );
                                    }else{
                                        //factor *= Math.floor( (nbOption.odOption.page.list_page.length + 1) / 2 );
                                    }
                                }
                            }
                        }
                    }else{
                        var option = origin_field.general.attributes.options[field.value];
                        if(option){
                            var option_price =  option.price;
                            if(origin_field.general.depend_quantity == 'n'){
                                factor = $scope.validate_float( option_price[0] );
                            }else{
                                if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                                    factor = 0;
                                }else{
                                    factor = $scope.validate_float( option_price[quantity_break.index] );
                                }
                            }
                            if( angular.isDefined(option.enable_subattr) && option.enable_subattr == 'on' ){
                                if(angular.isDefined(option.sub_attributes) && option.sub_attributes.length > 0){
                                    soption_price = option.sub_attributes[field.sub_value].price;
                                    if(origin_field.general.depend_quantity == 'n'){
                                        factor += $scope.validate_float( soption_price[0] );
                                    }else{
                                        if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                                            
                                        }else{
                                            factor += $scope.validate_float( soption_price[quantity_break.index] );
                                        }
                                    }
                                }
                            }
                        }
                    }
                    if( $scope.isMultipleSelectPage( origin_field ) ){
                        factor = [];
                        angular.forEach(field.values, function(val, v_index){
                            var option = origin_field.general.attributes.options[val];
                            if(origin_field.general.depend_quantity == 'n'){
                                factor[v_index] = option.price[0];
                            }else{
                                if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                                    factor[v_index] = '';
                                }else{
                                    factor[v_index] = option.price[quantity_break.index];
                                }
                            }                            
                        });
                        field.price = 0;
                        var xfac = 0;
                        angular.forEach(factor, function(fac){
                            fac = $scope.validate_float(fac);
//                            $scope.total_price += fac;
//                            field.price += fac;
                            switch(origin_field.general.price_type){
                                case 'f':
                                    field.price += fac;
                                    $scope.total_price += fac;
                                    break;
                                case 'p':
                                    field.price += $scope.basePrice * fac / 100;
                                    $scope.total_price += $scope.basePrice * fac / 100;
                                    break;
                                case 'p+':
                                    field.price += fac / 100;
                                    xfac += fac / 100;
                                    field.is_pp = 1;
                                    break;
                            }
                        });
                        field.price = $scope.convert_to_wc_price( field.price );
                        if(origin_field.general.price_type == 'p+'){
                            xfactor *= (1 + xfac / 100);
                        }
                    }else{
                        factor = $scope.validate_float(factor) ;
                        field.is_pp = 0;
                        if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'dimension' 
                                && origin_field.general.price_type == 'c' ){
                            origin_field.general.price_type = 'f';
                        }
                        if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'page' 
                                && origin_field.general.data_type == 'i' ){
                            factor *= $scope.validate_int( $scope.nbd_fields[field_id].value );
                        }
                        switch(origin_field.general.price_type){
                            case 'f':
                                field.price = $scope.convert_to_wc_price( factor );
                                $scope.total_price += factor;
                                break;
                            case 'p':
                                field.price = $scope.convert_to_wc_price( $scope.basePrice * factor / 100 );
                                $scope.total_price += ($scope.basePrice * factor / 100);
                                break;
                            case 'p+':
                                field.price = factor / 100;
                                xfactor *= (1 + factor / 100);
                                field.is_pp = 1;
                                break;
                            case 'c':
                                field.price = $scope.convert_to_wc_price( factor * $scope.validate_int( field.value ) );
                                $scope.total_price += factor * $scope.validate_int( field.value );
                                break; 
                            case 'cp':
                                field.price = $scope.convert_to_wc_price( factor * $scope.validate_int( field.value.length ) );
                                $scope.total_price += factor * $scope.validate_int( field.value.length );
                                break;
                        }
                    }
                }
            });
            $scope.total_price += ( ($scope.basePrice + $scope.total_price ) * (xfactor - 1 ) );
            angular.forEach($scope.nbd_fields, function(field){
                if( field.is_pp == 1 ) field.price = $scope.convert_to_wc_price( field.price * ($scope.basePrice + $scope.total_price ) / ( field.price + 1 ) );
            });
            var qty_factor = null;
            if( quantity_break.index == 0 && quantity_break.oparator == 'lt' ){
                qty_factor = '';
            }else{
                qty_factor = $scope.options.quantity_breaks[quantity_break.index].dis;
            }
            qty_factor = $scope.validate_float(qty_factor);
            $scope.discount_by_qty = $scope.options.quantity_discount_type == 'f' ? qty_factor : ($scope.basePrice + $scope.total_price ) * qty_factor / 100;
            $scope.final_price = $scope.total_price + $scope.basePrice - $scope.discount_by_qty;
            $scope.final_price = $scope.final_price > 0 ? $scope.final_price : 0;           
            $scope.total_cart_price = $scope.final_price * qty;
            $scope.total_cart_price = $scope.convert_to_wc_price( $scope.total_cart_price );
            <?php if($change_base == 'yes' && !($options['display_type'] == 3 && count($options['bulk_fields']))): ?>
                <?php if( isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'NBO_Quick_View' && $nbd_qv_type == '2') : ?>
                var wrapEl = '#nbo-options-wrap';
                <?php else: ?>
                var wrapEl = '#product-' + $scope.product_id + ' .summary';
                <?php endif; ?>                                                
            jQuery(wrapEl + ' .price .amount').html($scope.total_cart_price);
            jQuery(wrapEl + ' .nbo-base-price-html').html(nbds_frontend.total);
            <?php endif; ?>
            $scope.final_price = $scope.convert_to_wc_price( $scope.final_price, true );
            $scope.total_price = $scope.convert_to_wc_price( $scope.total_price, true );
            $scope.discount_by_qty = $scope.convert_to_wc_price( $scope.discount_by_qty, true );
        };
        $scope.calculate_price_table = function(){
            $scope.price_table = [];
            $scope.basePrice = $scope.price;
            if(this.type == 'variable'){
                var variation_id = jQuery('input[name="variation_id"], input.variation_id').val();
                $scope.basePrice = (variation_id != '' && variation_id != 0 ) ? $scope.variations[variation_id] : $scope.basePrice;
            }
            $scope.basePrice = $scope.convert_wc_price_to_float($scope.basePrice); 
            var quantity_breaks = [];
            angular.forEach($scope.options.quantity_breaks, function(_break, key){
                quantity_breaks[key] = $scope.validate_int(_break.val);
            });
            var _qty = 0;
            if( $scope.is_sold_individually == 1 ){
                _qty = 1;
            }else{
                _qty = $scope.validate_int(jQuery('input[name="quantity"]').val());
            }
            angular.forEach(quantity_breaks, function(_break, key){
                var pt;
                if( key == 0 && _break > 1 ) {
                    pt = {};
                    pt.from = 1;
                    pt.up = _break - 1;
                    pt.quantity_break = {index: 0, oparator: 'lt'};
                    $scope.price_table.push(pt);
                }
                if( key > 0 && key < (quantity_breaks.length) ){
                    pt = {};
                    pt.from = quantity_breaks[key - 1];
                    pt.up = _break - 1;
                    pt.quantity_break = {index: key - 1, oparator: 'bw'};
                    $scope.price_table.push(pt);
                }
                if( key == (quantity_breaks.length - 1) ){
                    pt = {};
                    pt.from = _break;
                    pt.up = '**';
                    pt.quantity_break = {index: key, oparator: 'gt'};
                    $scope.price_table.push(pt);
                }
            });
            angular.forEach($scope.price_table, function(pt, pt_index){
                pt.nbd_fields = {};
                pt.in_range = ( _qty >= pt.from && ( _qty <= pt.up || pt.up == '**' ) ) ? true : false;
                angular.copy($scope.nbd_fields, pt.nbd_fields);
                pt.total_price = 0;
                pt.discount_by_qty = 0;
                var xfactor = 1;
                angular.forEach(pt.nbd_fields, function(field, field_id){
                    if(field.enable){
                        var origin_field = $scope.get_field(field_id);
                        var factor = null;
                        if( origin_field.general.data_type == 'i' ){
                            if(origin_field.general.depend_quantity == 'n'){
                                factor = origin_field.general.price;
                            }else{
                                if( pt.quantity_break.index == 0 && pt.quantity_break.oparator == 'lt' ){
                                    factor = '';
                                }else{
                                    factor = origin_field.general.price_breaks[pt.quantity_break.index];
                                }
                            }
                            if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'dimension' 
                                    && origin_field.general.mesure == 'y' && angular.isDefined(origin_field.general.mesure_range) && origin_field.general.mesure_range.length > 0 ){
                                factor = $scope.calculate_price_base_measurement(origin_field.general.mesure_range, field.width, field.height);
                                if( (origin_field.general.price_type == 'f' || origin_field.general.price_type == 'c')
                                        && origin_field.general.mesure_base_pages == 'y' ){
                                    if( angular.isDefined(nbOption.odOption.page) ){
                                        factor *= Math.floor( (nbOption.odOption.page.number + 1) / 2 );
                                    }
                                }
                            }
                        }else{
                            var option = origin_field.general.attributes.options[field.value];
                            if(option){
                                if(origin_field.general.depend_quantity == 'n'){
                                    factor = $scope.validate_float( option.price[0] );
                                }else{
                                    if( pt.quantity_break.index == 0 && pt.quantity_break.oparator == 'lt' ){
                                        factor = 0;
                                    }else{
                                        factor = $scope.validate_float( option.price[pt.quantity_break.index] );
                                    }
                                }
                                if( angular.isDefined(option.enable_subattr) && option.enable_subattr == 'on' ){
                                    if(angular.isDefined(option.sub_attributes) && option.sub_attributes.length > 0){
                                        soption_price = option.sub_attributes[field.sub_value].price;
                                        if(origin_field.general.depend_quantity == 'n'){
                                            factor += $scope.validate_float( soption_price[0] );
                                        }else{
                                            if( pt.quantity_break.index == 0 && pt.quantity_break.oparator == 'lt' ){

                                            }else{
                                                factor += $scope.validate_float( soption_price[pt.quantity_break.index] );
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        if( $scope.isMultipleSelectPage( origin_field ) ){
                            factor = [];
                            angular.forEach(field.values, function(val, v_index){
                                var option = origin_field.general.attributes.options[val];
                                if(origin_field.general.depend_quantity == 'n'){
                                    factor[v_index] = option.price[0];
                                }else{
                                    if( pt.quantity_break.index == 0 && pt.quantity_break.oparator == 'lt' ){
                                        factor[v_index] = '';
                                    }else{
                                        factor[v_index] = option.price[pt.quantity_break.index];
                                    }
                                }                            
                            });
                            field.price = 0;
                            var xfac = 0;
                            angular.forEach(factor, function(fac){
                                fac = $scope.validate_float(fac);
//                                pt.total_price += fac;
//                                field.price += fac;
                                switch(origin_field.general.price_type){
                                    case 'f':
                                        field.price += fac;
                                        pt.total_price += fac;
                                        break;
                                    case 'p':
                                        field.price += $scope.basePrice * fac / 100;
                                        pt.total_price += $scope.basePrice * fac / 100;
                                        break;
                                    case 'p+':
                                        field.price += fac / 100;
                                        xfac += fac / 100;
                                        field.is_pp = 1;
                                        break;
                                }
                            });
                            field.price = $scope.convert_to_wc_price( field.price ); 
                            if(origin_field.general.price_type == 'p+'){
                                xfactor *= (1 + xfac / 100);
                            }                            
                        }else{
                            factor = $scope.validate_float(factor) ;
                            field.is_pp = 0;
                            if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'dimension' 
                                && origin_field.general.price_type == 'c' ){
                                origin_field.general.price_type = 'f';
                            }
                            if( angular.isDefined(origin_field.nbd_type) && origin_field.nbd_type == 'page' 
                                    && origin_field.general.data_type == 'i' ){
                                factor *= $scope.validate_int( $scope.nbd_fields[field_id].value );
                            }
                            switch(origin_field.general.price_type){
                                case 'f':
                                    field.price = $scope.convert_to_wc_price( factor );
                                    pt.total_price += factor;
                                    break;
                                case 'p':
                                    field.price = $scope.convert_to_wc_price( $scope.basePrice * factor / 100 );
                                    pt.total_price += ($scope.basePrice * factor / 100);
                                    break;
                                case 'p+':
                                    field.price = factor / 100;
                                    xfactor *= (1 + factor / 100);
                                    field.is_pp = 1;
                                    break;
                                case 'c':
                                    field.price = $scope.convert_to_wc_price( factor * $scope.validate_int( field.value ) );
                                    pt.total_price += factor * $scope.validate_int( field.value );
                                    break; 
                                case 'cp':
                                    field.price = $scope.convert_to_wc_price( factor * $scope.validate_int( field.value.length ) );
                                    pt.total_price += factor * $scope.validate_int( field.value.length );
                                    break;
                            }
                        }
                    }
                });
                pt.total_price += ( ($scope.basePrice + pt.total_price ) * (xfactor - 1 ) );
                angular.forEach(pt.nbd_fields, function(field){
                    if( field.is_pp == 1 ) field.price = $scope.convert_to_wc_price( field.price * ($scope.basePrice + pt.total_price ) / ( field.price + 1 ) );
                });
                var qty_factor = null;
                if( pt.quantity_break.index == 0 && pt.quantity_break.oparator == 'lt' ){
                    qty_factor = '';
                }else{
                    qty_factor = $scope.options.quantity_breaks[pt.quantity_break.index].dis;
                }
                qty_factor = $scope.validate_float(qty_factor);
                pt.discount_by_qty = $scope.options.quantity_discount_type == 'f' ? qty_factor : ($scope.basePrice + pt.total_price ) * qty_factor / 100;
                pt.final_price = pt.total_price + $scope.basePrice - pt.discount_by_qty;
                pt.final_price = pt.final_price > 0 ? pt.final_price : 0;
                pt.final_price = $scope.convert_to_wc_price( pt.final_price, true );
                pt.total_price = $scope.convert_to_wc_price( pt.total_price, true );
                pt.discount_by_qty = $scope.convert_to_wc_price( pt.discount_by_qty, true );
            });
        };
        $scope.isMultipleSelectPage = function(field){
            if( angular.isDefined(field.nbd_type) && field.nbd_type == 'page' && field.general.data_type == 'm' ){
                return true;
            }
            return false;
        };
        $scope.calculate_price_base_measurement = function(mesure_range, width, height){
            var area = $scope.validate_float(width) * $scope.validate_float(height);
            var price_per_unit = 0, start_range = 0, end_range = 0, price_range = 0;
            angular.forEach(mesure_range, function(range, key){
                start_range = $scope.validate_float(range[0]);
                end_range = $scope.validate_float(range[1]);
                price_range = $scope.validate_float(range[2]);
                if( start_range <= area && ( area <= end_range || end_range == 0 ) ){
                    price_per_unit = price_range;
                }
                if( start_range <= area && key == ( mesure_range.length - 1 ) && area > end_range  ){
                    price_per_unit = price_range;
                }
            });
            return price_per_unit * area;
        };
        $scope.update_app = function(){
            if ($scope.$root.$$phase !== "$apply" && $scope.$root.$$phase !== "$digest") $scope.$apply(); 
        };
        $scope.init();
    }]).directive('stringToNumber', function() {
        return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function(value) {
                    if( value === null ) value = '';
                    return '' + value;
                });
                ngModel.$formatters.push(function(value) {
                    return parseFloat(value);
                });
            }
        };
    }).directive('convertToNumber', function() {
        return {
            require: 'ngModel',
            link: function(scope, element, attrs, ngModel) {
                ngModel.$parsers.push(function(val) {
                    return val != null ? parseInt(val, 10) : null;
                });
                ngModel.$formatters.push(function(val) {
                    return val != null ? '' + val : null;
                });
            }
        };
    }).directive('nboClickDebounce', function ($timeout) {
        var delay = 500;
        return {
            restrict: 'A',
            priority: -1,
            link: function (scope, elem) {
                var disabled = false;
                function onClick(evt) {
                    if (disabled) {
                        evt.preventDefault();
                        evt.stopImmediatePropagation();
                    } else {
                        disabled = true;
                        $timeout(function () { disabled = false; }, delay, false);
                    }
                }
                scope.$on('$destroy', function () { elem.off('click', onClick); });
                elem.on('click', onClick);
            }
        };
    }).directive( 'nbdHelpTip', function($timeout) {
        return {
            restrict: 'C',
            scope: {
                position: '@position'
            },
            link: function( scope, element, attrs ) {
                var tiptip_args = {
                    'attribute': 'data-tip',
                    'fadeIn': 50,
                    'fadeOut': 50,
                    'delay': 200,
                    defaultPosition: scope.position ? scope.position : "top"
                };
                $timeout(function() {
                    jQuery(element).tipTip( tiptip_args );
                }, 0);
            }
        };
    }).directive( 'nboInputFile', function($timeout, $window) {
        return {
            restrict: 'A',
            require: 'ngModel',
            scope: {
                fileChange: '&',
                fieldId: '@fieldId',
                types: '@types',
                file: '@',
                filename: '@',
                uploaded: '@',
                minsize: '@',
                maxsize: '@'
            },
            link: function( scope, element, attrs, ctrl ) {
                if( scope.uploaded == 1 ){
                    ClipboardEvent = $window.ClipboardEvent,
                    DataTransfer = $window.DataTransfer;
                    try {
                        var el = element[0];
                        if (ClipboardEvent || DataTransfer ){
                            var dT = new ClipboardEvent('').clipboardData || new DataTransfer();
                            dT.items.add(new File([scope.file], scope.filename));
                            el.files = dT.files;
                            onChange( 'init' );
                        }
                    }catch(err){
                        console.log(err);
                    }
                }
                element.on('change', onChange);
                scope.$on('destroy', function () {
                    element.off('change', onChange);
                });
                function onChange( init ) {
                    if( init != 'init' ){
                        var file = element[0].files[0];
                        function resetInput(){
                            ctrl.$setViewValue('');
                            jQuery(element).val('');
                            scope.fileChange();
                            return false;
                        };
                        if( scope.maxsize != '' ){
                            var max_size = parseInt( scope.maxsize ) * 1024 * 1024;
                            if( max_size < file.size ){
                                alert('<?php _e('Sorry, file is too big, max size: ', 'web-to-print-online-designer'); ?>' + scope.maxsize + 'MB');
                                resetInput();
                            }
                        }
                        if( scope.minsize != '' ){
                            var minsize = parseInt( scope.minsize ) * 1024 * 1024;
                            if( minsize > file.size ){
                                alert('<?php _e('Sorry, file is too small, min size: ', 'web-to-print-online-designer'); ?>' + scope.minsize + 'MB');
                                resetInput();
                            }
                        }
                        if( scope.types != '' ){
                            var types = scope.types.replace(/ /g,'').split(','),
                            filetype = file.type.toLowerCase(),
                            checType = false;
                            filetype = filetype != '' ? filetype : file.name.substring(file.name.lastIndexOf('.')+1).toLowerCase();
                            angular.forEach(types, function(type){
                                if( filetype.indexOf(type) > -1 ){
                                    checType = true;
                                }
                            });
                            if( !checType ){
                                alert('<?php _e('Sorry, this file type is not permitted for security reasons. Only accept: ', 'web-to-print-online-designer'); ?>' + scope.types);
                                resetInput();
                            }
                        }
                    }
                    ctrl.$setViewValue(element[0].files[0]);
                    jQuery(element).parent('.nbd-field-content').find('.nbd-upload-hidden').remove();
                    scope.fileChange();
                }
            }
        };
    }).filter('to_trusted', ['$sce', function($sce){
        return function(text) {
            var div = document.createElement('div');
            text += '';
            div.innerHTML = text;
            return $sce.trustAsHtml(div.textContent);
        };            
    }]);
    <?php if( !(isset($_REQUEST['wc-api']) && $_REQUEST['wc-api'] == 'NBO_Quick_View') ) : ?>
        var appEl = document.getElementById('<?php echo $appid; ?>');
        angular.element(function() {
            angular.bootstrap(appEl, ['nboApp']);
        });
    <?php endif; ?>
    jQuery(document).on( 'update_nbo_options_from_builder', function(e, data){
        var $scope = angular.element(document.getElementById(nbOption.crtlId)).scope();
        angular.forEach(data.nbd_fields, function(nbd_field, field_id){
            $scope.nbd_fields[field_id].value = nbd_field.value;
            $scope.nbd_fields[field_id].sub_value = nbd_field.sub_value;
        });
        $scope.check_valid( true, true );
    });
    jQuery(document).on( 'update_product_image_from_builder', function(e, data){
        var $scope = angular.element(document.getElementById(nbOption.crtlId)).scope();
        $scope.change_product_image_without_field( data );
    });
</script>
</div>