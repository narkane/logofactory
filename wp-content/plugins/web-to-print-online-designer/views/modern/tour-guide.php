<?php if ( ! defined( 'ABSPATH' ) ) { exit;} ?>
<div class="tour-guide">
    <div class="nbd-bgTour bgTour-top"></div>
    <div class="nbd-bgTour bgTour-bottom"></div>
    <div class="nbd-bgTour bgTour-left"></div>
    <div class="nbd-bgTour bgTour-right"></div>
    <div class="nbd-tourStep nbd-show nbd-triangle" data-pos="left">
        <div class="main-tour">
            <i class="icon-nbd icon-nbd-clear tour-close js-tour-close" ng-click="processTourComponents('close')"></i>
            <div class="tour-padding">
                <ng-include src="tourGuide.steps[tourGuide.currentStep].template"></ng-include>
            </div>
            <div class="tour-footer">
                <div class="tour-pagination">
                    <div class="item-pag tour-prev" ng-click="prevTour()"><span ng-if="tourGuide.currentStep > 0" style="font-size: 12px;"><?php _e('Prev','web-to-print-online-designer'); ?></span></div>
                    <div class="item-pag tour-count"><span class="step-cur">{{tourGuide.currentStep + 1}}</span> <?php _e('of','web-to-print-online-designer'); ?> <span class="step-total">{{tourGuide.steps.length}}</span></div>
                    <div class="item-pag tour-next" ng-click="nextTour()"><span ng-if="tourGuide.currentStep < (tourGuide.steps.length - 1)" style="font-size: 12px;"><?php _e('Next','web-to-print-online-designer'); ?></span></div>
                </div>
            </div>
        </div>
    </div>
    <?php echo '<script type="text/ng-template" id="tour_guide.templates">'; ?>
        <div class="tour-title"><?php _e('Template','web-to-print-online-designer'); ?></div>
        <div class="tour-intro"><?php _e('Choose from our state of the art Ready-to-use relevant templates which are relevant to product you have chosen to design.','web-to-print-online-designer'); ?></div>
    <?php echo '</script>'; ?>
    <?php echo '<script type="text/ng-template" id="tour_guide.typos">'; ?>
        <div class="tour-title"><?php _e('Text','web-to-print-online-designer'); ?></div>
        <div class="tour-intro"><?php _e('Use our extensive font library and formatting options (Alignment, Colors, and Size) to use text in your design.'); ?></div>
    <?php echo '</script>'; ?>
    <?php echo '<script type="text/ng-template" id="tour_guide.layers">'; ?>
        <div class="tour-title"><?php _e('Layers','web-to-print-online-designer'); ?></div>
        <div class="tour-intro"><?php _e('A simple way to manage and drag to sort the order in layers.'); ?></div>
    <?php echo '</script>'; ?>
    <?php echo '<script type="text/ng-template" id="tour_guide.cliparts">'; ?>
        <div class="tour-title"><?php _e('Clip Art','web-to-print-online-designer'); ?></div>
        <div class="tour-intro"><?php _e('Choose from extensive library of ready-to-use relevant vector art (ClipArt) which you can use to create meaningful design. You can also search the vector art you are looking for by using given search box.','web-to-print-online-designer'); ?></div>
    <?php echo '</script>'; ?>
    <?php echo '<script type="text/ng-template" id="tour_guide.elements">'; ?>
        <div class="tour-title"><?php _e('Elements','web-to-print-online-designer'); ?></div>
        <div class="tour-intro"><?php _e('Choose from library of different shapes, icons which are useful to create your design; Color palette is also available to change color of shape, icon.','web-to-print-online-designer'); ?></div>
    <?php echo '</script>'; ?>
    <?php echo '<script type="text/ng-template" id="tour_guide.photos">'; ?>
        <div class="tour-title"><?php _e('Photos','web-to-print-online-designer'); ?></div>
        <div class="tour-intro"><?php _e('You can upload your photos and Artwork to be used in your design from here; please also through given instructions related to type if art file and size limitation.','web-to-print-online-designer'); ?></div>
    <?php echo '</script>'; ?>
    <?php echo '<script type="text/ng-template" id="tour_guide.process">'; ?>
        <div class="tour-title"><?php _e('Process','web-to-print-online-designer'); ?></div>
        <div class="tour-intro"><?php _e('Your design will be saved before continuing with other options or directly add to cart.'); ?></div>
    <?php echo '</script>'; ?>
</div>