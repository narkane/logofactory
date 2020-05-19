<div class="nbd-warning">
    <div class="item main-warning animated animate800" ng-class="settings.showWarning.oos && stages[currentStage].states.oos ? 'fadeInDown nbd-show' : 'fadeOutUp'">
        <span class="title-warning"><?php _e('Out Of Stage','web-to-print-online-designer'); ?></span>
        <i class="icon-nbd icon-nbd-clear close-popup close-warning" ng-click="settings.showWarning.oos = false"></i>
    </div>
    <div class="item main-warning animated animate800" ng-class="settings.showWarning.ilr && stages[currentStage].states.ilr ? 'fadeInDown nbd-show' : 'fadeOutUp'">
        <span class="title-warning"><?php _e('Image Low Resolution','web-to-print-online-designer'); ?></span>
        <i class="icon-nbd icon-nbd-clear close-popup close-warning" ng-click="settings.showWarning.ilr = false"></i>
    </div>    
</div>