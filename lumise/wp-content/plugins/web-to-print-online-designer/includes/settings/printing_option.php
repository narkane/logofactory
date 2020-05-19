<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( !class_exists('Nbdesigner_Printing_Options') ) {    
    class Nbdesigner_Printing_Options{
        public static function get_options() {
            return apply_filters('nbdesigner_printing_options_settings', array(
                'general' => array(
                    array(
                        'title' => __( 'Options display style', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_display',
                        'description' 	=> __('This controls how options are displayed on the front-end .', 'web-to-print-online-designer'),
                        'default'	=> '1',
                        'type' 		=> 'radio',
                        'options'   => array(
                            '1' => __('Style 1', 'web-to-print-online-designer'),
                            '2' => __('Style 2', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Hide Add to cart button until all required options are chosen', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_add_cart_until_form_filled',
                        'description' 	=> __('Check this to show the add to cart button only when all required options are filled.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Hide summary options', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_summary_options',
                        'description' 	=> __('Hide summary options in product detail page.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Hide table pricing', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_table_pricing',
                        'description' 	=> __('Hide table pricing in product detail page.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Hide option swatch label', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_option_swatch_label',
                        'description' 	=> __('Hide option swatch label in Style 1 in product detail page.', 'web-to-print-online-designer'),
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),   
                    array(
                        'title' => __( 'Change original product price', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_change_base_price_html',
                        'description' 	=> __('Overwrite the original product price when options are changing.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Auto hide price if zero', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_zero_price',
                        'description' 	=> __('Hide the option price display if it is zero.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Option description tooltip position', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_tooltip_position',
                        'description' 	=> '',
                        'default'	=> 'top',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'top' => __('Top', 'web-to-print-online-designer'),
                            'right' => __('Right', 'web-to-print-online-designer'),
                            'bottom' => __('Bottom', 'web-to-print-online-designer'),
                            'left' => __('Left', 'web-to-print-online-designer'),
                        )                      
                    ),
                    array(
                        'title' => __( 'jQuery selector for increase/decrease quantity button', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_selector_increase_qty_btn',
                        'description' 	=> __('This is used to re calculate quantity discount price, example: .quantity-plus, .quantity-minus', 'web-to-print-online-designer'),
                        'default'	=> '',
                        'type' 		=> 'text',
                        'class'         => 'regular-text',
                        'placeholder'   => '.quantity-plus, .quantity-minus'
                    ),
                    array(
                        'title' => __('Display product options on', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_display_product_option',
                        'description' => __( 'Display product options on popup or product tab in modern layout.', 'web-to-print-online-designer'),
                        'default'	=> '1',
                        'type' 		=> 'radio',
                        'options'   => array(
                            '1' => __('Popup', 'web-to-print-online-designer'),
                            '2' => __('Product Tab', 'web-to-print-online-designer')
                        ) 
                    )
                ),
                'catalog'   => array(
                    array(
                        'title' => __( 'Force Select Options', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_force_select_options',
                        'description' 	=> __('This changes the add to cart button on shop and archive pages to display select options when the product has extra product options.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    )                     
                ),
                'cart' => array(
                    array(
                        'title' => __( 'Turn off persistent cart', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_turn_off_persistent_cart',
                        'description' 	=> __('Enable this if the product has a lot of options.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Clear cart button', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_clear_cart_button',
                        'description' 	=> __('Enables or disables the clear cart button.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Hide options in cart', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_options_in_cart',
                        'description' 	=> __('Enables or disables the display of options in cart.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Hide option price in cart', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_option_price_in_cart',
                        'description' 	=> __('Enables or disables the display of option price in cart.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    )                     
                ),
                'editor' => array(
                    array(
                        'title' => __( 'Auto hide Preview printing options in design editor', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_hide_print_option_in_editor',
                        'description' 	=> '',
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    )
                )
            ));
        }
    }
}