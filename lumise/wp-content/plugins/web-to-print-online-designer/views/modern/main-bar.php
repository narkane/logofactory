<?php
$custom_logo_id = get_theme_mod( 'custom_logo' );
$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );
$without_logo = false;
if(!isset($image['0'])){   
    $logo_option = nbdesigner_get_option('nbdesigner_editor_logo');
    $logo_url = wp_get_attachment_url( $logo_option );
    if(!$logo_url){
        $without_logo = true;
    }
}else{
    $logo_url = $image['0'];
}
?>
<div class="nbd-main-bar">
    <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>" class="logo <?php if($without_logo) echo ' logo-without-image'; ?>">
        <?php if(!$without_logo): ?>
        <img src="<?php echo $logo_url;?>" alt="online design">
        <?php else: ?>
        <?php _e('Home','web-to-print-online-designer'); ?>
        <?php endif; ?>
    </a>
    <i class="icon-nbd icon-nbd-menu menu-mobile"></i>
    <ul class="nbd-main-menu menu-left">
        <li class="menu-item item-edit">
            <span><?php _e('File','web-to-print-online-designer'); ?></span>
            <div class="sub-menu" data-pos="left">
                <ul>
                    <?php if( is_user_logged_in() ): ?>
                    <li ng-if="false" class="sub-menu-item flex space-between" ng-click="loadUserDesigns()">
                        <span><?php _e('Open My Logo','web-to-print-online-designer'); ?></span>
                    </li>
                    <li class="sub-menu-item flex space-between item-import-file" ng-click="loadMyDesign(null, false)">
                        <span><?php _e('Open My Design','web-to-print-online-designer'); ?></span>
                        <small>{{ 'M-O' | keyboardShortcut }}</small>
                    </li>
                    <?php endif; ?>
                    <li class="sub-menu-item flex space-between item-import-file" ng-click="loadMyDesign(null, true)">
                        <span><?php _e('My Design in Cart','web-to-print-online-designer'); ?></span>
                        <small>{{ 'M-S-O' | keyboardShortcut }}</small>
                    </li>                    
                    <li class="sub-menu-item flex space-between item-import-file" ng-click="importDesign()">
                        <span><?php _e('Import Design','web-to-print-online-designer'); ?></span>
                        <small>{{ 'M-S-I' | keyboardShortcut }}</small>
                    </li>
                    <li class="sub-menu-item flex space-between" ng-click="exportDesign()">
                        <span><?php _e('Export Design','web-to-print-online-designer'); ?></span>
                        <small>{{ 'M-S-E' | keyboardShortcut }}</small>
                    </li>
                    <?php if( $settings['allow_customer_download_design_in_editor'] == 'yes' && ( $settings['nbdesigner_download_design_in_editor_png'] == '1' || $settings['nbdesigner_download_design_in_editor_pdf'] == '1' || $settings['nbdesigner_download_design_in_editor_jpg'] == '1' || $settings['nbdesigner_download_design_in_editor_svg'] == '1' ) ): ?>
                    <li class="sub-menu-item flex space-between hover-menu" data-animate="bottom-to-top">
                        <span class="title-menu"><?php _e('Download','web-to-print-online-designer'); ?></span>
                        <i class="icon-nbd icon-nbd-arrow-drop-down rotate-90"></i>
                        <div class="hover-sub-menu-item">
                            <ul>
                                <?php if( $settings['nbdesigner_download_design_in_editor_png'] == '1' ): ?>
                                <li ng-click="saveDesign('png')"><span class="title-menu"><?php _e('PNG','web-to-print-online-designer'); ?></span></li>
                                <?php endif; ?>
                                <?php if( $settings['nbdesigner_download_design_in_editor_jpg'] == '1' ): ?>
                                <li ng-click="saveData('download-jpg')"><span class="title-menu"><?php _e('CMYK JPG','web-to-print-online-designer'); ?></span></li>
                                <?php endif; ?>
                                <?php if( $settings['nbdesigner_download_design_in_editor_svg'] == '1' ): ?>
                                <li ng-click="saveDesign('svg')"><span class="title-menu"><?php _e('SVG','web-to-print-online-designer'); ?></span></li>
                                <?php endif; ?>
                                <?php if( $settings['nbdesigner_download_design_in_editor_pdf'] == '1' ): ?>
                                <li ng-click="saveData('download-pdf')"><span class="title-menu"><?php _e('PDF','web-to-print-online-designer'); ?></span></li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </li>
                    <?php endif; ?>
                </ul>
            </div>
            <div id="nbd-overlay"></div>
        </li>
        <li class="menu-item item-edit">
            <span><?php _e('Edit','web-to-print-online-designer'); ?></span>
            <div class="sub-menu" data-pos="left">
                <ul>
                    <li class="sub-menu-item flex space-between" ng-click="clearAllStage()">
                        <span><?php _e('Clear all design','web-to-print-online-designer'); ?></span>
                        <small>{{ 'M-E' | keyboardShortcut }}</small>
                    </li>
                    <li ng-if="settings.nbdesigner_save_for_later == 'yes'" class="sub-menu-item flex space-between" ng-click="saveForLater()">
                        <span><?php _e('Save for later','web-to-print-online-designer'); ?></span>
                        <small>{{ 'M-S-S' | keyboardShortcut }}</small>
                    </li>  
                </ul>
            </div>
            <div id="nbd-overlay"></div>
        </li>
        <li class="menu-item item-view">
            <span><?php _e('View','web-to-print-online-designer'); ?></span>
            <ul class="sub-menu" data-pos="left">
                <li ng-show="!settings.is_mobile" class="sub-menu-item flex space-between" ng-click="toggleRuler()" ng-class="settings.showRuler ? 'active' : ''">
                    <span class="title-menu"><?php _e('Ruler','web-to-print-online-designer'); ?></span>
                    <small>{{ 'M-R' | keyboardShortcut }}</small>
                </li>
                <li class="sub-menu-item flex space-between" ng-click="settings.showGrid = !settings.showGrid" ng-class="settings.showGrid ? 'active' : ''">
                    <span class="title-menu"><?php _e('Show grid','web-to-print-online-designer'); ?></span>
                    <small>{{ 'S-G' | keyboardShortcut }}</small>
                </li>
                <li class="sub-menu-item flex space-between" ng-click="settings.bleedLine = !settings.bleedLine" ng-class="settings.bleedLine ? 'active' : ''">
                    <span class="title-menu"><?php _e('Show bleed line','web-to-print-online-designer'); ?></span>
                    <small>{{ 'M-L' | keyboardShortcut }}</small>
                </li>
                <li ng-show="!settings.is_mobile" class="sub-menu-item flex space-between" ng-click="settings.showDimensions = !settings.showDimensions" ng-class="settings.showDimensions ? 'active' : ''">
                    <span class="title-menu"><?php _e('Show dimensions','web-to-print-online-designer'); ?></span>
                </li>
                <li class="sub-menu-item flex space-between" ng-click="clearGuides()" ng-class="!(stages[currentStage].rulerLines.hors.length > 0 || stages[currentStage].rulerLines.vers.length > 0) ? 'nbd-disabled' : ''">
                    <span class="title-menu"><?php _e('Clear Guides','web-to-print-online-designer'); ?></span>
                    <small>{{ 'S-L' | keyboardShortcut }}</small>
                </li>
                <!--<li class="sub-menu-item flex space-between hover-menu" data-animate="bottom-to-top" ng-click="settings.snapMode.status = !settings.snapMode.status;" ng-class="settings.snapMode.status ? 'active' : ''">
                    <span class="title-menu"><?php _e('Snap to','web-to-print-online-designer'); ?></span>
                    <i class="icon-nbd icon-nbd-arrow-drop-down rotate-90" ng-show="settings.snapMode.status"></i>
                    <div class="hover-sub-menu-item" ng-show="settings.snapMode.status">
                        <ul>
                            <li ng-click="settings.snapMode.type = 'layer'; $event.stopPropagation();" ng-class="settings.snapMode.type == 'layer' ? 'active' : ''"><span class="title-menu"><?php _e('Layer','web-to-print-online-designer'); ?></span></li>
                            <li ng-click="settings.snapMode.type = 'bounding'; $event.stopPropagation();" ng-class="settings.snapMode.type == 'bounding' ? 'active' : ''"><span class="title-menu"><?php _e('Bounding','web-to-print-online-designer'); ?></span></li>
                            <li ng-click="settings.snapMode.type = 'grid'; $event.stopPropagation();" ng-class="settings.snapMode.type == 'grid' ? 'active' : ''"><span class="title-menu"><?php _e('Grid','web-to-print-online-designer'); ?></span></li>
                        </ul>
                    </div>
                </li>-->
		<li class="sub-menu-item flex space-between hover-menu" data-animate="bottom-to-top">
                    <span class="title-menu"><?php _e('Show warning','web-to-print-online-designer'); ?></span>
                    <i class="icon-nbd icon-nbd-arrow-drop-down rotate-90"></i>
                    <div class="hover-sub-menu-item">
                        <ul>
                            <li ng-click="settings.showWarning.oos = !settings.showWarning.oos" ng-class="settings.showWarning.oos ? 'active' : ''"><span class="title-menu"><?php _e('Out of stage','web-to-print-online-designer'); ?></span></li>
                            <li ng-click="settings.showWarning.ilr = !settings.showWarning.ilr" ng-class="settings.showWarning.ilr ? 'active' : ''"><span class="title-menu"><?php _e('Image low resolution','web-to-print-online-designer'); ?></span></li>
                        </ul>
                    </div>
                </li>
            </ul>
            <div id="nbd-overlay"></div>
        </li>
        <?php if( $show_nbo_option && ($settings['nbdesigner_display_product_option'] == '1' || wp_is_mobile() ) ): ?>
        <li class="menu-item item-nbo-options" ng-click="getPrintingOptions()">
            <span><?php _e('Options','web-to-print-online-designer'); ?></span>
        </li>
        <?php endif; ?> 
        <li class="menu-item tour_start" ng-if="!settings.is_mobile" ng-click="startTourGuide()">
            <span class="nbd-tooltip-hover-right" title="<?php _e('Quick Help','web-to-print-online-designer'); ?>">?</span>
        </li>
        <?php do_action('nbd_modern_extra_menu'); ?>
    </ul>
    <ul class="nbd-main-menu menu-center">
        <li class="menu-item undo-redo" ng-click="undo()" ng-class="stages[currentStage].states.isUndoable ? 'in' : 'nbd-disabled'">
            <i class="icon-nbd-baseline-undo" style="font-size: 24px"></i>
            <span style="font-size: 12px;"><?php _e('Undo','web-to-print-online-designer'); ?></span>
        </li>
        <li class="menu-item undo-redo" ng-click="redo()" ng-class="stages[currentStage].states.isRedoable ? 'in' : 'nbd-disabled'">
            <i class="icon-nbd-baseline-redo" style="font-size: 24px"></i>
            <span style="font-size: 12px;"><?php _e('Redo','web-to-print-online-designer'); ?></span>
        </li>
    </ul>
    <ul class="nbd-main-menu menu-right">
        <li class="menu-item item-title animated slideInDown animate700">
            <input type="text" name="title" class="title" placeholder="Title" ng-model="stages[currentStage].config.name"/>
        </li>
        <li ng-if="settings.nbdesigner_share_design == 'yes'" class="menu-item item-share nbd-show-popup-share animated slideInDown animate800" ng-click="saveData('share')"><i class="icon-nbd icon-nbd-share2"></i></li>
        <?php if( $task == 'create_template' ): ?>
        <li class="menu-item item-process animated slideInDown animate900" id="save-template" ng-click="loadTemplateCat()">
            <span><?php _e('Save Template','web-to-print-online-designer'); ?></span><i class="icon-nbd icon-nbd-arrow-upward rotate90"></i>
        </li>
        <?php else: ?>
        <li ng-class="printingOptionsAvailable ? '' : 'nbd-disabled'" class="menu-item item-process animated slideInDown animate900 save-data" data-overlay="overlay" ng-click="saveData()" data-tour="process" data-tour-priority="7">
            <span><?php _e('Process','web-to-print-online-designer'); ?></span><i class="icon-nbd icon-nbd-arrow-upward rotate90"></i>
        </li>
        <?php endif; ?> 
    </ul>
</div>