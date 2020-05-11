<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if(!class_exists('Nbdesigner_Settings_Frontend')){
    class Nbdesigner_Settings_Frontend {
        public static function get_options() {
            return apply_filters('nbdesigner_design_tool_settings', array(
                'tool-text' => array(
                    array(
                        'title' => __( 'Enable tool Add Text', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_text',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Default font subset', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Choose your language font subset.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_default_font_subset',
                        'type' 		=> 'select',
                        'default'	=> 'all',
                        'options'        =>  _nbd_font_subsets()
                    ),                    
                    array(
                        'title' => __( 'Default text', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_default_text',
                        'default'	=> 'Text here',
                        'description' 		=> __( 'Default text when user add text', 'web-to-print-online-designer'),
                        'type' 		=> 'text',
                        'class'         => 'regular-text',
                    ),  
                    array(
                        'title' => __( 'Default color', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_default_color',
                        'default'	=> '#cc324b',
                        'description' 		=> sprintf(__( 'Default color text when user add text. If you\'re using limited color palette, make sure <a href="%s">this color</a> has been defined', 'web-to-print-online-designer'), esc_url(admin_url('admin.php?page=nbdesigner&tab=color'))),
                        'type' 		=> 'colorpicker'
                    ),                     
                    array(
                        'title' => __( 'Enable Curved Text', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_curvedtext',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'layout' 	=> 'c',
                        'description'   =>  __('Only available on 1.x versions', 'web-to-print-online-designer'),
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),   
                    array(
                        'title' => __( 'Enable Text transform unproportionally', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_text_free_transform',
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'description'   =>  __('When true, Text can be transformed unproportionally', 'web-to-print-online-designer'),
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),                     
                    array(
                        'title' => __( 'Enable Text pattern', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_textpattern',
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'layout' 	=> 'c',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Show/hide features', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_text',
                        'default'	=> json_encode(array(
                                'nbdesigner_text_change_font' => 1,
                                'nbdesigner_text_italic' => 1,
                                'nbdesigner_text_bold' => 1,
                                'nbdesigner_text_underline' => 0,
                                'nbdesigner_text_through' => 0,
                                'nbdesigner_text_overline' => 0,
                                'nbdesigner_text_case' => 1,
                                'nbdesigner_text_align_left' => 1,
                                'nbdesigner_text_align_right' => 1,
                                'nbdesigner_text_align_center' => 1,
                                'nbdesigner_text_color' => 1,
                                'nbdesigner_text_background' => 1,
                                'nbdesigner_text_shadow' => 0,
                                'nbdesigner_text_line_height' => 1,
                                'nbdesigner_text_spacing' => 1,
                                'nbdesigner_text_font_size' => 1,
                                'nbdesigner_text_opacity' => 1,
                                'nbdesigner_text_outline' => 1,
                                'nbdesigner_text_proportion' => 1,
                                'nbdesigner_text_rotate' => 1                        
                            )),
                        'description' 	=> __( 'Show/hide features in frontend', 'web-to-print-online-designer'),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'enable_select'         => 1,
                        'options'   => array(
                            'nbdesigner_text_change_font' => __('Change font', 'web-to-print-online-designer'),
                            'nbdesigner_text_italic' => __('Italic', 'web-to-print-online-designer'),
                            'nbdesigner_text_bold' => __('Bold', 'web-to-print-online-designer'),
                            'nbdesigner_text_underline' => __('Underline', 'web-to-print-online-designer'),
                            'nbdesigner_text_through' => __('Line-through', 'web-to-print-online-designer'),
                            'nbdesigner_text_overline' => __('Overline', 'web-to-print-online-designer'),
                            'nbdesigner_text_case' => __('Text Case', 'web-to-print-online-designer'),
                            'nbdesigner_text_align_left' => __('Align left', 'web-to-print-online-designer'),
                            'nbdesigner_text_align_right' => __('Align right', 'web-to-print-online-designer'),
                            'nbdesigner_text_align_center' => __('Align center', 'web-to-print-online-designer'),
                            'nbdesigner_text_color' => __('Text color', 'web-to-print-online-designer'),
                            'nbdesigner_text_background' => __('Text background', 'web-to-print-online-designer'),
                            'nbdesigner_text_shadow' => __('Text shadow', 'web-to-print-online-designer'),
                            'nbdesigner_text_line_height' => __('Line height', 'web-to-print-online-designer'),
                            'nbdesigner_text_spacing' => __('Spacing', 'web-to-print-online-designer'),
                            'nbdesigner_text_font_size' => __('Font size', 'web-to-print-online-designer'),
                            'nbdesigner_text_opacity' => __('Opacity', 'web-to-print-online-designer'),
                            'nbdesigner_text_outline' => __('Outline', 'web-to-print-online-designer'),
                            'nbdesigner_text_proportion' => __('Unlock proportion', 'web-to-print-online-designer'),
                            'nbdesigner_text_rotate' => __('Rotate', 'web-to-print-online-designer')
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                      
                ),
                'tool-clipart' => array(
                    array(
                        'title' => __( 'Enable tool Add Clipart', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_clipart',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Show/hide features', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_clipart',
                        'default'	=> json_encode(array(
                            'nbdesigner_clipart_change_path_color' => 1,      
                            'nbdesigner_clipart_rotate' => 1,     
                            'nbdesigner_clipart_opacity' => 1                            
                        )),
                        'description' 	=> __( 'Show/hide features in frontend', 'web-to-print-online-designer'),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_clipart_change_path_color' => __( 'Change color path', 'web-to-print-online-designer'),      
                            'nbdesigner_clipart_rotate' => __( 'Rotate', 'web-to-print-online-designer'),      
                            'nbdesigner_clipart_opacity' => __( 'Opacity', 'web-to-print-online-designer')
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                     
                ),
                'tool-image' => array(
                    array(
                        'title' => __( 'Enable tool Add Image', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_image',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ), 
                    array(
                        'title' => __( 'Enable upload image', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_upload_image',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),           
                    array(
                        'title' => __('Login Required', 'web-to-print-online-designer'),
                        'description' => __('Users must create an account in your Wordpress site and need to be logged-in to upload images.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_upload_designs_php_logged_in',
                        'default' => 'no',
                        'type' => 'checkbox'
                    ), 
                    array(
                        'title' => __('Allow upload multiple images', 'web-to-print-online-designer'),
                        'description' => __('Allow the customer upload multiple images.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_upload_multiple_images',
                        'default' => 'no',
                        'type' => 'checkbox'
                    ),    
                    array(
                        'title' => __('Max upload files at once', 'web-to-print-online-designer'),
                        'description' => '',
                        'id' => 'nbdesigner_max_upload_files_at_once',
                        'default' => 5,
                        'css'         => 'width: 65px',
                        'type' => 'number'
                    ),                    
                    array(
                        'title' => __( 'Max upload size', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_maxsize_upload',
                        'css'         => 'width: 65px',
                        'default'	=> nbd_get_max_upload_default(),
                        'subfix'        => ' MB',
                        'type' 		=> 'number'
                    ),    
                    array(
                        'title' => __( 'Min upload size', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_minsize_upload',
                        'css'         => 'width: 65px',
                        'default'	=> '0',
                        'subfix'        => ' MB',
                        'type' 		=> 'number'
                    ),
                    array(
                        'title' => __( 'Min image upload resolution', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_mindpi_upload',
                        'css'         => 'width: 65px',
                        'default'	=> '0',
                        'subfix'        => ' DPI',
                        'type' 		=> 'number'
                    ),  
                    array(
                        'title' => __( 'Enable low resolution image', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_low_resolution_image',
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'description'   =>  __( 'Alert a message to the customer', 'web-to-print-online-designer'),
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),                    
                    array(
                        'title' => __( 'Enable images from url', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_image_url',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ), 
                    array(
                        'title' => __( 'Enable get images from Google Drive', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_google_drive',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ), 
                    array(
                        'title' => __( 'Enable search from Pixabay', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_pixabay',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ), 
                    array(
                        'title' => __( 'Enable search from Unsplash', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_unsplash',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),                     
                    array(
                        'title' => __( 'Enable SVG code', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_svg_code',
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),                     
                    array(
                        'title' => __( 'Enable capture images by webcam', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_image_webcam',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),                    
                    array(
                        'title' => __( 'Enable Facebook photos', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_facebook_photo',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Enable Instagram photos', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_instagram_photo',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),  
                    array(
                        'title' => __( 'Enable Dropbox photos', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_dropbox_photo',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),                      
                    array(
                        'title' => __('Show terms and conditions', 'web-to-print-online-designer'),
                        'description' => __('Show term and conditions upload image.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_upload_show_term',
                        'default' => 'no',
                        'type' => 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                         
                    ),                    
                    array(
                        'title' => __( 'Terms and conditions upload image', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_upload_term',
                        'default'	=> 'Your term',
                        'type' 		=> 'textarea',
                        'description'      => __('HTML Tags Supported', 'web-to-print-online-designer'),
                        'css'         => 'width: 50em; height: 15em;'
                    ),  
                    array(
                        'title' => __( 'Show/hide features', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_image',
                        'default'	=> json_encode(array(
                                'nbdesigner_image_unlock_proportion' => 1,        
                                'nbdesigner_image_shadow' => 0,      
                                'nbdesigner_image_opacity' => 1,       
                                'nbdesigner_image_grayscale' => 1,  
                                'nbdesigner_image_invert' => 1,         
                                'nbdesigner_image_sepia' => 1, 
                                'nbdesigner_image_sepia2' => 1,       
                                'nbdesigner_image_remove_white' => 1,   
                                'nbdesigner_image_transparency' => 1,         
                                'nbdesigner_image_tint' => 1,       
                                'nbdesigner_image_blend' => 1, 
                                'nbdesigner_image_brightness' => 1,          
                                'nbdesigner_image_noise' => 1,         
                                'nbdesigner_image_pixelate' => 1,        
                                'nbdesigner_image_multiply' => 1,  
                                'nbdesigner_image_blur' => 1,          
                                'nbdesigner_image_sharpen' => 1,        
                                'nbdesigner_image_emboss' => 1,     
                                'nbdesigner_image_edge_enhance' => 1,        
                                'nbdesigner_image_rotate' => 1,      
                                'nbdesigner_image_crop' => 1,        
                                'nbdesigner_image_shapecrop' => 1                          
                            )),
                        'description' 	=> __( 'Show/hide features in frontend', 'web-to-print-online-designer'),
                        'type' 		=> 'multicheckbox',
                        'layout' 	=> 'c',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_image_unlock_proportion' => __( 'Unlock proportion', 'web-to-print-online-designer'),        
                            'nbdesigner_image_shadow' => __( 'Shadow', 'web-to-print-online-designer'),        
                            'nbdesigner_image_opacity' => __( 'Opacity', 'web-to-print-online-designer'),          
                            'nbdesigner_image_grayscale' => __( 'Grayscale', 'web-to-print-online-designer'),          
                            'nbdesigner_image_invert' => __( 'Invert', 'web-to-print-online-designer'),         
                            'nbdesigner_image_sepia' => __( 'Sepia', 'web-to-print-online-designer'),         
                            'nbdesigner_image_sepia2' => __( 'Sepia 2', 'web-to-print-online-designer'),           
                            'nbdesigner_image_remove_white' => __( 'Remove white', 'web-to-print-online-designer'),     
                            'nbdesigner_image_transparency' => __( 'Transparency', 'web-to-print-online-designer'),           
                            'nbdesigner_image_tint' => __( 'Tint', 'web-to-print-online-designer'),          
                            'nbdesigner_image_blend' => __( 'Blend mode', 'web-to-print-online-designer'),           
                            'nbdesigner_image_brightness' => __( 'Brightness', 'web-to-print-online-designer'),          
                            'nbdesigner_image_noise' => __( 'Noise', 'web-to-print-online-designer'),         
                            'nbdesigner_image_pixelate' => __( 'Pixelate', 'web-to-print-online-designer'),         
                            'nbdesigner_image_multiply' => __( 'Multiply', 'web-to-print-online-designer'),     
                            'nbdesigner_image_blur' => __( 'Blur', 'web-to-print-online-designer'),          
                            'nbdesigner_image_sharpen' => __( 'Sharpen', 'web-to-print-online-designer'),         
                            'nbdesigner_image_emboss' => __( 'Emboss', 'web-to-print-online-designer'),         
                            'nbdesigner_image_edge_enhance' => __( 'Edge enhance', 'web-to-print-online-designer'),          
                            'nbdesigner_image_rotate' => __( 'Rotate', 'web-to-print-online-designer'),         
                            'nbdesigner_image_crop' => __( 'Crop image', 'web-to-print-online-designer'),         
                            'nbdesigner_image_shapecrop' => __( 'Shape crop', 'web-to-print-online-designer'),  
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                    
                ),
                'tool-draw' => array(
                    array(
                        'title' => __( 'Enable Free Draw', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_draw',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),    
                    array(
                        'title' => __( 'Show/hide features', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_clipart',
                        'default'	=> json_encode(array(
                                'nbdesigner_draw_brush' => 1,         
                                   'nbdesigner_draw_brush_pencil' => 1,              
                                   'nbdesigner_draw_brush_circle' => 1,         
                                   'nbdesigner_draw_brush_spray' => 1,         
                                   'nbdesigner_draw_brush_pattern' => 0,        
                                   'nbdesigner_draw_brush_hline' => 0,     
                                   'nbdesigner_draw_brush_vline' => 0,           
                                   'nbdesigner_draw_brush_square' => 0,            
                                   'nbdesigner_draw_brush_diamond' => 0,         
                                   'nbdesigner_draw_brush_texture' => 0,           
                               'nbdesigner_draw_shape' => 1,   
                                   'nbdesigner_draw_shape_rectangle' => 1,   
                                   'nbdesigner_draw_shape_circle' => 1,   
                                   'nbdesigner_draw_shape_triangle' => 1,   
                                   'nbdesigner_draw_shape_line' => 1,   
                                   'nbdesigner_draw_shape_polygon' => 1,   
                                   'nbdesigner_draw_shape_hexagon' => 1                                     
                            )),
                        'description' 	=> __( 'Show/hide features in frontend', 'web-to-print-online-designer'),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'depend'    =>  array(
                            'nbdesigner_draw_brush' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_pencil' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_circle' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_spray' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_pattern' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_hline' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_vline' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_square' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_diamond' => 'nbdesigner_draw_brush',         
                                'nbdesigner_draw_brush_texture' => 'nbdesigner_draw_brush',        
                            'nbdesigner_draw_shape' => 'nbdesigner_draw_shape',
                                'nbdesigner_draw_shape_rectangle' => 'nbdesigner_draw_shape',
                                'nbdesigner_draw_shape_circle' => 'nbdesigner_draw_shape',
                                'nbdesigner_draw_shape_triangle' => 'nbdesigner_draw_shape',
                                'nbdesigner_draw_shape_line' => 'nbdesigner_draw_shape',
                                'nbdesigner_draw_shape_polygon' => 'nbdesigner_draw_shape',
                                'nbdesigner_draw_shape_hexagon' => 'nbdesigner_draw_shape'                           
                        ),
                        'options'   => array(
                            'nbdesigner_draw_brush' => __('Brush', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_pencil' => __('Pencil', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_circle' => __('Circle', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_spray' => __('Spray', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_pattern' => __('Pattern', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_hline' => __('Hline', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_vline' => __('Vline', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_square' => __('Square', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_diamond' => __('Diamond', 'web-to-print-online-designer'),         
                                'nbdesigner_draw_brush_texture' => __('Texture', 'web-to-print-online-designer'),        
                            'nbdesigner_draw_shape' => __('Geometrical shape', 'web-to-print-online-designer'),
                                'nbdesigner_draw_shape_rectangle' => __('Rectangle', 'web-to-print-online-designer'),
                                'nbdesigner_draw_shape_circle' => __('Circle', 'web-to-print-online-designer'),
                                'nbdesigner_draw_shape_triangle' => __('Triangle', 'web-to-print-online-designer'),
                                'nbdesigner_draw_shape_line' => __('Line', 'web-to-print-online-designer'),
                                'nbdesigner_draw_shape_polygon' => __('Polygon', 'web-to-print-online-designer'),
                                'nbdesigner_draw_shape_hexagon' => __('Hexagon', 'web-to-print-online-designer')
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                       
                ),   
                'tool-qrcode' => array(
                    array(
                        'title' => __( 'Enable QRCode', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_qrcode',
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),
                    array(
                        'title' => __( 'Default text', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_default_qrcode',
                        'default'	=> 'example.com',
                        'description' 	=> __( 'Default text for QRCode', 'web-to-print-online-designer'),
                        'type' 		=> 'text',
                        'class'         => 'regular-text',
                    )                     
                ),   
                'misc' => array(
                    array(
                        'title' => __( 'Enable scale object from center', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_object_center_scaling',
                        'description' => __('Change the default object scale origin from corner to center.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),
                    array(
                        'title' => __( 'Disable auto load primary tempalte.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_disable_auto_load_template',
                        'description' => __('Always show blank design pages and only load templates until the customer choose them.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        ) 
                    ),
                    array(
                        'title' => __( 'Lazy load default template', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_lazy_load_template',
                        'description' => __('Lazy load default template if the product has template and the default templates is a very large objects.', 'web-to-print-online-designer'),
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