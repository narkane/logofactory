<?php
if (!defined('ABSPATH')) exit;
$class = nbdesigner_get_option('nbdesigner_class_design_button_detail'); 
$_enable_upload = get_post_meta($pid, '_nbdesigner_enable_upload', true);
$_enable_upload_without_design = get_post_meta($pid, '_nbdesigner_enable_upload_without_design', true);
$label = apply_filters('nbd_start_design_label', __('Start Design', 'web-to-print-online-designer'));
if( $_enable_upload ){
    $label = apply_filters('nbd_start_design_and_upload_label', __('Start and upload design', 'web-to-print-online-designer'));
}
if( $_enable_upload_without_design ){
    $label = apply_filters('nbd_upload_design_label', __('Upload Design', 'web-to-print-online-designer'));
}else{
    $_enable_upload_without_design = 0;
}
$layout = nbd_get_product_layout($pid);
//$show_button_use_our_template = 1;
$show_button_use_our_template = 0;
?>
<style>
    .nbd-save-for-later, .nbd-download-pdf {
        border: 1px solid #ddd !important;
        background: #fff !important;
        color: #333333 !important;
        padding: 1em 2em;
        font-weight: bold;
        font-size: 0.875rem;
        line-height: 1em;  
        border-radius: 2em;
    }
    a.nbd-save-for-later svg {
        display: none;
        margin-right: 10px;
    }
    a.nbd-save-for-later:focus {
        outline: none;
    }
    a.nbd-save-for-later.saved {
        pointer-events: none;
    }
    .nbd-social {
        width: 36px;
        height: 36px;
        display: inline-block;
        padding: 5px;
        border: 1px solid #ddd;
        margin: 0px;
        opacity: 0.8;
        -webkit-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
        background: #fff;
        cursor: pointer;
    }    
    .nbd-save-loading, .nbd-pdf-loading {
        display: inline-block;
        margin-right: 10px;
        vertical-align: middle;
    }
    .nbd-save-loading.hide, .nbd-pdf-loading.hide {
        display: none;
    }
    #nbdesigner-preview-title {
        margin-top: 15px;
    }
    #nbd_processing {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: #fff;
        -webkit-transition: all .4s;
        -moz-transition: all .4s;
        -o-transition: all .4s;
        transition: all .4s;
        z-index: 1;
    }    
    .nbd-popup-wrap {
        z-index: 2;
        background: rgba(245, 246, 247, 0.95);
        -webkit-transition: opacity 200ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;
        -moz-transition: opacity 200ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;
        transition: opacity 200ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        left: 0;   
        overflow: hidden;
    }
    .nbd-popup-wrap.is-hidden {
        visibility: hidden;
        opacity: 0;
        z-index: -1;        
    }
    .nbd__pop__content {
        position: absolute;
        margin-top: auto;
        margin-bottom: auto;
        left: 50%;
        margin-left: auto;
        margin-right: auto;
        transform: translate(-50%, -50%);
        top: 48%;  
        width: 100%;
    }
    .nbd__pop__content_wrapper {
        padding-right: 1.5rem;
        padding-left: 1.5rem;  
        max-width: 60rem;
        margin: 0 auto;        
    }
    .nbd__pop__content_wrapper.nbd__pop_wide {
        max-width: 76.5rem;
    }
    .__content_wrapper {
        width: 100% !important;
        position: relative !important;
        display: inline-block;
    }
    .content__header {
        bottom: 100%;
        width: 100%;
        padding-bottom: 3rem;
        position: absolute;
        text-align: center;
        margin: 0;
        font-size: 1.875rem;
        line-height: 1.15;
    }
    .content__content {
        margin: 0;
        display: flex;
        margin-top: -1.5rem;
        margin-left: -1.5rem;
        font-size: 0;
        flex-wrap: wrap;
        -ms-flex-pack: center;
        justify-content: center;        
    }
    .layout__item {
        width: 50%;
        font-size: 1rem;
        padding-left: 1.5rem;
        position: relative;
        padding-top: 1.5rem;
        display: flex;
        display: -ms-flexbox;
    }
    .nbd__pop_wide .layout__item {
        width: 33.333333%;
    }
    .layout__item__inner {
        box-shadow: 0 0.125rem 0.25rem 0 rgba(79,90,109,0.25);
        flex-direction: row;
        -ms-flex-direction: row;
        text-align: left;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -ms-flex-positive: 1;
        flex-grow: 1;
        -webkit-transition: box-shadow 200ms linear, opacity 200ms;
        -moz-transition: box-shadow 200ms linear, opacity 200ms;
        transition: box-shadow 200ms linear, opacity 200ms;
        background-color: #fff; 
        position: relative;
        cursor: pointer;
    }
    .item__layout {
        overflow: hidden;
        flex: 1 1 auto;
        font-size: 0;
        display: -ms-flexbox;
        display: flex;
        margin: 0;
        padding: 0;   
        margin-left: -1.5rem;
        -ms-flex-direction: row;
        flex-direction: row;        
    }
    .tile__media-wrap {
        min-height: 0;
        display: -ms-flexbox;
        display: flex;     
        padding-left: 1.5rem;
        width: 33.333333%;  
        position: relative;
        overflow: hidden;
        font-size: 1rem;
    }
    .tile-action__image-wrap {
        height: auto;
        position: relative;
        text-align: center;
        margin: 0;
        width: 100%;
    }
    .custom_design .tile-action__image-wrap {
        background-color: #f1eb9c; 
    }
    .upload_design .tile-action__image-wrap {
        background-color: #afcdd7;
    } 
    .use_our_design .tile-action__image-wrap {
        background-color: #b6b8dc; 
    }
    .tile-action__image-wrap svg {
        position: absolute !important;
        top: 50% !important;
        margin-top: auto !important;
        margin-bottom: auto !important;
        left: 50% !important;
        margin-left: auto !important;
        margin-right: auto !important;
        transform: translate(-50%, -50%) !important; 
        height: 50%;
        width: 50%;
    }
    .tile__text-wrap {
        font-size: 1rem;
        width: 66.666667%;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;  
        padding-left: 1.125rem;
    }
    .tile__text-wrap-inner {
        -ms-flex: 1 1 auto;
        flex: 1 1 auto;
        padding: 1.5rem 2.25rem 1.5rem 0;
    }
    .h__block {
        line-height: 1.15;
        font-size: 1.35rem;
        margin-bottom: 0.1875em;
        font-weight: 500;
        color: #3f4a59;
    }
    .layout__item__inner:hover {
        box-shadow: 0 0.1875rem 0.625rem 0 rgba(79,90,109,0.3);
    }
    .tile__text-wrap-inner ul{
        margin: 0;
        list-style: none;
    }
    .tile--horizontal__chevron {
        height: 1.5rem;
        width: 1.5rem;        
        position: absolute;
        top: 50%;
        margin-top: auto;
        margin-bottom: auto;
        transform: translateY(-50%);
        right: 0.75rem;
        fill: #128a67;
        transition: right 200ms;        
    }
    .layout__item__inner:hover .tile--horizontal__chevron{
        right: 0.4375rem;
    }  
    .nbd-m-custom-design-wrap,
    .nbd-m-upload-design-wrap {
        position: absolute;
        z-index: -1;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        opacity: 0;
        visibility: hidden;
        background: #fff;
    }
    .nbd-m-custom-design-wrap.is-visible,
    .nbd-m-upload-design-wrap.is-visible {
        opacity: 1;
        visibility: visible; 
        z-index: 1;
    }    
    .nbd-custom-design-wrap {
        position: relative;
        width: 100%;
        height: 100%;
    }
    .nbd-custom-design-wrap iframe {
        left: 0;
        top: 0;
        position: absolute;
    }
    .nbd-m-upload-design-wrap {
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;        
    }
    .inputfile {
        width: 0.1px;
        height: 0.1px;
        opacity: 0;
        overflow: hidden;
        position: absolute;
        z-index: -1;
    }    
    .inputfile + label {
        width: 320px;
        flex-direction: column;
        display: flex;
        text-align: center;
        justify-content: center;
        align-items: center;
        border: 2px dashed #ddd;
        border-radius: 4px;
        color: #394264;
        cursor: pointer;
        padding: 10px;
        margin: 0 auto;
    } 
    .inputfile + label.highlight {
        border-color: #394264;
    }
    .inputfile + label svg {
        width: 2em;
        height: 2em;
        vertical-align: middle;
        fill: currentColor;
        margin-top: -0.25em;
        margin-right: 0.25em;
    }
    .upload-zone span {
        display: block;
        line-height: 12px;
    }
    .nbd-upload-items {
        width: 150px;
        height: 150px;
        display: inline-block;
        margin: 15px;        
    }
    .nbd-upload-items-inner {
        display: flex;
        align-items: flex-end;
        justify-content: center;
        width: 100%;
        height: 100%;
        text-align: center;
        position: relative;
        overflow: hidden;
    } 
    .nbd-upload-item {
        max-width: 100%;
        max-height: 100%;
    }
    .nbd-upload-item-title {
        position: absolute;
        border: 0;
        background: #fff;
        width: 100%;
        height: 30px;
        line-height: 30px;
        text-overflow: ellipsis;
        overflow: hidden;
        padding: 0 5px;
        white-space: nowrap;
        font-weight: bold;
        background: rgba(255, 255, 255, 0.75);
        border-bottom-right-radius: 3px;
        border-bottom-left-radius: 3px;        
    }
    .nbd-upload-items-inner span {
        position: absolute;
        z-index: 2;
        width: 30px;
        height: 30px;
        cursor: pointer;
        background: #fff;
        line-height: 30px;
        -webkit-transform: translateY(30px);
        -moz-transform: translateY(30px);
        transform: translateY(30px);
        -webkit-transition: all 0.4s;
        -moz-transition: all 0.4s;
        transition: all 0.4s;
        border-radius: 50%;
        font-size: 20px;
        color: #cc324b;
    }
    .nbd-upload-items-inner:hover span {
        -webkit-transform: translateY(-10px);
        -moz-transform: translateY(-10px);
        transform: translateY(-10px);
    }
    .upload-design-preview {
        margin: 15px;
        max-height: 300px;
        max-width: 720px;
        position: relative;
        overflow: hidden;
    }    
    .submit-upload-design:hover {
        box-shadow: 0 11px 15px -7px rgba(0,0,0,.2), 0 24px 38px 3px rgba(0,0,0,.14), 0 9px 46px 8px rgba(0,0,0,.12);
    }
    .submit-upload-design {
        height: 40px;
        border-radius: 20px;
        background: #fff;
        padding: 0 15px;
        color: #394264;
        text-transform: uppercase;
        font-weight: bold;
        line-height: 40px;
        cursor: pointer;
        display: inline-block;
        margin-top: 15px;
        box-shadow: 0 5px 6px -3px rgba(0,0,0,.2), 0 9px 12px 1px rgba(0,0,0,.14), 0 3px 16px 2px rgba(0,0,0,.12);
    }    
    .upload-zone {
        position: relative;
    }
    .upload-zone .nbd-upload-loading {
        position: absolute;
        top: 50%;
        left: 50%;
        -webkit-transform: translate(-50%, -50%);
        -moz-transform: translate(-50%, -50%);
        transform: translate(-50%, -50%);
        z-index: -1;
        visibility: hidden;
        opacity: 0;
    }
    .upload-zone .nbd-upload-loading.is-visible {
        visibility: visible;
        z-index: 2;
        opacity: 1;
    }
    .inputfile + label.is-loading {
        opacity: 0.75;
    }
    .nbd-m-upload-design-wrap.is-loading {
        pointer-events: none;
    }
    #container-online-designer {
        position: fixed; 
        top: 0; 
        left: 0; 
        bottom: 0;
        left: 0;
        width: 100vw; 
        height: 100vh; 
        z-index: -1; 
        opacity: 0;
        visibility: hidden;
        -webkit-transition: opacity 400ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;
        -moz-transition: opacity 400ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;
        transition: opacity 400ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;        
    }
    #container-online-designer.is-visible {
        opacity: 1;
        z-index: 999999999; 
        visibility: visible;
        -webkit-transition: opacity 200ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;
        -moz-transition: opacity 200ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;
        transition: opacity 200ms 0ms, visibility 0ms 0ms, z-index 0ms 0ms;        
    }
    .rtl .tile__text-wrap-inner {
        padding: 1.5rem 0 1.5rem 2.25rem;
    }
    @media only screen and (max-width: 40.0525em){
        .nbd-popup-wrap{
            padding: 5rem 0 3rem;
        }
        .layout__item {
            width: 100% !important;
        }
        .tile__media-wrap {
            width: 25%;
        }
        .tile__text-wrap {
            width: 75%;
        }      
        .content__header {
            position: unset;
        }
    }  
    @media screen and (max-width: 768px){
        #container-online-designer {
            height: 100%;
        }
    }
</style>
<script type="text/javascript">
    var nbd_layout = '<?php echo $layout; ?>';
    var is_nbd_upload = <?php echo $_enable_upload; ?>;
    var use_our_template = <?php echo $show_button_use_our_template; ?>;
    var is_nbd_upload_without_design = <?php echo $_enable_upload_without_design; ?>;
</script>
<?php do_action('before_nbdesigner_frontend_container', $pid, $option); ?>
<div class="nbdesigner_frontend_container">
    <input name="nbd-add-to-cart" type="hidden" value="<?php echo $pid; ?>" />
    <p>
        <a class="button nbdesigner_disable alt nbdesign-button <?php echo $class; ?>" id="triggerDesign" >
            <img class="nbdesigner-img-loading hide" src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>"/>
            <?php echo $label; ?>
        </a>
    </p>   
    <h4 id="nbdesigner-preview-title" style="display: none;"><?php _e('Custom design', 'web-to-print-online-designer'); ?></h4>    
    <div id="nbd-actions" style="display: none;">
    <?php
        if( $layout == 'c' && nbdesigner_get_option('nbdesigner_save_for_later') == 'yes' ):
    ?>
        <p>
            <?php if( is_user_logged_in() ): ?>
            <a href="javascript:void(0)" onclick="NBDESIGNERPRODUCT.save_for_later()" class="button alt nbd-save-for-later" id="nbd-save-for-later">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 14 14">
                    <title>check2</title>
                    <path fill="#0085ba" d="M13.055 4.422c0 0.195-0.078 0.391-0.219 0.531l-6.719 6.719c-0.141 0.141-0.336 0.219-0.531 0.219s-0.391-0.078-0.531-0.219l-3.891-3.891c-0.141-0.141-0.219-0.336-0.219-0.531s0.078-0.391 0.219-0.531l1.062-1.062c0.141-0.141 0.336-0.219 0.531-0.219s0.391 0.078 0.531 0.219l2.297 2.305 5.125-5.133c0.141-0.141 0.336-0.219 0.531-0.219s0.391 0.078 0.531 0.219l1.062 1.062c0.141 0.141 0.219 0.336 0.219 0.531z"></path>
                </svg>
                <img class="nbd-save-loading hide" src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>"/> 
                <?php _e('Save for later', 'web-to-print-online-designer'); ?>
            </a>
            <?php endif; ?>
            <?php
                $allow_donwload_pdf = false;
                if( $allow_donwload_pdf ):
            ?>
            <a href="javascript:void(0)" onclick="NBDESIGNERPRODUCT.download_pdf()" class="button alt nbd-download-pdf">
                <img class="nbd-pdf-loading hide" src="<?php echo NBDESIGNER_PLUGIN_URL.'assets/images/loading.gif' ?>"/> 
                <?php _e('Download PDF', 'web-to-print-online-designer'); ?>
            </a>
            <?php endif; ?>
        </p>
    <?php endif; ?>
    <?php
        if( $layout == 'c' && nbdesigner_get_option('nbdesigner_share_design') == 'yes' ):
    ?>  
        <p id="nbd-share-group">
            <?php _e('Share design', 'web-to-print-online-designer'); ?><br />
            <a href="#" data-href="https://facebook.com/sharer/sharer.php?u=" target="_blank" class="nbd-social" title="Facebook">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>facebook</title>
                    <path fill="#3b5998" d="M22.675 0h-21.351c-0.732 0-1.325 0.593-1.325 1.325v21.351c0 0.732 0.593 1.325 1.325 1.325h11.495v-9.294h-3.129v-3.621h3.129v-2.674c0-3.099 1.893-4.785 4.659-4.785 1.325 0 2.463 0.096 2.794 0.141v3.24h-1.92c-1.5 0-1.793 0.72-1.793 1.77v2.31h3.585l-0.465 3.63h-3.12v9.284h6.115c0.732 0 1.325-0.593 1.325-1.325v-21.351c0-0.732-0.593-1.325-1.325-1.325z"></path>
                </svg>           
            </a>
            <a href="#" data-href="https://plus.google.com/share?url=" target="_blank" class="nbd-social" title="Google">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>google</title>
                    <path fill="#dc4e41" d="M7.635 10.909v2.618h4.335c-0.174 1.125-1.309 3.295-4.331 3.295-2.606 0-4.732-2.16-4.732-4.822s2.123-4.822 4.728-4.822c1.485 0 2.478 0.633 3.045 1.179l2.073-1.995c-1.331-1.245-3.056-1.995-5.115-1.995-4.226-0.002-7.638 3.418-7.638 7.634s3.414 7.635 7.635 7.635c4.41 0 7.332-3.097 7.332-7.461 0-0.501-0.054-0.885-0.12-1.264h-7.212zM24 10.909h-2.183v-2.182h-2.183v2.182h-2.181v2.181h2.182v2.182h2.19v-2.182h2.174z"></path>
                </svg>            
            </a>
            <a href="#" data-href="https://twitter.com/share?url=" data-text="<?php _e('My design', 'web-to-print-online-designer'); ?>" target="_blank" class="nbd-social" title="Twitter">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>twitter</title>
                    <path fill="#1da1f2" d="M24 4.557c-0.885 0.39-1.83 0.655-2.828 0.776 1.015-0.611 1.797-1.575 2.165-2.724-0.951 0.555-2.006 0.96-3.128 1.185-0.897-0.96-2.175-1.56-3.594-1.56-2.718 0-4.923 2.205-4.923 4.92 0 0.39 0.045 0.765 0.127 1.125-4.092-0.195-7.72-2.16-10.149-5.13-0.426 0.721-0.666 1.561-0.666 2.476 0 1.71 0.87 3.214 2.19 4.098-0.807-0.026-1.567-0.248-2.231-0.615v0.060c0 2.385 1.695 4.377 3.95 4.83-0.414 0.111-0.849 0.171-1.298 0.171-0.315 0-0.615-0.030-0.915-0.087 0.63 1.956 2.445 3.379 4.605 3.42-1.68 1.32-3.81 2.106-6.105 2.106-0.39 0-0.78-0.023-1.17-0.067 2.19 1.395 4.77 2.211 7.56 2.211 9.060 0 14.010-7.5 14.010-13.995 0-0.21 0-0.42-0.015-0.63 0.96-0.69 1.8-1.56 2.46-2.55z"></path>
                </svg>            
            </a>   
            <a href="#" data-href="https://www.linkedin.com/shareArticle?mini=true&url=" target="_blank" class="nbd-social" title="Linkedin">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>linkedin</title>
                    <path fill="#0077b5" d="M20.448 20.453h-3.555v-5.569c0-1.329-0.027-3.037-1.851-3.037-1.852 0-2.136 1.446-2.136 2.94v5.667h-3.555v-11.453h3.414v1.56h0.045c0.477-0.9 1.638-1.85 3.37-1.85 3.6 0 4.267 2.37 4.267 5.456v6.282zM5.337 7.433c-1.143 0-2.064-0.926-2.064-2.065 0-1.137 0.921-2.063 2.064-2.063 1.14 0 2.064 0.925 2.064 2.063 0 1.14-0.926 2.065-2.064 2.065zM7.119 20.453h-3.564v-11.453h3.564v11.453zM22.224 0h-20.454c-0.978 0-1.77 0.774-1.77 1.73v20.541c0 0.956 0.792 1.729 1.77 1.729h20.453c0.978 0 1.777-0.774 1.777-1.73v-20.541c0-0.956-0.799-1.73-1.777-1.73z"></path>
                </svg>            
            </a>
            <a href="#" data-href="mailto:?subject=Check%20out%20my%20design!&body=" target="_blank" class="nbd-social" title="Mail">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>mail</title>
                    <path fill="#d14836" d="M24 4.5v15c0 0.851-0.649 1.5-1.5 1.5h-1.5v-13.612l-9 6.462-9-6.462v13.612h-1.5c-0.85 0-1.5-0.649-1.5-1.5v-15c0-0.425 0.162-0.8 0.43-1.068 0.27-0.272 0.646-0.432 1.070-0.432h0.499l10.001 7.25 10-7.25h0.5c0.424 0 0.799 0.162 1.069 0.432 0.269 0.268 0.431 0.644 0.431 1.068z"></path>
                </svg>            
            </a> 
            <a href="#" data-href="http://pinterest.com/pin/create/button/?url=" data-description="<?php _e('My design', 'web-to-print-online-designer'); ?>" target="_blank" class="nbd-social" id="nbd-pinterest" title="Pinterest">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>pinterest</title>
                    <path fill="#bd081c" d="M12 0c-6.627 0-12 5.373-12 12 0 5.085 3.162 9.428 7.626 11.175-0.105-0.949-0.2-2.406 0.042-3.442 0.219-0.938 1.407-5.965 1.407-5.965s-0.36-0.72-0.36-1.782c0-1.665 0.969-2.915 2.172-2.915 1.024 0 1.518 0.769 1.518 1.69 0 1.031-0.654 2.569-0.993 3.996-0.285 1.196 0.6 2.168 1.777 2.168 2.13 0 3.771-2.247 3.771-5.493 0-2.865-2.064-4.875-5.013-4.875-3.414 0-5.415 2.565-5.415 5.205 0 1.035 0.394 2.145 0.889 2.745 0.099 0.12 0.112 0.225 0.086 0.345-0.090 0.375-0.294 1.2-0.335 1.365-0.053 0.225-0.172 0.27-0.402 0.165-1.497-0.692-2.436-2.881-2.436-4.652 0-3.78 2.751-7.26 7.931-7.26 4.161 0 7.398 2.97 7.398 6.93 0 4.14-2.61 7.47-6.24 7.47-1.215 0-2.355-0.63-2.76-1.38l-0.75 2.85c-0.27 1.047-1.005 2.355-1.5 3.15 1.125 0.345 2.31 0.535 3.555 0.535 6.615 0 12-5.37 12-12s-5.385-12-12-12z"></path>
                </svg>        
            </a>
            <a href="#" data-href="https://web.skype.com/share?url=" target="_blank" class="nbd-social" title="Skype">
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                    <title>skype</title>
                    <path fill="#00aff0" d="M12.053 18.9c-4.028 0-5.827-1.983-5.827-3.47 0-0.765 0.561-1.297 1.335-1.297 1.725 0 1.275 2.479 4.492 2.479 1.644 0 2.554-0.895 2.554-1.812 0-0.552-0.27-1.163-1.356-1.431l-3.581-0.896c-2.885-0.724-3.408-2.289-3.408-3.756 0-3.051 2.865-4.197 5.556-4.197 2.475 0 5.4 1.374 5.4 3.204 0 0.784-0.69 1.24-1.455 1.24-1.47 0-1.2-2.040-4.17-2.040-1.47 0-2.295 0.666-2.295 1.62s1.155 1.26 2.16 1.49l2.64 0.588c2.895 0.649 3.63 2.349 3.63 3.95 0 2.478-1.905 4.329-5.73 4.329zM23.098 14.011l-0.030 0.135-0.045-0.24c0.015 0.045 0.045 0.075 0.060 0.12 0.12-0.675 0.18-1.365 0.18-2.055 0-1.53-0.3-3.015-0.9-4.425-0.57-1.35-1.395-2.565-2.43-3.6-1.050-1.035-2.25-1.86-3.6-2.43-1.319-0.632-2.803-0.931-4.334-0.931-0.72 0-1.446 0.071-2.145 0.206l0.12 0.060-0.24-0.034 0.12-0.024c-0.964-0.518-2.047-0.792-3.147-0.792-1.791 0-3.476 0.699-4.743 1.97s-1.965 2.96-1.965 4.755c0 1.144 0.292 2.268 0.844 3.263l0.019-0.125 0.042 0.24-0.060-0.115c-0.114 0.645-0.172 1.3-0.172 1.957 0 1.533 0.3 3.021 0.885 4.422 0.57 1.365 1.38 2.58 2.43 3.615 1.035 1.050 2.25 1.86 3.6 2.445 1.395 0.6 2.88 0.9 4.41 0.9 0.66 0 1.335-0.060 1.98-0.18l-0.12-0.060 0.24 0.045-0.135 0.030c1.005 0.57 2.13 0.87 3.3 0.87 1.785 0 3.465-0.69 4.74-1.965 1.26-1.26 1.965-2.955 1.965-4.755 0-1.14-0.3-2.265-0.855-3.27z"></path>
                </svg>          
            </a>             
        </p>
    <?php endif; ?>
    </div>    
    <div id="nbdesigner_frontend_area"></div>
    <h4 id="nbdesigner-upload-title" style="display: none;"><?php _e('Upload file', 'web-to-print-online-designer'); ?></h4>
    <div id="nbdesigner_upload_preview" style="margin-bottom: 15px;"></div>
    <?php if($extra_price != ''): ?>
    <p><?php _e('Extra price for design', 'web-to-print-online-designer'); ?> + <?php echo $extra_price; ?></p>
    <?php endif; ?>
    <?php do_action('nbd_after_single_product_design_section', $pid, $option); ?>
</div>
<?php do_action('after_nbdesigner_frontend_container', $pid, $option); ?>
<div style="" id="container-online-designer" data-iframe="<?php echo $src; ?>">
    <?php if($layout == 'c'): ?>
    <div id="nbd-custom-design-wrap" class="nbd-custom-design-wrap">
        <div id="nbd_processing">
            <div class="atom-loading">
                <div class="loading__ring">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M85.5,42c-0.2-0.8-0.5-1.7-0.8-2.5c-0.3-0.9-0.7-1.6-1-2.3c-0.3-0.7-0.6-1.3-1-1.9c0.3,0.5,0.5,1.1,0.8,1.7  c0.2,0.7,0.6,1.5,0.8,2.3s0.5,1.7,0.8,2.5c0.8,3.5,1.3,7.5,0.8,12c-0.4,4.3-1.8,9-4.2,13.4c-2.4,4.2-5.9,8.2-10.5,11.2  c-1.1,0.7-2.2,1.5-3.4,2c-0.5,0.2-1.2,0.6-1.8,0.8s-1.3,0.5-1.9,0.8c-2.6,1-5.3,1.7-8.1,1.8l-1.1,0.1L53.8,84c-0.7,0-1.4,0-2.1,0  c-1.4-0.1-2.9-0.1-4.2-0.5c-1.4-0.1-2.8-0.6-4.1-0.8c-1.4-0.5-2.7-0.9-3.9-1.5c-1.2-0.6-2.4-1.2-3.7-1.9c-0.6-0.3-1.2-0.7-1.7-1.1  l-0.8-0.6c-0.3-0.1-0.6-0.4-0.8-0.6l-0.8-0.6L31.3,76l-0.2-0.2L31,75.7l-0.1-0.1l0,0l-1.5-1.5c-1.2-1-1.9-2.1-2.7-3.1  c-0.4-0.4-0.7-1.1-1.1-1.7l-1.1-1.7c-0.3-0.6-0.6-1.2-0.9-1.8c-0.2-0.5-0.6-1.2-0.8-1.8c-0.4-1.2-1-2.4-1.2-3.7  c-0.2-0.6-0.4-1.2-0.5-1.9c-0.1-0.6-0.2-1.2-0.3-1.8c-0.3-1.2-0.3-2.4-0.4-3.7c-0.1-1.2,0-2.5,0.1-3.7c0.2-1.2,0.3-2.4,0.6-3.5  c0.1-0.6,0.3-1.1,0.4-1.7l0.1-0.8l0.3-0.8c1.5-4.3,3.8-8,6.5-11c0.8-0.8,1.4-1.5,2.1-2.1c0.9-0.9,1.4-1.3,2.2-1.8  c1.4-1.2,2.9-2,4.3-2.8c2.8-1.5,5.5-2.3,7.7-2.8s4-0.7,5.2-0.6c0.6-0.1,1.1,0,1.4,0s0.4,0,0.4,0h0.1c2.7,0.1,5-2.2,5-5  c0.1-2.7-2.2-5-5-5c-0.2,0-0.2,0-0.3,0c0,0-0.2,0.1-0.6,0.1c-0.4,0-1,0-1.8,0.1c-1.6,0.1-4,0.4-6.9,1.2c-2.9,0.8-6.4,2-9.9,4.1  c-1.8,1-3.6,2.3-5.4,3.8C26,21.4,25,22.2,24.4,23c-0.2,0.2-0.4,0.4-0.6,0.6c-0.2,0.2-0.5,0.4-0.6,0.7c-0.5,0.4-0.8,0.9-1.3,1.4  c-3.2,3.9-5.9,8.8-7.5,14.3l-0.3,1l-0.2,1.1c-0.1,0.7-0.3,1.4-0.4,2.1c-0.3,1.5-0.4,2.9-0.5,4.5c0,1.5-0.1,3,0.1,4.5  c0.2,1.5,0.2,3,0.6,4.6c0.1,0.7,0.3,1.5,0.4,2.3c0.2,0.8,0.5,1.5,0.7,2.3c0.4,1.6,1.1,3,1.7,4.4c0.3,0.7,0.7,1.4,1.1,2.1  c0.4,0.8,0.8,1.4,1.2,2.1c0.5,0.7,0.9,1.4,1.4,2s0.9,1.3,1.5,1.9c1.1,1.3,2.2,2.7,3.3,3.5l1.7,1.6c0,0,0.1,0.1,0.1,0.1c0,0,0,0,0,0  c0,0,0,0,0,0l0.1,0.1l0.1,0.1h0.2l0.5,0.4l1,0.7c0.4,0.2,0.6,0.5,1,0.7l1.1,0.6c0.8,0.4,1.4,0.9,2.1,1.2c1.4,0.7,2.9,1.5,4.4,2  c1.5,0.7,3.1,1,4.6,1.5c1.5,0.3,3.1,0.7,4.7,0.8c1.6,0.2,3.1,0.2,4.7,0.2c0.8,0,1.6-0.1,2.4-0.1l1.2-0.1l1.1-0.1  c3.1-0.4,6.1-1.3,8.9-2.4c0.8-0.3,1.4-0.6,2.1-0.9s1.3-0.7,2-1.1c1.3-0.7,2.6-1.7,3.7-2.5c0.5-0.4,1-0.9,1.6-1.3l0.8-0.6l0.2-0.2  c0,0,0.1-0.1,0.1-0.1c0.1-0.1,0,0,0,0v0.1l0.1-0.1l0.4-0.4c0.5-0.5,1-1,1.5-1.5c0.3-0.3,0.5-0.5,0.8-0.8l0.7-0.8  c0.9-1.1,1.8-2.2,2.5-3.3c0.4-0.6,0.7-1.1,1.1-1.7c0.3-0.7,0.6-1.2,0.9-1.8c2.4-4.9,3.5-9.8,3.7-14.4C87.3,49.7,86.6,45.5,85.5,42z"></path></svg>
                </div>
                <div class="loading__ring">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M85.5,42c-0.2-0.8-0.5-1.7-0.8-2.5c-0.3-0.9-0.7-1.6-1-2.3c-0.3-0.7-0.6-1.3-1-1.9c0.3,0.5,0.5,1.1,0.8,1.7  c0.2,0.7,0.6,1.5,0.8,2.3s0.5,1.7,0.8,2.5c0.8,3.5,1.3,7.5,0.8,12c-0.4,4.3-1.8,9-4.2,13.4c-2.4,4.2-5.9,8.2-10.5,11.2  c-1.1,0.7-2.2,1.5-3.4,2c-0.5,0.2-1.2,0.6-1.8,0.8s-1.3,0.5-1.9,0.8c-2.6,1-5.3,1.7-8.1,1.8l-1.1,0.1L53.8,84c-0.7,0-1.4,0-2.1,0  c-1.4-0.1-2.9-0.1-4.2-0.5c-1.4-0.1-2.8-0.6-4.1-0.8c-1.4-0.5-2.7-0.9-3.9-1.5c-1.2-0.6-2.4-1.2-3.7-1.9c-0.6-0.3-1.2-0.7-1.7-1.1  l-0.8-0.6c-0.3-0.1-0.6-0.4-0.8-0.6l-0.8-0.6L31.3,76l-0.2-0.2L31,75.7l-0.1-0.1l0,0l-1.5-1.5c-1.2-1-1.9-2.1-2.7-3.1  c-0.4-0.4-0.7-1.1-1.1-1.7l-1.1-1.7c-0.3-0.6-0.6-1.2-0.9-1.8c-0.2-0.5-0.6-1.2-0.8-1.8c-0.4-1.2-1-2.4-1.2-3.7  c-0.2-0.6-0.4-1.2-0.5-1.9c-0.1-0.6-0.2-1.2-0.3-1.8c-0.3-1.2-0.3-2.4-0.4-3.7c-0.1-1.2,0-2.5,0.1-3.7c0.2-1.2,0.3-2.4,0.6-3.5  c0.1-0.6,0.3-1.1,0.4-1.7l0.1-0.8l0.3-0.8c1.5-4.3,3.8-8,6.5-11c0.8-0.8,1.4-1.5,2.1-2.1c0.9-0.9,1.4-1.3,2.2-1.8  c1.4-1.2,2.9-2,4.3-2.8c2.8-1.5,5.5-2.3,7.7-2.8s4-0.7,5.2-0.6c0.6-0.1,1.1,0,1.4,0s0.4,0,0.4,0h0.1c2.7,0.1,5-2.2,5-5  c0.1-2.7-2.2-5-5-5c-0.2,0-0.2,0-0.3,0c0,0-0.2,0.1-0.6,0.1c-0.4,0-1,0-1.8,0.1c-1.6,0.1-4,0.4-6.9,1.2c-2.9,0.8-6.4,2-9.9,4.1  c-1.8,1-3.6,2.3-5.4,3.8C26,21.4,25,22.2,24.4,23c-0.2,0.2-0.4,0.4-0.6,0.6c-0.2,0.2-0.5,0.4-0.6,0.7c-0.5,0.4-0.8,0.9-1.3,1.4  c-3.2,3.9-5.9,8.8-7.5,14.3l-0.3,1l-0.2,1.1c-0.1,0.7-0.3,1.4-0.4,2.1c-0.3,1.5-0.4,2.9-0.5,4.5c0,1.5-0.1,3,0.1,4.5  c0.2,1.5,0.2,3,0.6,4.6c0.1,0.7,0.3,1.5,0.4,2.3c0.2,0.8,0.5,1.5,0.7,2.3c0.4,1.6,1.1,3,1.7,4.4c0.3,0.7,0.7,1.4,1.1,2.1  c0.4,0.8,0.8,1.4,1.2,2.1c0.5,0.7,0.9,1.4,1.4,2s0.9,1.3,1.5,1.9c1.1,1.3,2.2,2.7,3.3,3.5l1.7,1.6c0,0,0.1,0.1,0.1,0.1c0,0,0,0,0,0  c0,0,0,0,0,0l0.1,0.1l0.1,0.1h0.2l0.5,0.4l1,0.7c0.4,0.2,0.6,0.5,1,0.7l1.1,0.6c0.8,0.4,1.4,0.9,2.1,1.2c1.4,0.7,2.9,1.5,4.4,2  c1.5,0.7,3.1,1,4.6,1.5c1.5,0.3,3.1,0.7,4.7,0.8c1.6,0.2,3.1,0.2,4.7,0.2c0.8,0,1.6-0.1,2.4-0.1l1.2-0.1l1.1-0.1  c3.1-0.4,6.1-1.3,8.9-2.4c0.8-0.3,1.4-0.6,2.1-0.9s1.3-0.7,2-1.1c1.3-0.7,2.6-1.7,3.7-2.5c0.5-0.4,1-0.9,1.6-1.3l0.8-0.6l0.2-0.2  c0,0,0.1-0.1,0.1-0.1c0.1-0.1,0,0,0,0v0.1l0.1-0.1l0.4-0.4c0.5-0.5,1-1,1.5-1.5c0.3-0.3,0.5-0.5,0.8-0.8l0.7-0.8  c0.9-1.1,1.8-2.2,2.5-3.3c0.4-0.6,0.7-1.1,1.1-1.7c0.3-0.7,0.6-1.2,0.9-1.8c2.4-4.9,3.5-9.8,3.7-14.4C87.3,49.7,86.6,45.5,85.5,42z"></path></svg>
                </div>
            </div>  
        </div>
    </div>    
    <?php 
        else: 
        $product = wc_get_product($pid);
        $option = unserialize(get_post_meta($pid, '_nbdesigner_upload', true));
    ?>
    <script type="text/javascript">
        var nbd_allow_type = "<?php echo $option['allow_type']; ?>",
        nbd_disallow_type = "<?php echo $option['disallow_type']; ?>",
        nbd_number = parseInt(<?php echo $option['number']; ?>),
        nbd_minsize = parseInt(<?php echo $option['minsize']; ?>),
        nbd_maxsize = parseInt(<?php echo $option['maxsize']; ?>);
    </script>
    <div class="nbd-m-custom-design-wrap" id="nbd-m-custom-design-wrap">
        <div id="nbd_processing">
            <div class="atom-loading">
                <div class="loading__ring">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M85.5,42c-0.2-0.8-0.5-1.7-0.8-2.5c-0.3-0.9-0.7-1.6-1-2.3c-0.3-0.7-0.6-1.3-1-1.9c0.3,0.5,0.5,1.1,0.8,1.7  c0.2,0.7,0.6,1.5,0.8,2.3s0.5,1.7,0.8,2.5c0.8,3.5,1.3,7.5,0.8,12c-0.4,4.3-1.8,9-4.2,13.4c-2.4,4.2-5.9,8.2-10.5,11.2  c-1.1,0.7-2.2,1.5-3.4,2c-0.5,0.2-1.2,0.6-1.8,0.8s-1.3,0.5-1.9,0.8c-2.6,1-5.3,1.7-8.1,1.8l-1.1,0.1L53.8,84c-0.7,0-1.4,0-2.1,0  c-1.4-0.1-2.9-0.1-4.2-0.5c-1.4-0.1-2.8-0.6-4.1-0.8c-1.4-0.5-2.7-0.9-3.9-1.5c-1.2-0.6-2.4-1.2-3.7-1.9c-0.6-0.3-1.2-0.7-1.7-1.1  l-0.8-0.6c-0.3-0.1-0.6-0.4-0.8-0.6l-0.8-0.6L31.3,76l-0.2-0.2L31,75.7l-0.1-0.1l0,0l-1.5-1.5c-1.2-1-1.9-2.1-2.7-3.1  c-0.4-0.4-0.7-1.1-1.1-1.7l-1.1-1.7c-0.3-0.6-0.6-1.2-0.9-1.8c-0.2-0.5-0.6-1.2-0.8-1.8c-0.4-1.2-1-2.4-1.2-3.7  c-0.2-0.6-0.4-1.2-0.5-1.9c-0.1-0.6-0.2-1.2-0.3-1.8c-0.3-1.2-0.3-2.4-0.4-3.7c-0.1-1.2,0-2.5,0.1-3.7c0.2-1.2,0.3-2.4,0.6-3.5  c0.1-0.6,0.3-1.1,0.4-1.7l0.1-0.8l0.3-0.8c1.5-4.3,3.8-8,6.5-11c0.8-0.8,1.4-1.5,2.1-2.1c0.9-0.9,1.4-1.3,2.2-1.8  c1.4-1.2,2.9-2,4.3-2.8c2.8-1.5,5.5-2.3,7.7-2.8s4-0.7,5.2-0.6c0.6-0.1,1.1,0,1.4,0s0.4,0,0.4,0h0.1c2.7,0.1,5-2.2,5-5  c0.1-2.7-2.2-5-5-5c-0.2,0-0.2,0-0.3,0c0,0-0.2,0.1-0.6,0.1c-0.4,0-1,0-1.8,0.1c-1.6,0.1-4,0.4-6.9,1.2c-2.9,0.8-6.4,2-9.9,4.1  c-1.8,1-3.6,2.3-5.4,3.8C26,21.4,25,22.2,24.4,23c-0.2,0.2-0.4,0.4-0.6,0.6c-0.2,0.2-0.5,0.4-0.6,0.7c-0.5,0.4-0.8,0.9-1.3,1.4  c-3.2,3.9-5.9,8.8-7.5,14.3l-0.3,1l-0.2,1.1c-0.1,0.7-0.3,1.4-0.4,2.1c-0.3,1.5-0.4,2.9-0.5,4.5c0,1.5-0.1,3,0.1,4.5  c0.2,1.5,0.2,3,0.6,4.6c0.1,0.7,0.3,1.5,0.4,2.3c0.2,0.8,0.5,1.5,0.7,2.3c0.4,1.6,1.1,3,1.7,4.4c0.3,0.7,0.7,1.4,1.1,2.1  c0.4,0.8,0.8,1.4,1.2,2.1c0.5,0.7,0.9,1.4,1.4,2s0.9,1.3,1.5,1.9c1.1,1.3,2.2,2.7,3.3,3.5l1.7,1.6c0,0,0.1,0.1,0.1,0.1c0,0,0,0,0,0  c0,0,0,0,0,0l0.1,0.1l0.1,0.1h0.2l0.5,0.4l1,0.7c0.4,0.2,0.6,0.5,1,0.7l1.1,0.6c0.8,0.4,1.4,0.9,2.1,1.2c1.4,0.7,2.9,1.5,4.4,2  c1.5,0.7,3.1,1,4.6,1.5c1.5,0.3,3.1,0.7,4.7,0.8c1.6,0.2,3.1,0.2,4.7,0.2c0.8,0,1.6-0.1,2.4-0.1l1.2-0.1l1.1-0.1  c3.1-0.4,6.1-1.3,8.9-2.4c0.8-0.3,1.4-0.6,2.1-0.9s1.3-0.7,2-1.1c1.3-0.7,2.6-1.7,3.7-2.5c0.5-0.4,1-0.9,1.6-1.3l0.8-0.6l0.2-0.2  c0,0,0.1-0.1,0.1-0.1c0.1-0.1,0,0,0,0v0.1l0.1-0.1l0.4-0.4c0.5-0.5,1-1,1.5-1.5c0.3-0.3,0.5-0.5,0.8-0.8l0.7-0.8  c0.9-1.1,1.8-2.2,2.5-3.3c0.4-0.6,0.7-1.1,1.1-1.7c0.3-0.7,0.6-1.2,0.9-1.8c2.4-4.9,3.5-9.8,3.7-14.4C87.3,49.7,86.6,45.5,85.5,42z"></path></svg>
                </div>
                <div class="loading__ring">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" x="0px" y="0px" viewBox="0 0 100 100" style="enable-background:new 0 0 100 100;" xml:space="preserve"><path d="M85.5,42c-0.2-0.8-0.5-1.7-0.8-2.5c-0.3-0.9-0.7-1.6-1-2.3c-0.3-0.7-0.6-1.3-1-1.9c0.3,0.5,0.5,1.1,0.8,1.7  c0.2,0.7,0.6,1.5,0.8,2.3s0.5,1.7,0.8,2.5c0.8,3.5,1.3,7.5,0.8,12c-0.4,4.3-1.8,9-4.2,13.4c-2.4,4.2-5.9,8.2-10.5,11.2  c-1.1,0.7-2.2,1.5-3.4,2c-0.5,0.2-1.2,0.6-1.8,0.8s-1.3,0.5-1.9,0.8c-2.6,1-5.3,1.7-8.1,1.8l-1.1,0.1L53.8,84c-0.7,0-1.4,0-2.1,0  c-1.4-0.1-2.9-0.1-4.2-0.5c-1.4-0.1-2.8-0.6-4.1-0.8c-1.4-0.5-2.7-0.9-3.9-1.5c-1.2-0.6-2.4-1.2-3.7-1.9c-0.6-0.3-1.2-0.7-1.7-1.1  l-0.8-0.6c-0.3-0.1-0.6-0.4-0.8-0.6l-0.8-0.6L31.3,76l-0.2-0.2L31,75.7l-0.1-0.1l0,0l-1.5-1.5c-1.2-1-1.9-2.1-2.7-3.1  c-0.4-0.4-0.7-1.1-1.1-1.7l-1.1-1.7c-0.3-0.6-0.6-1.2-0.9-1.8c-0.2-0.5-0.6-1.2-0.8-1.8c-0.4-1.2-1-2.4-1.2-3.7  c-0.2-0.6-0.4-1.2-0.5-1.9c-0.1-0.6-0.2-1.2-0.3-1.8c-0.3-1.2-0.3-2.4-0.4-3.7c-0.1-1.2,0-2.5,0.1-3.7c0.2-1.2,0.3-2.4,0.6-3.5  c0.1-0.6,0.3-1.1,0.4-1.7l0.1-0.8l0.3-0.8c1.5-4.3,3.8-8,6.5-11c0.8-0.8,1.4-1.5,2.1-2.1c0.9-0.9,1.4-1.3,2.2-1.8  c1.4-1.2,2.9-2,4.3-2.8c2.8-1.5,5.5-2.3,7.7-2.8s4-0.7,5.2-0.6c0.6-0.1,1.1,0,1.4,0s0.4,0,0.4,0h0.1c2.7,0.1,5-2.2,5-5  c0.1-2.7-2.2-5-5-5c-0.2,0-0.2,0-0.3,0c0,0-0.2,0.1-0.6,0.1c-0.4,0-1,0-1.8,0.1c-1.6,0.1-4,0.4-6.9,1.2c-2.9,0.8-6.4,2-9.9,4.1  c-1.8,1-3.6,2.3-5.4,3.8C26,21.4,25,22.2,24.4,23c-0.2,0.2-0.4,0.4-0.6,0.6c-0.2,0.2-0.5,0.4-0.6,0.7c-0.5,0.4-0.8,0.9-1.3,1.4  c-3.2,3.9-5.9,8.8-7.5,14.3l-0.3,1l-0.2,1.1c-0.1,0.7-0.3,1.4-0.4,2.1c-0.3,1.5-0.4,2.9-0.5,4.5c0,1.5-0.1,3,0.1,4.5  c0.2,1.5,0.2,3,0.6,4.6c0.1,0.7,0.3,1.5,0.4,2.3c0.2,0.8,0.5,1.5,0.7,2.3c0.4,1.6,1.1,3,1.7,4.4c0.3,0.7,0.7,1.4,1.1,2.1  c0.4,0.8,0.8,1.4,1.2,2.1c0.5,0.7,0.9,1.4,1.4,2s0.9,1.3,1.5,1.9c1.1,1.3,2.2,2.7,3.3,3.5l1.7,1.6c0,0,0.1,0.1,0.1,0.1c0,0,0,0,0,0  c0,0,0,0,0,0l0.1,0.1l0.1,0.1h0.2l0.5,0.4l1,0.7c0.4,0.2,0.6,0.5,1,0.7l1.1,0.6c0.8,0.4,1.4,0.9,2.1,1.2c1.4,0.7,2.9,1.5,4.4,2  c1.5,0.7,3.1,1,4.6,1.5c1.5,0.3,3.1,0.7,4.7,0.8c1.6,0.2,3.1,0.2,4.7,0.2c0.8,0,1.6-0.1,2.4-0.1l1.2-0.1l1.1-0.1  c3.1-0.4,6.1-1.3,8.9-2.4c0.8-0.3,1.4-0.6,2.1-0.9s1.3-0.7,2-1.1c1.3-0.7,2.6-1.7,3.7-2.5c0.5-0.4,1-0.9,1.6-1.3l0.8-0.6l0.2-0.2  c0,0,0.1-0.1,0.1-0.1c0.1-0.1,0,0,0,0v0.1l0.1-0.1l0.4-0.4c0.5-0.5,1-1,1.5-1.5c0.3-0.3,0.5-0.5,0.8-0.8l0.7-0.8  c0.9-1.1,1.8-2.2,2.5-3.3c0.4-0.6,0.7-1.1,1.1-1.7c0.3-0.7,0.6-1.2,0.9-1.8c2.4-4.9,3.5-9.8,3.7-14.4C87.3,49.7,86.6,45.5,85.5,42z"></path></svg>
                </div>
            </div>  
        </div>        
    </div>
    <div class="nbd-m-upload-design-wrap" id="nbd-m-upload-design-wrap">
        <div class="nbd-upload-inner">
            <h2><?php _e('Upload design', 'web-to-print-online-designer'); ?></h2>
            <div class="upload-zone">
                <input type="file" id="nbd-file-upload" autocomplete="off" class="inputfile"/> 
                <label for="nbd-file-upload">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="17" viewBox="0 0 20 17">
                        <path d="M10 0l-5.2 4.9h3.3v5.1h3.8v-5.1h3.3l-5.2-4.9zm9.3 11.5l-3.2-2.1h-2l3.4 2.6h-3.5c-.1 0-.2.1-.2.1l-.8 2.3h-6l-.8-2.2c-.1-.1-.1-.2-.2-.2h-3.6l3.4-2.6h-2l-3.2 2.1c-.4.3-.7 1-.6 1.5l.6 3.1c.1.5.7.9 1.2.9h16.3c.6 0 1.1-.4 1.3-.9l.6-3.1c.1-.5-.2-1.2-.7-1.5z"> 
                        </path>
                    </svg>
                    <span style="margin-bottom: 10px; margin-top: 10px;"><?php _e('Click or drop file here', 'web-to-print-online-designer'); ?></span>
                    <?php if($option['allow_type'] != ''): ?><span><small><?php _e('Allow extensions', 'web-to-print-online-designer'); ?>: <?php echo $option['allow_type']; ?></small></span><?php endif; ?>
                    <?php if($option['disallow_type'] != ''): ?><span><small><?php _e('Disallow extensions', 'web-to-print-online-designer'); ?>: <?php echo $option['disallow_type']; ?></small></span><?php endif; ?>
                    <span><small><?php _e('Min size', 'web-to-print-online-designer'); ?> <?php echo $option['minsize']; ?> MB</small></span>
                    <span><small><?php _e('Max size', 'web-to-print-online-designer'); ?> <?php echo $option['maxsize']; ?> MB</small></span>
                </label>
                <svg class="nbd-upload-loading" xmlns="http://www.w3.org/2000/svg" width="50px" height="50px" viewBox="0 0 50 50"><circle fill="none" opacity="0.05" stroke="#000000" stroke-width="3" cx="25" cy="25" r="20"/><g transform="translate(25,25) rotate(-90)"><circle  style="stroke:#48B0F7; fill:none; stroke-width: 3px; stroke-linecap: round" stroke-dasharray="110" stroke-dashoffset="0"  cx="0" cy="0" r="20"><animate attributeName="stroke-dashoffset" values="360;140" dur="2.2s" keyTimes="0;1" calcMode="spline" fill="freeze" keySplines="0.41,0.314,0.8,0.54" repeatCount="indefinite" begin="0"/><animateTransform attributeName="transform" type="rotate" values="0;274;360" keyTimes="0;0.74;1" calcMode="linear" dur="2.2s" repeatCount="indefinite" begin="0"/><animate attributeName="stroke" values="#10CFBD;#48B0F7;#ff0066;#48B0F7;#10CFBD" fill="freeze" dur="3s" begin="0" repeatCount="indefinite"/></circle></g></svg>
            </div>
            <div class="upload-design-preview"></div>
            <div class="submit-upload-design"><span onclick="hideDesignFrame()"><?php _e('Complete', 'web-to-print-online-designer'); ?></span></div>
            <p style="margin-top: 15px;margin-bottom: 0;color: #2a6496;cursor: pointer;" onclick="backtoOption()">← <?php _e('Back to option', 'web-to-print-online-designer'); ?></p>
        </div>
    </div> 
    <?php if( $_enable_upload && !$_enable_upload_without_design ): ?>
    <div class="nbd-popup-wrap" id="nbd__content__overlay">
        <div class="nbd__pop__content">
            <div class="nbd__pop__content_wrapper <?php if($show_button_use_our_template) echo 'nbd__pop_wide'; ?>">
                <div class="__content_wrapper">
                    <div class="content__header"><?php _e('How would you like to design your', 'web-to-print-online-designer'); ?> <b><?php echo $product->get_title(); ?></b></div>
                    <div class="content__content">
                        <div class="layout__item">
                            <div class="layout__item__inner" id="open_m-upload-design-wrap">
                                <div class="item__layout upload_design">
                                    <div class="tile__media-wrap">
                                        <div class="tile-action__image-wrap">
                                            <svg viewBox="0 0 49 30" width="100%" height="100%"><g stroke-width=".8" fill="none" fill-rule="evenodd"><path d="M39.793 8.384c.001-.053.004-.106.004-.16 0-2.56-2.061-4.637-4.603-4.637-1.226 0-2.339.485-3.164 1.273A13.124 13.124 0 0 0 22.726 1C16.135 1 10.672 5.86 9.673 12.217c-.055 0-.11-.004-.164-.004-4.7 0-8.509 3.838-8.509 8.572 0 4.59 3.58 8.336 8.08 8.56v.012h28.362c5.822 0 10.542-4.755 10.542-10.62 0-5.052-3.502-9.276-8.191-10.353z" stroke="#4B4F54" stroke-dasharray="2,2" fill="#FFF"></path><path d="M26.597 14.366l-2.054-2.022v11.691h-.749V12.343l-2.053 2.023-.53-.522 2.429-2.39.529-.522.53.522 2.427 2.39-.53.522zm-2.453-.974h.05l-.025-.024-.025.024z" stroke="#52575C" stroke-linejoin="round" fill="#52575C"></path></g></svg>                                        
                                        </div>
                                    </div>
                                    <div class="tile__text-wrap">
                                        <div class="tile__text-wrap-inner">
                                            <h3 class="h__block"><?php _e('Upload a full design', 'web-to-print-online-designer'); ?></h3>
                                            <ul>
                                                <li>- <?php _e('Have a complete design', 'web-to-print-online-designer'); ?></li>
                                                <li>- <?php _e('Have your own designer', 'web-to-print-online-designer'); ?></li>
                                            </ul>                                            
                                        </div>
                                        <svg class="tile--horizontal__chevron" viewBox="0 0 24 24" width="100%" height="100%"><path d="M10.5 18.5a1 1 0 0 1-.71-1.71l4.8-4.79-4.8-4.79A1 1 0 0 1 11.2 5.8l6.21 6.2-6.21 6.21a1 1 0 0 1-.7.29z"></path></svg>
                                    </div> 
                                </div>    
                            </div>
                        </div>
                        <div class="layout__item">
                            <div class="layout__item__inner" id="open_m-custom-design-wrap">
                                <div class="item__layout custom_design">
                                    <div class="tile__media-wrap">
                                        <div class="tile-action__image-wrap">
                                            <svg viewBox="0 0 49 39" width="100%" height="100%"><g fill="none" fill-rule="evenodd"><path stroke="#4B4F54" stroke-dasharray="4,4" fill="#FFF" d="M2.577 2.627h44.165v34.392H2.577z"></path><path stroke="#52575C" fill="#FFF" d="M1.225 1.232H4.38v3.253H1.225zM44.939 1.232h3.155v3.253h-3.155zM1.225 35.16H4.38v3.253H1.225zM44.939 35.16h3.155v3.253h-3.155z"></path><path d="M32.663 23.91a.459.459 0 0 1-.46-.473v-.917c-.582.901-1.486 1.454-2.711 1.454-1.87 0-3.294-1.517-3.294-3.618 0-2.102 1.424-3.619 3.294-3.619 1.225 0 2.129.553 2.711 1.454v-.916c0-.269.2-.474.46-.474s.46.205.46.474v6.162c0 .268-.2.474-.46.474zm-3.049-6.367c-1.532 0-2.497 1.17-2.497 2.813 0 1.643.965 2.812 2.497 2.812 1.578 0 2.59-1.28 2.59-2.812 0-1.533-1.012-2.813-2.59-2.813zm-4.658 6.368c-.23 0-.353-.143-.414-.3l-1.256-2.892h-5.27l-1.257 2.891c-.061.158-.184.3-.414.3-.275 0-.444-.19-.444-.426 0-.079.03-.158.061-.22l4.26-9.813c.091-.205.214-.316.429-.316.214 0 .337.11.429.316l4.259 9.812c.03.063.061.142.061.221 0 .237-.169.427-.444.427zm-4.305-9.45l-2.284 5.42h4.566l-2.282-5.42z" fill="#FFC600"></path><path fill="#CC9E00" d="M15.646 25.865h18.477v.5H15.646z"></path></g></svg>
                                        </div>
                                    </div>      
                                    <div class="tile__text-wrap">
                                        <div class="tile__text-wrap-inner">
                                            <h3 class="h__block"><?php _e('Design here online', 'web-to-print-online-designer'); ?></h3>
                                            <ul>
                                                <li>- <?php _e('Already have your concept', 'web-to-print-online-designer'); ?></li>
                                                <li>- <?php _e('Customise every detail', 'web-to-print-online-designer'); ?></li>
                                            </ul>                                            
                                        </div>
                                        <svg class="tile--horizontal__chevron" viewBox="0 0 24 24" width="100%" height="100%"><path d="M10.5 18.5a1 1 0 0 1-.71-1.71l4.8-4.79-4.8-4.79A1 1 0 0 1 11.2 5.8l6.21 6.2-6.21 6.21a1 1 0 0 1-.7.29z"></path></svg>
                                    </div> 
                                </div>    
                            </div>
                        </div>
                        <?php 
                            if($show_button_use_our_template): 
                            $template_gallery =  add_query_arg(array(
                                'pid'    =>  $pid
                                ),  getUrlPageNBD('gallery'));
                        ?>
                        <div class="layout__item">
                            <a style="display: block; width: 100%;color: unset;" href="<?php echo $template_gallery; ?>">
                            <div class="layout__item__inner">
                                <div class="item__layout use_our_design">
                                    <div class="tile__media-wrap">
                                        <div class="tile-action__image-wrap">
                                            <svg viewBox="0 0 46 32" width="100%" height="100%"><g fill="none" fill-rule="evenodd"><path d="M.173 31.059A.174.174 0 0 1 0 30.884V.174C0 .078.077 0 .173 0h45.654c.096 0 .173.078.173.174v30.71a.174.174 0 0 1-.173.175H.173z" fill="#FFF"></path><path d="M45.657 0H.343A.35.35 0 0 0 0 .356v31.288A.35.35 0 0 0 .343 32h45.314a.35.35 0 0 0 .343-.356V.356A.35.35 0 0 0 45.657 0zM.343 31.644h45.314V.356H.343v31.288z" fill="#7679B3"></path><path fill="#7679B3" d="M3.755 7.529h10.327v16H3.755zM18.776 10.353h21.591v-.941H18.776zM40.367 15.058H18.776v-.94h21.591zM18.776 18.824h21.591v-.942H18.776zM18.776 22.588h21.591v-.94H18.776z"></path><path d="M9.388 18.824c-.587 0-1.129-.313-1.488-.858a2.419 2.419 0 0 1-.39-1.335c0-.15.013-.3.04-.445.066-.484.375-1.287.919-2.383.402-.812.744-1.416.748-1.422.04-.069.084-.146.17-.146.087 0 .132.077.171.145.004.006.349.616.749 1.423.544 1.096.853 1.9.92 2.386.025.142.038.292.038.442 0 .486-.135.948-.39 1.335-.359.545-.9.858-1.487.858m.014-.274c.506 0 .974-.271 1.284-.746.22-.336.336-.738.336-1.16 0-.132-.01-.262-.033-.387-.06-.44-.34-1.168-.833-2.17-.37-.752-.68-1.305-.683-1.31l-.07-.121-.071.121c-.003.005-.314.558-.684 1.31-.493 1.002-.772 1.73-.832 2.166a2.232 2.232 0 0 0-.034.39c0 .424.116.825.336 1.16.31.476.779.747 1.284.747" fill="#FFF"></path></g></svg>
                                        </div>
                                    </div>      
                                    <div class="tile__text-wrap">
                                        <div class="tile__text-wrap-inner">
                                            <h3 class="h__block"><?php _e('Use our templates', 'web-to-print-online-designer'); ?></h3>
                                            <ul>
                                                <li>- <?php _e('Looking for inspiration', 'web-to-print-online-designer'); ?></li>
                                                <li>- <?php _e('Want simple customization', 'web-to-print-online-designer'); ?></li>
                                            </ul>                                            
                                        </div>
                                        <svg class="tile--horizontal__chevron" viewBox="0 0 24 24" width="100%" height="100%"><path d="M10.5 18.5a1 1 0 0 1-.71-1.71l4.8-4.79-4.8-4.79A1 1 0 0 1 11.2 5.8l6.21 6.2-6.21 6.21a1 1 0 0 1-.7.29z"></path></svg>
                                    </div> 
                                </div>    
                            </div>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php endif; ?>
    <span id="closeFrameDesign" class="nbdesigner_pp_close">&times;</span>
</div>
<script>
    var nbd_create_own_page = "<?php echo getUrlPageNBD('create') ?>";    
</script>