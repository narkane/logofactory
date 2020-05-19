<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( !class_exists('Nbdesigner_Settings_Output') ) {    
    class Nbdesigner_Settings_Output{
        public static function get_options() {
            return apply_filters('nbdesigner_output_settings', array(
                'output-settings' => array(
                    array(
                        'title' => __( 'Watermark', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_pdf_watermark',
                        'description' 	=> __('Enable watermark if allow customer download PDFs', 'web-to-print-online-designer'),
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Always', 'web-to-print-online-designer'),
                            'before' => __('Before complete order', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                      
                    ),
                    array(
                        'title' => __( 'Watermark type', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_pdf_watermark_type',
                        'default'	=> '2',
                        'type' 		=> 'radio',
                        'options' => array(
                            '1' => __('Image', 'web-to-print-online-designer'),
                            '2' => __('Text', 'web-to-print-online-designer')
                        )                     
                    ),
                    array(
                        'title' => __( 'Watermark image', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_pdf_watermark_image',
                        'description' 	=> __('Choose a watermark image', 'web-to-print-online-designer'),
                        'default'	=> '',
                        'type' 		=> 'nbd-media'                      
                    ),
                    array(
                        'title' => __( 'Watermark text', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Branded watermark text', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_pdf_watermark_text',
                        'class'         => 'regular-text',
                        'default'	=> get_bloginfo('name'),
                        'type' 		=> 'text'
                    ), 
                    array(
                        'title' => __( 'Show bleed', 'web-to-print-online-designer'),
                        'description' 	=> __( 'If the product include bleed line, show it below/above the content design.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_bleed_stack',
                        'default'	=> '1',
                        'type' 		=> 'radio',
                        'options' => array(
                            '1' => __('Below the content design.', 'web-to-print-online-designer'),
                            '2' => __('Above the content design.', 'web-to-print-online-designer')
                        )                     
                    ),
                    array(
                        'title' => __( 'Truetype fonts', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Each font in a separate line', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_truetype_fonts',
                        'class'         => 'regular-text',
                        'placeholder'         => 'Abel&#x0a;Abril Fatface&#x0a;Aguafina Script',
                        'css'         =>  'height: 10em;',
                        'default'	=> '',
                        'type' 		=> 'textarea'
                    )
                ),
                'jpeg-settings' => array(
                    array(
                        'title' => __( 'Default ICC profile', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_default_icc_profile',
                        'description' 	=> __('Set default ICC profile for jpg image. <br/><b>This feature require your server support Imagemagick with lcms2.</b>', 'web-to-print-online-designer'),
                        'type' 		=> 'select',
                        'default'	=> 1,
                        'options'        =>  nbd_get_icc_cmyk_list()  
                    )
                )
            ));
        }
    }
}