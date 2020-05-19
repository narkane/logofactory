<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
if(!class_exists('NBD_ADMIN_PRINTING_OPTIONS')){
    class NBD_ADMIN_PRINTING_OPTIONS {
        protected static $instance;
        public function __construct() {
            //todo something
        }
	public static function instance() {
            if ( is_null( self::$instance ) ) {
                    self::$instance = new self();
            }
            return self::$instance;
	}        
        public function init(){
            if (is_admin()) {         
                $this->ajax();
            }
            add_action('nbd_menu', array($this, 'tab_menu'));   
            add_action('nbd_create_tables', array($this, 'create_options_table'));  
            add_action('admin_enqueue_scripts', array($this, 'admin_enqueue_scripts'), 30, 1);
            add_action('add_meta_boxes', array($this, 'add_meta_boxes'), 30);
            add_action('save_post', array($this, 'save_product_option'));
            
            // Alter the product thumbnail in order
            add_filter( 'woocommerce_admin_order_item_thumbnail', array( $this, 'admin_order_item_thumbnail' ), 50, 3 ); 
            //Hide some price option data in order
            add_filter( 'woocommerce_hidden_order_itemmeta', array($this, 'hidden_custom_order_item_metada'));
        }
        public function ajax(){
            $ajax_events = array(
                'nbd_download_option_image'   => true,
                'nbd_get_media_full_size_url'   => true
            );
            foreach ($ajax_events as $ajax_event => $nopriv) {
                add_action('wp_ajax_' . $ajax_event, array($this, $ajax_event));
                if ($nopriv) {
                    // NBDesigner AJAX can be used for frontend ajax requests
                    add_action('wp_ajax_nopriv_' . $ajax_event, array($this, $ajax_event));
                }
            }
        }
        public function nbd_get_media_full_size_url(){
            if ( !wp_verify_nonce($_POST['nonce'], 'save-design') && NBDESIGNER_ENABLE_NONCE ) {
                die('Security error');
            }
            $result = array(
                'flag'  =>  1,
                'images'  =>  array()
            );
            $images = json_decode( stripslashes( $_POST['images'] ), true );
            foreach( $images as $key => $image ){
                $result['images'][$key] = wp_get_attachment_url( $image );
            }
            echo json_encode($result);
            wp_die();
        }
        public function nbd_download_option_image(){
            if ( !wp_verify_nonce($_POST['nonce'], 'save-design') && NBDESIGNER_ENABLE_NONCE ) {
                die('Security error');
            }
            $result = array(
                'flag'  =>  1,
                'image'  =>  array()
            );
            $url = $_POST['image'];
            require_once(NBDESIGNER_PLUGIN_DIR.'includes/class.download.image.php');
            if( strpos( $url, get_site_url() ) > -1 ){
                $result['image'] = array(
                    'current_site'  => 1
                );
            }else{
                $download_remote_image = new Nbdesigner_Download_Image( $url, array() );
                $attachment_id = $download_remote_image->download();
                if( $attachment_id ){
                    $result['image'] = array(
                        'current_site'  => 0,
                        'id'  => $attachment_id
                    );
                }else{
                    $result['flag'] = 0;
                }
            }
            echo json_encode($result);
            wp_die();
        }
        public function hidden_custom_order_item_metada($order_items){
            $order_items[] = '_nbo_option_price';
            $order_items[] = '_nbo_field';
            $order_items[] = '_nbo_options';
            $order_items[] = '_nbo_original_price';
            return $order_items;
        }
        public function admin_order_item_thumbnail( $image = "", $item_id = "", $item = "" ){
            $order = nbd_get_order_object();
            $item_meta = function_exists( 'wc_get_order_item_meta' ) ? wc_get_order_item_meta( $item_id, '', FALSE ) : $order->get_item_meta( $item_id ); 
            if( isset($item_meta['_nbo_option_price']) ){
                $option_price = maybe_unserialize( $item_meta['_nbo_option_price'][0] );
                $size = 'shop_thumbnail';
                $dimensions = wc_get_image_size( $size );  
                if( isset($option_price['cart_image']) && $option_price['cart_image'] != '' ){
                    $image = '<img src="'.$option_price['cart_image']
                            . '" width="' . esc_attr( $dimensions['width'] )
                            . '" height="' . esc_attr( $dimensions['height'] )
                            . '" class="nbo-thumbnail woocommerce-placeholder wp-post-image" />';        
                }
            }
            return $image;
        }
        public function save_product_option($post_id){
            if (!isset($_POST['nbo_box_nonce']) || !wp_verify_nonce($_POST['nbo_box_nonce'], 'nbo_box')
                || !(current_user_can('administrator') || current_user_can('shop_manager'))) {
                return $post_id;
            }
            if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
                return $post_id;
            }
            if ('page' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } else {
                if (!current_user_can('edit_post', $post_id)) {
                    return $post_id;
                }
            }
            $enable = $_POST['_nbo_enable']; 
            update_post_meta($post_id, '_nbo_enable', $enable);
            if( isset($_POST['_nbdpb_enable']) ){
                $enable = $_POST['_nbdpb_enable']; 
                update_post_meta($post_id, '_nbdpb_enable', $enable);
            }
        }
        public function add_meta_boxes(){
            add_meta_box('nbo_print_option', __('Printing option', 'web-to-print-online-designer'), array($this, 'meta_box'), 'product', 'normal', 'high');
        }        
        public function meta_box(){
            $post_id = get_the_ID();
            $enable = get_post_meta($post_id, '_nbo_enable', true);
            $nbdpb_enable = get_post_meta($post_id, '_nbdpb_enable', true);
            $option_id = $this->get_product_option( $post_id );
            $option_id = $option_id ? $option_id : 0;
            $link_edit_option = add_query_arg(array(
                    'product_id' => $post_id, 
                    'action' => 'edit',
                    'paged' => 1,
                    'id' => $option_id
                ),
                admin_url('admin.php?page=nbd_printing_options'));
            ?>
            <div id="nbo-wraper">
                <?php wp_nonce_field('nbo_box', 'nbo_box_nonce'); ?>
                <div>
                    <p>
                        <input type="hidden" value="0" name="_nbo_enable"/>
                        <label style="width: 150px; display: inline-block; margin-right: 10px;" for="_nbo_enable"><?php _e('Enable Print option', 'web-to-print-online-designer'); ?></label>
                        <input type="checkbox" value="1" name="_nbo_enable" id="_nbo_enable" <?php checked($enable); ?> class="short" />
                    </p>
                    <p>
                        <a class="button" href="<?php echo $link_edit_option; ?>" target="_blank">
                            <?php if( $option_id != 0 ){ _e('Edit option', 'web-to-print-online-designer'); }else{ _e('Create option', 'web-to-print-online-designer'); }; ?>
                        </a>
                    </p>
                    <?php if( $option_id != 0 ): ?>
                    <p>
                        <input type="hidden" value="0" name="_nbdpb_enable"/>
                        <label style="width: 150px; display: inline-block; margin-right: 10px;" for="_nbdpb_enable"><?php _e('Enable Product builder', 'web-to-print-online-designer'); ?></label>
                        <input type="checkbox" value="1" name="_nbdpb_enable" id="_nbdpb_enable" <?php checked($nbdpb_enable); ?> class="short" />
                    </p>
                    <?php endif; ?>
                </div>
            </div>
            <?php
        }
        public function get_product_option($product_id){
            $option_id = get_transient( 'nbo_product_'.$product_id );
            if( false === $option_id ){
                global $wpdb;
                $sql = "SELECT id, priority, apply_for, product_ids, product_cats, date_from, date_to FROM {$wpdb->prefix}nbdesigner_options WHERE published = 1";
                $options = $wpdb->get_results($sql, 'ARRAY_A');
                if($options){
                    $_options = array();
                    foreach( $options as $option ){
                        $execute_option = true;
                        $from_date = false;
                        if( isset($option['date_from']) ){
                            $from_date = empty( $option['date_from'] ) ? false : strtotime( date_i18n( 'Y-m-d 00:00:00', strtotime( $option['date_from'] ), false ) );
                        }
                        $to_date = false;
                        if( isset($option['date_to']) ){
                            $to_date = empty( $option['date_to'] ) ? false : strtotime( date_i18n( 'Y-m-d 00:00:00', strtotime( $option['date_to'] ), false ) );
                        }
                        $now  = current_time( 'timestamp' );
			if ( $from_date && $to_date && !( $now >= $from_date && $now <= $to_date ) ) {
                            $execute_option = false;
			} elseif ( $from_date && !$to_date && !( $now >= $from_date ) ) {
                            $execute_option = false;
			} elseif ( $to_date && !$from_date && !( $now <= $to_date ) ) {
                            $execute_option = false;
			}
                        if( $execute_option ){
                            if( $option['apply_for'] == 'p' ){
                                $products = unserialize($option['product_ids']);
                                $execute_option = in_array($product_id, $products) ? true : false;
                            }else {
                                $categories = $option['product_cats'] ? unserialize($option['product_cats']) : array();
                                $product = wc_get_product($product_id);
                                $product_categories = $product->get_category_ids();
                                $intersect = array_intersect($product_categories, $categories);
                                $execute_option = ( count($intersect) > 0 ) ? true : false;
                            }
                        }
                        if( $execute_option ){
                            $_options[] = $option;
                        }
                    }
                    $_options = array_reverse( $_options );
                    $option_priority = 0;
                    foreach( $_options as $_option ){
                        if( $_option['priority'] > $option_priority ){
                            $option_priority = $_option['priority'];
                            $option_id = $_option['id'];
                        }
                    }
                    if( $option_id ){
                        set_transient( 'nbo_product_'.$product_id , $option_id );
                    }
                }
            } 
            return $option_id;
        }
        public function tab_menu(){
            if(current_user_can('manage_nbd_tool')){  
                $options_hook = add_submenu_page(
                    'nbdesigner', 'Printing Options', 'Printing Options', 'manage_nbd_tool', 'nbd_printing_options', array($this, 'printing_options')
                );             
                add_action( "load-$options_hook", array( $this, 'screen_option' ));
            }             
        }
        public function create_options_table(){
            global $wpdb;
            $collate = '';
            if ( $wpdb->has_cap( 'collation' ) ) {
                $collate = $wpdb->get_charset_collate();
            } 
            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            if (NBDESIGNER_VERSION != get_option("nbdesigner_version_plugin")) {        
                $tables =  "
CREATE TABLE {$wpdb->prefix}nbdesigner_options ( 
 id bigint(20) unsigned NOT NULL auto_increment,
 title text NOT NULL,
 priority  TINYINT(1) NOT NULL default 0, 
 published  TINYINT(1) NOT NULL default 1, 
 product_ids text NULL, 
 product_cats text NULL,  
 date_from TINYTEXT NULL,  
 date_to TINYTEXT NULL,  
 apply_for TINYTEXT NULL,  
 enabled_roles text NULL,  
 disabled_roles text NULL,  
 created datetime NOT NULL default '0000-00-00 00:00:00',
 modified datetime NOT NULL default '0000-00-00 00:00:00', 
 created_by BIGINT(20) NULL, 
 modified_by BIGINT(20) NULL,  
 fields longtext,
 builder text NULL,
 PRIMARY KEY  (id)
) $collate; 
                ";
                @dbDelta($tables);                
            }
        }
        public function admin_enqueue_scripts( $hook ){
            if( $hook == 'nbdesigner_page_nbd_printing_options' ){
                wp_register_style('nbd_options', NBDESIGNER_CSS_URL . 'admin-options.css', array('wp-color-picker'), NBDESIGNER_VERSION);        
                wp_register_script('nbd_options', NBDESIGNER_JS_URL . 'admin-options.js', array('jquery', 'jquery-ui-resizable', 'jquery-ui-draggable', 'jquery-ui-datepicker', 'jquery-ui-autocomplete', 'wp-color-picker', 'angularjs', 'wc-enhanced-select'), NBDESIGNER_VERSION);          
                wp_localize_script('nbd_options', 'nbd_options', array(
                    'nbd_options_lang' => nbd_option_i18n(),
                    'calendar_image'    =>  NBDESIGNER_PLUGIN_URL.'assets/images/calendar.png',
                    'search_products_nonce'    =>  wp_create_nonce( "search-products" ),
                ));   
                wp_enqueue_style(array('wp-jquery-ui-dialog', 'wp-color-picker', 'nbd_options'));
                wp_enqueue_script(array('wpdialogs', 'nbd_options'));   	
                $jquery_version = isset( $wp_scripts->registered['jquery-ui-core']->ver ) ? $wp_scripts->registered['jquery-ui-core']->ver : '1.9.2';
                wp_enqueue_style( 'jquery-ui-style', '//ajax.googleapis.com/ajax/libs/jqueryui/' . $jquery_version . '/themes/smoothness/jquery-ui.css' );
            }
        }
        public function printing_options(){ 
            if( isset( $_GET['action'] ) ){
                $paged = get_query_var('paged', 1);
                $message = array('content'  =>  '');
                if( $_GET['action'] == 'unpublish' ){
                    $this->unpublish_option( $_REQUEST['id'] );
                    wp_redirect(esc_url_raw(add_query_arg(array('paged' => $paged), admin_url('admin.php?page=nbd_printing_options'))));
                }else{
                    $id = (isset( $_REQUEST['id'] ) && absint($_REQUEST['id']) > 0 ) ? absint($_REQUEST['id']) : 0;
                    if( isset( $_POST['save'] ) ){
                        $result = $this->save_option();
                        if($result['status']){
                            $message = array(
                                'flag'  =>  'success',
                                'content'   =>  __('Option updated.', 'web-to-print-online-designer')
                            );
                            if( $id == 0 ){
                                $id = $result['id'];
                                wp_redirect(esc_url_raw(add_query_arg(array(
                                        'paged' => 1,
                                        'action'    =>  'edit',
                                        'id'    =>  $id
                                    ), admin_url('admin.php?page=nbd_printing_options'))));
                            }
                        }else{
                            $message = array(
                                'flag'  =>  'error',
                                'content'   =>  ''
                            );                
                        }
                    }
                    $_options = ($id > 0) ? $this->get_option($id) : false;//$_options = false;
                    if($_options){
                        $raw_options = unserialize($_options['fields']);
                        if( !isset($raw_options["fields"]) ){
                            $raw_options["fields"] = array();
                            //$raw_options["fields"][] = $this->default_field();
                        }
                        $options = $this->build_options( $raw_options );
                        $options['id'] = $_options['id'];
                        $options['title'] = $_options['title'];
                        $options['priority'] = $_options['priority'];
                        $options['published'] = $_options['published'];
                        $options['date_from'] = isset($_options['date_from']) ? $_options['date_from'] : '';
                        $options['date_to'] = isset($_options['date_to']) ? $_options['date_to'] : '';
                        $options['apply_for'] = isset($_options['apply_for']) ? $_options['apply_for'] : 'p';
                        $options['product_cats'] = isset($_options['product_cats']) ? ( !is_null(unserialize($_options['product_cats'])) ? unserialize($_options['product_cats']) : array()) : array();
                        $options['product_ids'] = isset($_options['product_ids']) ? ( !is_null(unserialize($_options['product_ids'])) ? unserialize($_options['product_ids']) : array()) : array();
                    }else{
                        $options = $this->build_options();
                        $options['id'] = 0;
                        $options['title'] = '';
                        $options['date_from'] = '';
                        $options['date_to'] = '';
                        $options['priority'] = 1;                     
                        $options['published'] = 1; 
                        $options['apply_for'] = 'p';                    
                        $options['product_cats'] = array();               
                        $options['product_ids'] = array();               
                    }
                    foreach ( $options["fields"] as $f_index => $field ){
                        if($field["conditional"]['enable'] == 'n'){
                            $options["fields"][$f_index]["conditional"]['depend'] = $this->build_config_conditional_depend();
                            $options["fields"][$f_index]["conditional"]['logic'] = $this->build_config_conditional_logic();
                            $options["fields"][$f_index]["conditional"]['show'] = $this->build_config_conditional_show();
                        }
                    }    
                    $default_field = $this->default_config_field();
                    $product_id = (isset($_GET['product_id']) && absint($_GET['product_id']) > 0) ? absint($_GET['product_id']) : 0;
                    if( $product_id ){
                        if( !$_options ){
                            $options['product_ids'] = array( 0 => $product_id);
                        }
                    }
                    include_once(NBDESIGNER_PLUGIN_DIR . 'views/options/edit-option.php');
                }
            }else{
                require_once NBDESIGNER_PLUGIN_DIR . 'includes/options/fields-list-table.php';
                $nbd_options = new NBD_Options_List_Table();              
                include_once(NBDESIGNER_PLUGIN_DIR . 'views/options/options-list-table.php');
            }
        }
        public function screen_option(){
            if( !isset($_GET['action']) ){
                $option = 'per_page';
                $args   = array(
                    'label'   => __('Printing Options', 'web-to-print-online-designer'),
                    'default' => 10,
                    'option'  => 'options_per_page'
                );
                add_screen_option( $option, $args );            
            }            
        }
        public function save_option(){
//            $nonce = esc_attr($_REQUEST['_wpnonce']);
//            if (!wp_verify_nonce($nonce, 'nbd_options_nonce')) {
//                wp_redirect( esc_url(admin_url('admin.php?page=nbd_printing_options')) );
//                exit;
//            }
            $id = absint($_REQUEST['id']);
            $modified_date = new DateTime();
            $arr = array(
                'title' =>  $_POST['title'],
                'published' =>  1,
                'priority' =>  $_POST['priority'],
                'date_from' =>  $_POST['date_from'],
                'date_to' =>  $_POST['date_to'],
                'apply_for' =>  $_POST['apply_for'],
                'product_cats' =>  isset($_POST['product_cats']) ? serialize($_POST['product_cats']) : serialize(array()),
                'product_ids' =>  isset($_POST['product_ids']) ? serialize($_POST['product_ids']) : serialize(array()),
                'modified'  => $modified_date->format('Y-m-d H:i:s'),
                'fields'    => serialize($_POST['options'])
            );
            $arr['fields'] = serialize( $this->validate_option($_POST['options']) );
            global $wpdb;
            $date = new DateTime();
            if( $id > 0 ){
                $arr['modified'] = $date->format('Y-m-d H:i:s');
                $arr['modified_by'] = wp_get_current_user()->ID;
                $result = $wpdb->update("{$wpdb->prefix}nbdesigner_options", $arr, array( 'id' => $id) );
            }else{
                $arr['created'] = $date->format('Y-m-d H:i:s');
                $arr['created_by'] = wp_get_current_user()->ID;
                $result = $wpdb->insert("{$wpdb->prefix}nbdesigner_options", $arr);
                $id = $result ?  $wpdb->insert_id : 0;
            }
            $this->clear_transients();
            return array(
                'status' =>  $result,
                'id'    =>  $id
            );
        }
        private function clear_transients(){
            global $wpdb;
            $sql = "DELETE FROM $wpdb->options WHERE option_name LIKE '_transient_nbo_product_%' OR option_name LIKE '_transient_timeout_nbo_product_%'";   
            $wpdb->query( $sql );
        }
        private function validate_option( $options ){
            if( $options['display_type'] == 2 ){
                if( !isset($options['pm_hoz']) ){
                    $options['pm_hoz'] = array();
                }
                if( !isset($options['pm_ver']) ){
                    $options['pm_ver'] = array();
                }                
            }else if( $options['display_type'] == 3 ){
                if( !isset($options['bulk_fields']) ){
                    $options['bulk_fields'] = array();
                }
            }else{
                $options['display_type'] = 1;
            }
            if( isset($options["fields"]) ){
                foreach ( $options["fields"] as $f_index => $field ){
                    $array_price_type = array('f', 'p', 'p+', 'c', 'cp' );
                    if( !in_array($field["general"]['price_type'], $array_price_type) ){
                        $options["fields"][$f_index]["general"]['price_type'] = $field["general"]['data_type'] == 'i' ? 'c' : 'f';
                    }
                    if( isset( $field["conditional"]['depend'] ) ){
                        foreach( $field["conditional"]['depend'] as $d_index => $depend ){
                            if( ($depend['operator'] == 'i' || $depend['operator'] == 'n') && $depend['val'] == '? string: ?' ){
                                unset($options["fields"][$f_index]["conditional"]['depend'][$d_index]);
                            }
                        }
                    }
                }
            }
            return $options;
        }
        public function get_option($id){
            global $wpdb;
            $sql = "SELECT * FROM {$wpdb->prefix}nbdesigner_options";
            $sql .= " WHERE id = " . esc_sql($id);
            $result = $wpdb->get_results($sql, 'ARRAY_A');
            return count($result[0]) ? $result[0] : false;
        }
        public function delete_option( $id ){
            global $wpdb;
            $sql = "DELETE FROM {$wpdb->prefix}nbdesigner_options";
            $sql .= " WHERE id = " . esc_sql($id);
            $result = $wpdb->query( $sql );
            if( $result ) $this->clear_transients();
        }
        public function unpublish_option( $id ){
            global $wpdb;
            $result = $wpdb->update($wpdb->prefix . 'nbdesigner_options', array(
                'published' => 0
            ), array( 'id' => esc_sql($id))); 
            if( $result ) $this->clear_transients();
        }
        public function build_options( $options = null ){
            if( is_null($options) ){
                $options = array(
                    'quantity_type' => 'r',
                    'quantity_discount_type' => 'p',
                    'quantity_min' => 1,
                    'quantity_max' => 100,
                    'quantity_step' => 1,
                    'quantity_enable' => 'n',
                    'quantity_breaks' => array(
                        array('val' =>  1, 'dis'    =>  '')
                    ),
                    'display_type'  =>  1,
                    'version'  =>  NBDESIGNER_NUMBER_VERSION,
                    'pm_hoz'  =>  array(),
                    'pm_ver'  =>  array(),
                    'bulk_fields'  =>  array(),
                    'fields'    => array(
                        0   =>  $this->default_field()                          
                    )
                );
            }
            if( !isset($options['version']) ){
                $options['quantity_enable'] = 'n';
            }
            $options['fields'] = $this->recursive_stripslashes($options['fields']);
            foreach( $options['fields'] as $f_key => $field ){
                $field = array_replace_recursive($this->default_field(), $field);
                foreach ($field as $tab =>  $data){
                    if( $tab != 'id' && $tab != 'nbd_type' && $tab != 'nbpb_type' ){
                        foreach ($data as $key => $f){
                            if( !in_array($key, array('component_icon', 'page_display', 'exclude_page', 'mesure', 'mesure_range', 'mesure_base_pages', 'min_width', 'max_width', 'step_width', 'default_width', 'min_height', 'max_height', 'step_height', 'default_height')) ){
                                $funcname = "build_config_".$tab.'_'.$key;
                                $options['fields'][$f_key][$tab][$key] = $this->$funcname($f);
                            }
                            if( $key == 'component_icon' ){
                                $options['fields'][$f_key][$tab]['component_icon_url'] = nbd_get_image_thumbnail( $f );
                            }
                        }
                    }
                    if( $tab == 'nbd_type' ){
                        $options['fields'][$f_key]['nbd_template'] = 'nbd.'.$data;
                        if( isset($options['fields'][$f_key]['general']['mesure'])){
                            if( !isset($options['fields'][$f_key]['general']['mesure_range']) ) $options['fields'][$f_key]['general']['mesure_range'] = array();
                            if( !isset($options['fields'][$f_key]['general']['mesure_base_pages']) ) $options['fields'][$f_key]['general']['mesure_base_pages'] = 'y';
                        }
                    }
                    if( $tab == 'nbpb_type' ){
                        $options['fields'][$f_key]['nbd_template'] = 'nbd.'.$data;
                    }
                }
            }
            if( isset($options['views']) ){
                foreach ($options['views'] as $vkey => $view){
                    $view['base'] = isset($view['base']) ? $view['base'] : 0;
                    $options['views'][$vkey]['base'] = $view['base'];
                    $options['views'][$vkey]['base_url'] = nbd_get_image_thumbnail( $view['base'] );
                }
            }
            return $options;
        }
        public function recursive_stripslashes( $fields ){
            $valid_fields = array();
            foreach($fields as $key => $field){
                if(is_array($field) ){
                    $valid_fields[$key] = $this->recursive_stripslashes($field);
                }else if(!is_null($field)){
                    $valid_fields[$key] = stripslashes($field);
                }
            }
            return $valid_fields;
        }
        public function default_field(){
            return array(
                'id'    =>  'f' . round(microtime(true) * 1000),
                'general' => array(
                    'title' =>  null,
                    'description' =>  null,
                    'data_type' =>  null,
                    'input_type' =>  null,
                    'input_option' =>  null,
                    'text_option' =>  null,
                    'upload_option' =>  null,
                    'enabled' =>  null,
                    'required' =>  null,
                    'price_type' =>  null,
                    'depend_quantity' =>  null,
                    'price' =>  null,
                    'price_breaks' =>  null,
                    'attributes' =>  null
                ),
                'conditional' => array(
                    'enable'    =>  'n',
                    'show'    =>  'y',
                    'logic'    =>  'a',
                    'depend'    =>  null
                ),
                'appearance' => array(
                    'display_type' =>  null,
                    'change_image_product' =>  null
                )                            
            ); 
        }
        public function default_config_field(){
            $field = $this->default_field();
            foreach ($field as $tab =>  $data){
                if( $tab != 'id' ){
                    foreach ($data as $key => $f){
                        $funcname = "build_config_".$tab.'_'.$key;
                        $field[$tab][$key] = $this->$funcname($f);                     
                    }
                }
            }
            return $field;
        }
        public function build_config_general_title( $value = null ){
            if (is_null($value)) $value = __( 'Option name', 'web-to-print-online-designer');
            return array(
                'title' => __( 'Option name', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type'  => 'text'              
            );
        }
        public function build_config_general_description( $value = null ){
            if (is_null($value)) $value = __( 'Option description', 'web-to-print-online-designer');
            return array(
                'title' => __( 'Description', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type'  => 'textarea'              
            );            
        }
        public function build_config_general_data_type( $value = null ){
            if (is_null($value)) $value = 'm';
            return array(
                'title' => __( 'Data type', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type' 		=> 'dropdown',
                'options' =>    array(
                    array(
                        'key'   =>  'i',
                        'text'   =>  __( 'Custom input', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'm',
                        'text'   =>  __( 'Multiple options', 'web-to-print-online-designer')
                    )
                )               
            );            
        } 
        public function build_config_general_input_type( $value = null ){
            if (is_null($value)) $value = 't';
            return array(
                'title' => __( 'Input type', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type' 		=> 'dropdown',
                'depend'    =>  array(
                    array(
                        'field' =>  'data_type',
                        'operator' =>  '=',
                        'value' =>  'i'
                    )                    
                ),
                'options' =>    array(
                    array(
                        'key'   =>  't',
                        'text'   =>  __( 'Text', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'n',
                        'text'   =>  __( 'Number', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'r',
                        'text'   =>  __( 'Number range', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'u',
                        'text'   =>  __( 'Upload', 'web-to-print-online-designer')
                    )
                )               
            );            
        }
        public function build_config_general_input_option( $value = null ){
            if (is_null($value)){$value = array(
                'min'   =>  1,
                'max'   =>  100,
                'step'   =>  1
            );}
            return array(
                'title' => __( 'Input option', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type' 		=> 'table',
                'depend'    =>  array(
                    array(
                        'field' =>  'data_type',
                        'operator' =>  '=',
                        'value' =>  'i'
                    ), 
                    array(
                        'field' =>  'input_type',
                        'operator' =>  '#',
                        'value' =>  't'
                    ),
                    array(
                        'field' =>  'input_type',
                        'operator' =>  '#',
                        'value' =>  'u'
                    )
                )              
            );            
        }
        public function build_config_general_text_option( $value = null ){
            if (is_null($value)){$value = array(
                'min'   =>  0,
                'max'   =>  999
            );}
            return array(
                'title' => __( 'Text input option', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type' 		=> 'table',
                'depend'    =>  array(
                    array(
                        'field' =>  'data_type',
                        'operator' =>  '=',
                        'value' =>  'i'
                    ), 
                    array(
                        'field' =>  'input_type',
                        'operator' =>  '=',
                        'value' =>  't'
                    )                    
                )              
            );            
        }
        public function build_config_general_upload_option( $value = null ){
            if (is_null($value)){$value = array(
                'min_size'   =>  0,
                'max_size'   =>  nbd_get_max_upload_default(),
                'allow_type'   =>  'png,jpg,jpeg'
            );}
            return array(
                'title' => __( 'Upload file option', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type' 		=> 'table',
                'depend'    =>  array(
                    array(
                        'field' =>  'data_type',
                        'operator' =>  '=',
                        'value' =>  'i'
                    ), 
                    array(
                        'field' =>  'input_type',
                        'operator' =>  '=',
                        'value' =>  'u'
                    )                    
                )              
            );            
        }
        public function build_config_general_enabled( $value = null ){
            if (is_null($value)) $value = 'y';
            return array(
                'title' => __( 'Enabled', 'web-to-print-online-designer'),
                'description'   =>  'Choose whether the option is enabled or not.',
                'value'	=> $value,
                'type' 		=> 'dropdown',
                'options' =>    array(
                    array(
                        'key'   =>  'y',
                        'text'   =>  __( 'Yes', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'n',
                        'text'   =>  __( 'No', 'web-to-print-online-designer')
                    )
                )               
            );            
        }
        public function build_config_general_required( $value = null ){
            if (is_null($value)) $value = 'n';
            return array(
                'title' => __( 'Required', 'web-to-print-online-designer'),
                'description'   =>  __( 'Choose whether the option is required or not.' ),
                'value'	=> $value,
                'type' 		=> 'dropdown',
                'options' =>    array(
                    array(
                        'key'   =>  'y',
                        'text'   =>  __( 'Yes', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'n',
                        'text'   =>  __( 'No', 'web-to-print-online-designer')
                    )
                )               
            );            
        } 
        public function build_config_general_price_type( $value = null ){
            if (is_null($value)) $value = 'f';
            return array(
                'title' => __( 'Price type', 'web-to-print-online-designer'),
                'description'   =>  __( 'Here you can choose how the price is calculated. Depending on the field there various types you can choose.' ),
                'value'	=> $value,
                'type' 		=> 'dropdown',
                'options' =>    array(
                    array(
                        'key'   =>  'f',
                        'text'   =>  __( 'Fixed amount', 'web-to-print-online-designer')                      
                    ),
                    array(
                        'key'   =>  'p',
                        'text'   =>  __( 'Percent of the original price', 'web-to-print-online-designer')                     
                    ),
                    array(
                        'key'   =>  'p+',
                        'text'   =>  __( 'Percent of the original price + options', 'web-to-print-online-designer')                       
                    ),
                    array(
                        'key'   =>  'c',
                        'text'   =>  __( 'Current value * price', 'web-to-print-online-designer'),
                        'depend'    => array(
                            array(
                                'field' =>  'data_type',
                                'operator' =>  '=',
                                'value' =>  'i'
                            ),
                            array(
                                'field' =>  'input_type',
                                'operator' =>  '#',
                                'value' =>  'u'
                            ),
                            array(
                                'field' =>  'input_type',
                                'operator' =>  '#',
                                'value' =>  't'
                            )
                        )
                    ),
                    array(
                        'key'   =>  'cp',
                        'text'   =>  __( 'Price per char', 'web-to-print-online-designer'),
                        'depend'    => array(
                            array(
                                'field' =>  'data_type',
                                'operator' =>  '=',
                                'value' =>  'i'
                            ),
                            array(
                                'field' =>  'input_type',
                                'operator' =>  '=',
                                'value' =>  't'
                            )
                        )
                    )
                )               
            );            
        }
        public function build_config_general_depend_quantity( $value = null ){
            if (is_null($value)) $value = 'n';
            return array(
                'title' => __( 'Depend quantity breaks', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type' 		=> 'dropdown',
                'options' =>    array(
                    array(
                        'key'   =>  'y',
                        'text'   =>  __( 'Yes', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'n',
                        'text'   =>  __( 'No', 'web-to-print-online-designer')
                    )
                )               
            );            
        }    
        public function build_config_general_price( $value = null ){
            if (is_null($value)) $value = '';
            return array(
                'title' => __( 'Additional Price', 'web-to-print-online-designer'),
                'description'   =>  __( 'Enter the price for this field or leave it blank for no price.' ),
                'value'	=> $value,
                'depend'    =>  array(
                    array(
                        'field' =>  'depend_quantity',
                        'operator' =>  '#',
                        'value' =>  'y'
                    ),
                    array(
                        'field' =>  'data_type',
                        'operator' =>  '=',
                        'value' =>  'i'
                    )                                 
                ),                                        
                'type'  => 'number'                
            );
        }
        public function build_config_general_price_breaks( $value = null ){
            if (is_null($value)) $value = array();
            return array(  
                'title' => __( 'Price depend quantity breaks', 'web-to-print-online-designer'),
                'depend'    =>  array(
                    array(
                        'field' =>  'depend_quantity',
                        'operator' =>  '=',
                        'value' =>  'y'
                    ),
                    array(
                        'field' =>  'data_type',
                        'operator' =>  '=',
                        'value' =>  'i'
                    )                                    
                ),
                'description'   =>  '',
                'value'	=> $value,
                'type'  => 'single_quantity_depend'                  
            );
        }     
        public function build_config_general_attributes( $attributes = null ){
            if (is_null($attributes)){ $options = array(
                    0 => array(
                        'name'  => __( 'Attribute name', 'web-to-print-online-designer'),
                        'des'  => '',
                        'price'	=> array(),
                        'selected'  =>  0,
                        'enable_subattr'  =>  0,
                        'preview_type'  =>  'i',
                        'image' =>  0,
                        'image_url' =>  '',
                        'product_image' =>  0,
                        'product_image_url' =>  '',
                        'color' =>  '#ffffff',
                        'sub_attributes'    => array(),
                        'sattr_display_type'    =>  's'
                    )
                );
            } else {
                $options = $attributes['options'];
            };
            foreach( $options as $key => $option){
                $options[$key]['enable_subattr'] = isset( $options[$key]['enable_subattr'] ) ? $options[$key]['enable_subattr'] : 0;
                $options[$key]['sub_attributes'] = isset( $options[$key]['sub_attributes'] ) ? $options[$key]['sub_attributes'] : array();
                $options[$key]['sattr_display_type'] = isset( $options[$key]['sattr_display_type'] ) ? $options[$key]['sattr_display_type'] : 's';
                $options[$key]['image_url'] = nbd_get_image_thumbnail( $option['image'] );
                if( isset($options[$key]['product_image']) ){
                    $options[$key]['product_image_url'] = nbd_get_image_thumbnail( $option['product_image'] );
                }
                if( isset($attributes['bg_type']) ){
                    if( $attributes['bg_type'] == 'i' ){
                        foreach( $option['bg_image'] as $k => $bg ){
                            $options[$key]['bg_image_url'][$k] = nbd_get_image_thumbnail( $bg );
                        }
                    }else{
                        $options[$key]['bg_image'] = array();
                        $options[$key]['bg_image_url'] = array();
                    }
                }
                if( isset($option['enable_subattr']) ){
                    foreach( $options[$key]['sub_attributes'] as $sak => $sa ){
                        $options[$key]['sub_attributes'][$sak]['image_url'] = nbd_get_image_thumbnail( $sa['image'] );
                    }
                }
            }
            $same_size = isset($attributes['same_size']) ? $attributes['same_size'] : 'y';
            $bg_type = isset($attributes['bg_type']) ? $attributes['bg_type'] : 'i';
            $number_of_sides = isset($attributes['number_of_sides']) ? $attributes['number_of_sides'] : 4;
            return array(  
                'title' => __( 'Attributes', 'web-to-print-online-designer'),
                'description'   =>  __( 'Attributes let you define extra product data, such as size or color.'),                                     
                'type' 		=> 'attributes',
                'same_size' =>  $same_size,
                'bg_type' =>  $bg_type,
                'number_of_sides' =>  $number_of_sides,
                'depend'    =>  array(
                    array(
                        'field' =>  'data_type',
                        'operator' =>  '=',
                        'value' =>  'm'                                        
                    )
                ), 
                'options'   => $options 
            );
        }
        public function build_config_general_pb_config($configs){
            foreach( $configs as $key => $o_config){
                foreach( $o_config as $skey => $so_config){
                    foreach( $so_config['views'] as $vkey => $view){
                        $configs[$key][$skey]['views'][$vkey]['display'] = (isset($view['display']) && $view['display'] == 'on') ? true : false;
                        $configs[$key][$skey]['views'][$vkey]['image_url'] = nbd_get_image_thumbnail( $view['image'] );
                    }
                }
            }
            return $configs;
        }
        public function build_config_general_nbpb_text_configs($configs){
			if( !isset($configs['views']) ) $configs['views'] = array();
            foreach( $configs['views'] as $key => $view){
                $configs['views'][$key]['display'] = (isset($view['display']) && $view['display'] == 'on') ? true : false;
            }
            return $configs;
        }
        public function build_config_general_nbpb_image_configs($configs){
			if( !isset($configs['views']) ) $configs['views'] = array();
            foreach( $configs['views'] as $key => $view){
                $configs['views'][$key]['display'] = (isset($view['display']) && $view['display'] == 'on') ? true : false;
            }
            return $configs;
        }
        public function build_config_appearance_display_type( $value = null ){
            if (is_null($value)) $value = 'd';
            return array(  
                'title' => __( 'Display type', 'web-to-print-online-designer'),
                'description'   =>  '',
                'value'	=> $value,
                'type' 		=> 'dropdown',
                'options' =>    array(
                    array(
                        'key'   =>  'd',
                        'text'   =>  __( 'Dropdown', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'r',
                        'text'   =>  __( 'Radio button', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  's',
                        'text'   =>  __( 'Swatch', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'l',
                        'text'   =>  __( 'Label', 'web-to-print-online-designer')
                    )                    
                ) 			
            );
        }
        public function build_config_appearance_change_image_product( $value = null ){
            if (is_null($value)) $value = 'n';
            return array(  
                'title' => __( 'Changes product image', 'web-to-print-online-designer'),
                'description'   =>  __('Choose whether to change the product image.', 'web-to-print-online-designer'),
                'type' 		=> 'dropdown',
                'value'	=> $value,
                'options' =>    array(
                    array(
                        'key'   =>  'y',
                        'text'   =>  __( 'Yes', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'n',
                        'text'   =>  __( 'No', 'web-to-print-online-designer')
                    )
                )			
            );
        }  
        public function build_config_appearance_quantity_selector( $value = null ){
            if (is_null($value)) $value = 'n';
            return array(  
                'title' => __( 'Quantity selector', 'web-to-print-online-designer'),
                'description'   =>  __('This will show a quantity selector for this option.', 'web-to-print-online-designer'),
                'type' 		=> 'dropdown',
                'value'	=> $value,
                'options' =>    array(
                    array(
                        'key'   =>  'y',
                        'text'   =>  __( 'Yes', 'web-to-print-online-designer')
                    ),
                    array(
                        'key'   =>  'n',
                        'text'   =>  __( 'No', 'web-to-print-online-designer')
                    )
                )  			
            );
        }
        public function build_config_conditional_enable( $value = null ){
            if (is_null($value)) $value = 'n';
            return $value;
        }
        public function build_config_conditional_show( $value = null ){
            if (is_null($value)) $value = 'n';
            return $value;
        } 
        public function build_config_conditional_logic( $value = null ){
            if (is_null($value)) $value = 'a';
            return $value;
        } 
        public function build_config_conditional_depend( $value = null ){
            if (is_null($value) || count($value) == 0) $value = array(
                0   =>  array(
                    'id'    => '',
                    'operator'  =>  'i',
                    'val'   =>  ''
                )
            );
            return $value;
        }         
    }
}
function nbd_option_i18n(){
    return array(
        'page'  =>  __('Number of pages', 'web-to-print-online-designer'),
        'color'  =>  __('Color', 'web-to-print-online-designer'),
        'size'  =>  __('Size', 'web-to-print-online-designer'),
        'dpi'  =>  __('DPI', 'web-to-print-online-designer'),
        'area'  =>  __('Area design shape', 'web-to-print-online-designer'),
        'orientation'  =>  __('Orientation', 'web-to-print-online-designer'),
        'dimension'  =>  __('Custom dimension', 'web-to-print-online-designer'),
        'padding'  =>  __('Padding', 'web-to-print-online-designer'),
        'nbpb_com'  =>  __('Component', 'web-to-print-online-designer'),
        'nbpb_text'  =>  __('Text', 'web-to-print-online-designer'),
        'nbpb_image'  =>  __('Image', 'web-to-print-online-designer'),
        'dpi_description'  =>  __('DPI is used to describe the resolution number of dots per inch in a digital print and the printing resolution of a hard copy print dot gain, which is the increase in the size of the halftone dots during printing.', 'web-to-print-online-designer'),
        'vertical'  =>  __('Vertical', 'web-to-print-online-designer'),
        'horizontal'  =>  __('Horizontal', 'web-to-print-online-designer'),
        'can_not_add_att'  =>  __('Can not add more attribute for this option.', 'web-to-print-online-designer'),
        'can_not_remove_att'  =>  __('Can not remove this attribute.', 'web-to-print-online-designer'),
        'rectangle'  =>  __('Rectangle', 'web-to-print-online-designer'),
        'ellipse'  =>  __('Ellipse', 'web-to-print-online-designer'),
        'attribute_name'  =>  __('Attribute name', 'web-to-print-online-designer'),
        'sub_attribute_name'  =>  __('Sub attribute name', 'web-to-print-online-designer'),
        'can_not_copy'  =>  __('Can not copy this option.', 'web-to-print-online-designer'),
        'option_exist'  =>  __('This option exist.', 'web-to-print-online-designer'),
        'front'  =>  __('Front side', 'web-to-print-online-designer'),
        'back'  =>  __('Back side', 'web-to-print-online-designer'),
        'both'  =>  __('Both font and back sides', 'web-to-print-online-designer'),
        'want_to_delete'  =>  __('Are you sure you want to delete this item?', 'web-to-print-online-designer'),
        'choose_image'  =>  __('Choose Image', 'web-to-print-online-designer'),
        'view_name'  =>  __('View name', 'web-to-print-online-designer')
    );
}
$nbd_printing_options = NBD_ADMIN_PRINTING_OPTIONS::instance();
$nbd_printing_options->init();