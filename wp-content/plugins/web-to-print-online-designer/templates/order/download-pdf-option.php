<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
} ?>
<div class="nbd-pdf-options nbd-hide" style="display: none;">
    <div class="nbd-pdf-options-inner">
        <h3><?php _e('Option for PDF files', 'web-to-print-online-designer'); ?></h3>
        <div class="md-checkbox">
            <input id="nbd-show-bleed" type="checkbox">
            <label for="nbd-show-bleed" class=""><?php _e('Show crop marks and bleed', 'web-to-print-online-designer'); ?></label>
        </div>    
        <div class="md-checkbox">
            <input id="nbd-multi-file" type="checkbox">
            <label for="nbd-multi-file" class=""><?php _e('Save to multiple files', 'web-to-print-online-designer'); ?></label>
        </div>   
        <div>
            <a hef="javascript: void(0)" class="nbd-button" style="margin: 10px auto;" onclick="NBDESIGNERPRODUCT.change_nbd_download_pdf_type( this )"><?php _e('Select', 'web-to-print-online-designer'); ?></a>
        </div>
    </div>
</div>	
