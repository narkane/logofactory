<?php
/*
  Plugin Name: NBT Live Chat
  Plugin URI: http://abc.com/woocommerce-price-matrix
  Description: Change the default behavior of WooCommerce Cart page, making AJAX requests when quantity field changes
  Version: 1.1.0
  Author: Katori
  Author URI: https://cmsmart.net/wordpress-plugins/nbt-woocommerce-price-matrix
 */
define('NBT_LCHAT_PATH', plugin_dir_path( __FILE__ ));
define('NBT_LCHAT_URL', plugin_dir_url( __FILE__ ));

class NBT_Solutions_Live_Chat {
    static $plugin_id = 'live-chat';
    /**
     * Variable to hold the initialization state.
     *
     * @var  boolean
     */
    protected static $initialized = false;

    public static $types = array();
    
    /**
     * Initialize functions.
     *
     * @return  void
     */
    public static function initialize() {


        // Do nothing if pluggable functions already initialized.
        if ( self::$initialized ) {
            return;
        }

        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        if ( ! is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
            add_action( 'admin_notices', 'install_woocommerce_admin_notice' );
        }else{
   
            if(!is_admin()){
                require_once 'inc/frontend.php';
            }
            

            if( ! defined('PREFIX_NBT_SOL') && !class_exists('NBT_Plugins') ){
                require_once 'inc/plugins.php';
            }

        }
        // State that initialization completed.
        self::$initialized = true;
    }


}


if( ! function_exists( 'install_woocommerce_admin_notice' ) ) {
    function install_woocommerce_admin_notice() {
        ?>
        <div class="error">
            <p><?php _e( 'WooCommerce plugin is not activated. Please install and activate it to use for plugin <strong>NBT Order Upload</strong>.', 'yith-woocommerce-wishlist' ); ?></p>
        </div>
    <?php
    }
}

if( ! defined('PREFIX_NBT_SOL')){
    NBT_Solutions_Live_Chat::initialize();
}