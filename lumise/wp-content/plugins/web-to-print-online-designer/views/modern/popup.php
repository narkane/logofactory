<?php include 'popup-contents/share.php';?>
<?php include 'popup-contents/webcam.php';?>
<div class="nbd-popup popup-fileType" data-animate="bottom-to-top">
    <div class="overlay-popup"></div>
    <div class="main-popup">
        <i class="icon-nbd icon-nbd-clear close-popup"></i>
        <div class="head"></div>
        <div class="body">
            <div class="main-body"></div>
        </div>
        <div class="footer"></div>
    </div>
</div>
<?php include 'popup-contents/hotkeys.php';?>
<?php include 'popup-contents/upload-terms.php';?>
<?php include 'popup-contents/confirm-delete-layers.php';?>
<?php if( $task == 'create_template' ) {include 'popup-contents/global-template-category.php';}; ?>
<?php if( $show_nbo_option && ($settings['nbdesigner_display_product_option'] == '1' || wp_is_mobile() ) ) include 'popup-contents/printing-options.php';?>
<?php include 'popup-contents/crop-image.php';?>
<?php include 'popup-contents/guidelines.php';?>
<?php include 'popup-contents/user-design.php';?>
<?php include 'popup-contents/my-templates.php';?>
<?php include 'popup-contents/my-cart-designs.php';?>
<?php do_action('nbd_modern_extra_popup');