<?php
class NBTMCS_Admin {


	public $available_tabs = array();

	/**
	 * Class constructor.
	 */
	public function __construct() {
		add_action( 'admin_print_scripts', array( $this, 'enqueue_scripts' ) );

		if( defined('PREFIX_NBT_SOL') && !class_exists('NBT_Plugins') ){
			add_action('admin_menu', array($this, 'register_subpage_media'));
		}else{
			//add_action( 'admin_menu', array( $this, 'register_panel' ), 5 );
		}

		add_action( 'woocommerce_admin_order_totals_after_tax', array($this, 'woocommerce_admin_order_totals_after_tax'), 10, 1 );
		add_filter('nbt_admin_field_rate', array($this, 'nbt_admin_field_rate'), 10, 3);
		add_filter( 'metabox_extra_nbt_currency-switcher_repeater', array($this, 'nbt_currency_switcher_repeater'), 10, 4 );

	}

	public function nbt_currency_switcher_repeater($array, $post, $key, $index){
		$array = array(
			'nbt_currency-switcher_repeater_symbol' => get_woocommerce_currency_symbol( $key )
		);
		return $array;
	}

	public function nbt_admin_field_rate($type, $field, $value){
		?>
		<div class="field-cs-rate" >
			<input type="text" class="<?php echo esc_attr( str_replace('[]', '', $field['id']) ) ?>" name="<?php echo esc_attr( $field['id'] ) ?>" value="<?php echo esc_attr( $value ) ?>"<?php if(isset($field['min'])){ echo ' min="'.$field['min'].'"';}?><?php if(isset($field['max'])){ echo ' max="'.$field['max'].'"';}?> />
			<button type="button" class="nbt-load-rate button">Load</button>
		</div>
		<?php
	}

	public function woocommerce_admin_order_totals_after_tax($order_id){
		$_currency_order = get_post_meta($order_id, '_currency_order', true);
		if($_currency_order){
			?>
			<tr>
				<td class="label"><?php echo wc_help_tip( __( 'This is the rate when customer ordered.', 'woocommerce' ) ); ?> <?php _e( 'Rate '.get_woocommerce_currency() .' to '.$_currency_order['currency'], 'woocommerce' ); ?></td>
				<td width="1%"></td>
				<td class="total">
					<span class="woocommerce-Price-amount amount"><?php echo $_currency_order['rate'];?></span>
				</td>
			</tr>
			<?php
		}
	}

	public function register_panel(){
		$args = array(
			'create_menu_page' => true,
			'parent_slug'   => '',
			'page_title'    => __( 'Currency Swichter', 'nbt-currency-switcher' ),
			'menu_title'    => __( 'Currency Swichter', 'nbt-currency-switchercurrency-switcher' ),
			'capability'    => apply_filters( 'nbt_cs_settings_panel_capability', 'manage_options' ),
			'parent'        => '',
			'parent_page'   => 'ntb_plugin_panel',
			'page'          => 'ntb_cs_panel',
			'admin-tabs'    => $this->available_tabs,
			'functions'		=> array(__CLASS__ , 'ntb_cs_page'),
			'font-path'  => NBT_MCS_URL . 'assets/css/nbt-plugins.css'
		);

		$this->_panel = new NBT_Plugins($args);
	}

	public function ntb_cs_page(){
		include(NBT_MCS_PATH .'tpl/admin/settings_clone.php');
	}
	public function register_subpage_media(){
		add_submenu_page('solutions', 'page-title', 'Curreny Swichter', 'manage_options', 'curency-swichter', array($this, 'media_order_admin_page')); 

	}

	public function media_order_admin_page(){
		$settings = get_option('_nbtmcs_currency_lists' );
		$settings_mcs = get_option('settings_mcs' );
		include NBT_MCS_PATH . 'tpl/admin/settings.php';
	}

	public function enqueue_scripts() {
		wp_enqueue_media();
		wp_enqueue_style( 'nbtmcs-admin', NBT_MCS_URL . 'assets/css/admin.css', array( )  );
		wp_enqueue_script( 'nbtmcs-blockUI', NBT_MCS_URL . 'assets/js/jquery.blockUI.js', array(  ));
		if( !defined('PREFIX_NBT_SOL')){
			

			
		}wp_enqueue_style( 'woocommerce_admin_styles', WC()->plugin_url() . '/assets/css/admin.css', array( )  );
	wp_enqueue_script( 'select2', WC()->plugin_url() . '/assets/js/select2/select2.full.js', array(  ));
		wp_enqueue_script( 'nbtmcs-admin', NBT_MCS_URL . 'assets/js/admin.js', array(  ));

		wp_localize_script( 'nbtmcs-admin', 'nbtmcs', array(
			'ajax_url' => admin_url( 'admin-ajax.php' ),
			'i18n'        => array(
				'mediaTitle'  => esc_html__( 'Choose an image', 'wcvs' ),
				'mediaButton' => esc_html__( 'Use image', 'wcvs' ),
			),
			'placeholder' => WC()->plugin_url() . '/assets/images/placeholder.png'
		));
	}


}
new NBTMCS_Admin();