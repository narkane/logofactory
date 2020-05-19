<?php
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
if( !class_exists('Nbdesigner_Settings_General') ) {
    class Nbdesigner_Settings_General {
        public static function get_options() {
            return apply_filters('nbdesigner_general_settings', array(
                'general-settings' => array(   
                    array(
                        'title' => __( 'Default design editor layout', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_design_layout',
                        'description' 	=> __('Choose default layout for design tool, you can choose specify layout for each product.<br /><b>Note</b>: We recommend <b>Modern</b> layout for the best user experience. We have stopped developing new features on Classic layout, consider this if you still want to use it.', 'web-to-print-online-designer'),
                        'default'	=> 'm',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'm' => __('<b>Modern</b> - recommended', 'web-to-print-online-designer'),
                            'v' => __('Visual', 'web-to-print-online-designer'),
                            'c' => __('Classic', 'web-to-print-online-designer')
                        )                        
                    ),                     
                    array(
                        'title' => __( 'Default output resolution - DPI', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_default_dpi',
                        'css'         => 'width: 65px',
                        'default'	=> '150',
                        'type' 		=> 'number'
                    ),                    
                    array(
                        'title' => __( 'Dimensions Unit', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_dimensions_unit',
                        'description' 	=> __('This controls what unit you will define lengths in.', 'web-to-print-online-designer'),
                        'default'	=> 'cm',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'cm' => __('cm', 'web-to-print-online-designer'),
                            'in' => __('in', 'web-to-print-online-designer'),
                            'mm' => __('mm', 'web-to-print-online-designer')
                        )                        
                    ),   
                    array(
                        'title' => __( 'Preview design size', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_thumbnail_width',
                        'description' 	=> __('This is size of preview design image which will show in cart or order page.', 'web-to-print-online-designer'),
                        'css'         => 'width: 65px',
                        'default'	=> '300',
                        'subfix'        => ' px',
                        'type' 		=> 'number'
                    ),  
                    array(
                        'title' => __( 'Preview template size', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_template_width',
                        'description' 	=> __('This is size of preview template image which will show in gallery page.', 'web-to-print-online-designer'),
                        'css'         => 'width: 65px',
                        'default'	=> '300',
                        'subfix'        => ' px',
                        'type' 		=> 'number'
                    ),                     
                    array(
                        'title' => __('Hide On Smartphones', 'web-to-print-online-designer'),
                        'description' => __('Hide product designer on smartphones and display an information instead.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_disable_on_smartphones',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),
                    array(
                        'title' => __('Allow save design for later', 'web-to-print-online-designer'),
                        'description' => __('Allow the customer save their design and continue working on it another time.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_save_for_later',
                        'default' => 'yes',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),  
                    array(
                        'title' => __('Allow share design', 'web-to-print-online-designer'),
                        'description' => __('Allow the customer share their design via email or social network.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_share_design',
                        'default' => 'yes',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),                    
                    array(
                        'title' => __('Cache latest design on user browser', 'web-to-print-online-designer'),
                        'description' => __('Save customer latest design. When they come back design product, they latest design will be loaded.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_save_latest_design',
                        'default' => 'yes',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),
                    array(
                        'title' => __('Cache customer uploaded image', 'web-to-print-online-designer'),
                        'description' => __('Cache customer uploaded image links on browser.', 'web-to-print-online-designer'),
                        'id' => 'nbdesigner_cache_uploaded_image',
                        'default' => 'yes',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),                    
                    array(
                        'title' => __('Allow customer re-design after order', 'web-to-print-online-designer'),
                        'description' => __('After order, customer can edit they design before it is approved or rejected.', 'web-to-print-online-designer'),
                        'id' => 'allow_customer_redesign_after_order',
                        'default' => 'yes',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),
                    array(
                        'title' => __('Allow download design after checkout', 'web-to-print-online-designer'),
                        'description' => __('Allow the customer download their designs or upload files after checkout.', 'web-to-print-online-designer'),
                        'id' => 'allow_customer_download_after_complete_order',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes - all order status', 'web-to-print-online-designer'),
                            'complete' => __('Complete order', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),
                    array(
                        'title' => __( 'Design file type download', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_download_type',
                        'default'	=> json_encode(array(
                                'nbdesigner_download_design_png' => 0,                         
                                'nbdesigner_download_design_pdf' => 0,                         
                                'nbdesigner_download_design_svg' => 0,                         
                                'nbdesigner_download_design_jpg' => 0,                         
                                'nbdesigner_download_design_jpg_cmyk' => 0,                        
                                'nbdesigner_download_design_upload_file' => 0                        
                            )),
                        'description' 	=> __( 'Choose design file type which the customer can download. Design in JPG format require PHP Imagick. ', 'web-to-print-online-designer'),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_download_design_png' => __('PNG', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_pdf' => sprintf(__( 'PDF, detail config for <a target="_blank" href="%s">PDF</a>', 'web-to-print-online-designer'), esc_url(admin_url('admin.php?page=nbdesigner&tab=output'))),
                            'nbdesigner_download_design_svg' => __('SVG', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_jpg' => __('JPG ( require PHP Imagick )', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_jpg_cmyk' => __('CMYK - JPG ( require PHP Imagick )', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_upload_file' => __('Upload files - The customer upload files', 'web-to-print-online-designer')
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    ),
                    array(
                        'title' => __('Allow download design in editor', 'web-to-print-online-designer'),
                        'description' => __('Allow the customer download their designs in editor ( Modern layout ).', 'web-to-print-online-designer'),
                        'id' => 'allow_customer_download_design_in_editor',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),
                    array(
                        'title' => __( 'Design file type download', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_download_type',
                        'default'	=> json_encode(array(
                                'nbdesigner_download_design_in_editor_png' => 0,                         
                                'nbdesigner_download_design_in_editor_pdf' => 0,        
                                'nbdesigner_download_design_in_editor_svg' => 0,                         
                                'nbdesigner_download_design_in_editor_jpg' => 0                      
                            )),
                        'description' 	=> __( 'Choose design file type which the customer can download. Design in JPG format require PHP Imagick. ', 'web-to-print-online-designer'),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => is_available_imagick() ? array(
                            'nbdesigner_download_design_in_editor_png' => __('PNG', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_in_editor_svg' => __('SVG', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_in_editor_pdf' => sprintf(__( 'PDF, detail config for <a target="_blank" href="%s">PDF</a>', 'web-to-print-online-designer'), esc_url(admin_url('admin.php?page=nbdesigner&tab=output'))),
                            'nbdesigner_download_design_in_editor_jpg' => __('JPG', 'web-to-print-online-designer')
                        ) : array(
                            'nbdesigner_download_design_in_editor_png' => __('PNG', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_in_editor_svg' => __('SVG', 'web-to-print-online-designer'),
                            'nbdesigner_download_design_in_editor_pdf' => sprintf(__( 'PDF, detail config for <a target="_blank" href="%s">PDF</a>', 'web-to-print-online-designer'), esc_url(admin_url('admin.php?page=nbdesigner&tab=output')))
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    ) 
                ),
                'admin-notifications' => array(
                    array(
                        'title' => __( 'Admin notifications', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_notifications',
                        'description' 	=> __('Send a message to the admin when customer design saved / changed.', 'web-to-print-online-designer'),
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                        
                    ),
                    array(
                        'title' => __( 'Recurrence', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_notifications_recurrence',
                        'description' 	=> __('Choose how many times you want to receive an e-mail.', 'web-to-print-online-designer'),
                        'default'	=> 'hourly',
                        'type' 		=> 'select',
                        'options'   => array(
                            'hourly' => __('Hourly', 'web-to-print-online-designer'),
                            'twicedaily' => __('Twice a day', 'web-to-print-online-designer'),
                            'daily' => __('Daily', 'web-to-print-online-designer')
                        )
                    ),   
                    array(
                        'title' => __( 'Recipients', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Enter recipients (comma separated) for this email. Defaults to ', 'web-to-print-online-designer').'<code>'.get_option('admin_email').'</code>',
                        'id' 		=> 'nbdesigner_notifications_emails',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text',
                        'placeholder'   => 'Enter your email'
                    ),    
                    array(
                        'title' => __( 'Send mail to admins when approve designs', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_enable_send_mail_when_approve',
                        'description' 	=> __('Send mail to admins when approve the customer designs.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                        
                    ),     
                    array(
                        'title' => __( 'Admin emails who receive notification when designs approved', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Enter recipients (comma separated) for admin email. Defaults to ', 'web-to-print-online-designer').'<code>'.get_option('admin_email').'</code>',
                        'id' 		=> 'nbdesigner_admin_emails',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text',
                        'placeholder'   => 'Enter admin emails'
                    ),
                    array(
                        'title' => __( 'Attach custom designs', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Attach custom designs in Admin notifications email.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_attachment_admin_email',
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )  
                    ),
                    array(
                        'title' => __( 'Attach custom designs type', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_option_attach_type',
                        'default'	=> json_encode(array(
                                'nbdesigner_attach_design_png' => 0,                            
                                'nbdesigner_attach_design_svg' => 0,                          
                            )),
                        'description' 	=> __( 'Choose design file type which attach in email.', 'web-to-print-online-designer'),
                        'type' 		=> 'multicheckbox',
                        'class'         => 'regular-text',
                        'options'   => array(
                            'nbdesigner_attach_design_png' => __('PNG', 'web-to-print-online-designer'),
                            'nbdesigner_attach_design_svg' => __('SVG', 'web-to-print-online-designer')
                        ),
                        'css' => 'margin: 0 15px 10px 5px;'
                    )                     
                ),
                'nbd-pages'       => array(
                    array(
                        'title' => __( 'Create your own page', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Choose Create your own page.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_create_your_own_page_id',
                        'type' 		=> 'select',
                        'default'	=> nbd_get_page_id( 'create_your_own' ),
                        'options'        =>  nbd_get_pages()
                    ),       
                    array(
                        'title' => __( 'Designer page', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Choose designer page.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_designer_page_id',
                        'type' 		=> 'select',
                        'default'	=> nbd_get_page_id( 'designer' ),
                        'options'        =>  nbd_get_pages()
                    ),    
                    array(
                        'title' => __( 'Gallery', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Choose Gallery page.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_gallery_page_id',
                        'type' 		=> 'select',
                        'default'	=> nbd_get_page_id( 'gallery' ),
                        'options'        =>  nbd_get_pages()
                    ),
                    array(
                        'title' => __( 'Redirect login', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Choose login page on design tool.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_logged_page_id',
                        'type' 		=> 'select',
                        'default'	=> nbd_get_page_id( 'logged' ),
                        'options'        =>  nbd_get_pages()
                    ),
                    array(
                        'title' => __( 'Product builder page', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Choose Product builder page.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_product_builder_page_id',
                        'type' 		=> 'select',
                        'default'	=> nbd_get_page_id( 'product_builder' ),
                        'options'        =>  nbd_get_pages()
                    )                     
                ),
                'application'       => array(
                    array(
                        'title' => __( 'Facebook App ID', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Enter a Facebook App-ID to allow customer use Facebook photos.', 'web-to-print-online-designer').' <a href="#" id="nbdesigner_show_helper">'.__("Where do I get this info?", 'web-to-print-online-designer').'</a>',
                        'id' 		=> 'nbdesigner_facebook_app_id',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text'
                    ), 
                    array(
                        'title' => __( 'Instagram App ID', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Enter a Instagram App-ID to allow customer use Instagram photos.', 'web-to-print-online-designer') . '<br /> <b>Redirect URI: '.NBDESIGNER_PLUGIN_URL.'includes/auth-instagram.php</b>',
                        'id' 		=> 'nbdesigner_instagram_app_id',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text'
                    ), 
                    array(
                        'title' => __( 'Dropbox App ID', 'web-to-print-online-designer'),
                        'description' 		=> __( 'Enter a Dropbox App-ID to allow customer use Dropbox photos.', 'web-to-print-online-designer'). '<br /><a href="https://www.dropbox.com/developers/apps/create" target="_blank" >'. __('Create a new app','web-to-print-online-designer') .'</a><br />'.__('Edit app and set "Chooser/Saver domains" with your domain: <b><code>'.$_SERVER['SERVER_NAME'].'</code></b>','web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_dropbox_app_id',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text'
                    ),                     
                    array(
                        'title' => __( 'Google API key', 'web-to-print-online-designer'),
                        'description' 		=> __( 'The Browser API key obtained from the Google API Console.', 'web-to-print-online-designer').' <a href="#" id="nbdesigner_google_drive_helper">'.__("Where do I get this info?", 'web-to-print-online-designer').'</a>',
                        'id' 		=> 'nbdesigner_google_api_key',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text'
                    ),
                    array(
                        'title' => __( 'Google Client ID', 'web-to-print-online-designer'),
                        'description' 		=> __( 'The Client ID obtained from the Google API Console.', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_google_client_id',
                        'class'         => 'regular-text',
                        'default'	=> '',
                        'type' 		=> 'text'
                    ),                     
                    array(
                        'title' => __( 'Pixabay API key', 'web-to-print-online-designer'),
                        'description' 		=> __( 'You can use default key or your Pixabay API key. That feature allow the customer serach images from Pixabay.', 'web-to-print-online-designer').' <a href="https://pixabay.com/vi/service/about/api/" target="_blank">'.__("Where do I get this info?", 'web-to-print-online-designer').'</a>',
                        'id' 		=> 'nbdesigner_pixabay_api_key',
                        'class'         => 'regular-text',
                        'default'	=> '27347-23fd1708b1c4f768195a5093b',
                        'type' 		=> 'text'
                    ),      
                    array(
                        'title' => __( 'Unsplash Application ID', 'web-to-print-online-designer'),
                        'description' 		=> __( 'You can use default key or your Unsplash Application ID. That feature allow the customer serach images from Pixabay.', 'web-to-print-online-designer').' <a href="https://unsplash.com/developers" target="_blank">'.__("Where do I get this info?", 'web-to-print-online-designer').'</a>',
                        'id' 		=> 'nbdesigner_unsplash_api_key',
                        'class'         => 'regular-text',
                        'default'	=> '5746b12f75e91c251bddf6f83bd2ad0d658122676e9bd2444e110951f9a04af8',
                        'type' 		=> 'text'
                    )
                ),
                'customization' => array(
                    
                ),
                'tools' => array(
                    array(
                        'title' => __('Force to upload SVG files', 'web-to-print-online-designer'),
                        'description' => __('Some themes or plugins disable upload SVG files feature. This option force to upload SVG files.', 'web-to-print-online-designer'),
                        'id' => 'nbd_force_upload_svg',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ), 
                    array(
                        'title' => __( 'Enable log mode', 'web-to-print-online-designer'),
                        'description'   => sprintf(__( 'Enable log mode for debug. <a href="%s">Logs</a>', 'web-to-print-online-designer'), esc_url(admin_url('admin.php?page=nbdesigner_tools#nbd-logs'))),
                        'id' 		=> 'nbdesigner_enable_log',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),
                    array(
                        'title' => __( 'Fix image URLs after change domain', 'web-to-print-online-designer'),
                        'description'   => sprintf(__( 'Fix image url after change domain or setup SSL.'), esc_url(admin_url('admin.php?page=nbdesigner_tools#nbd-logs'))),
                        'id' 		=> 'nbdesigner_fix_domain_changed',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),                    
                    array(
                        'title' => __( 'Cron job flush W3 Total cache', 'web-to-print-online-designer'),
                        'description'   => '',
                        'id' 		=> 'nbdesigner_cron_job_clear_w3_cache',
                        'default' => 'no',
                        'type' => 'radio',
                        'options' => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer'),
                        )
                    ),
                    array(
                        'title' => __( 'Site force login', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_site_force_login',
                        'description' 	=> __('Require user log in first.', 'web-to-print-online-designer'),
                        'default'	=> 'no',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                        
                    ),
                    array(
                        'title' => __( 'Redefine K_PATH_FONTS constant', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_redefine_K_PATH_FONTS',
                        'description' 	=> __('Redefine K_PATH_FONTS constant if your server does not provide the write permission in the plugins folder.', 'web-to-print-online-designer'),
                        'default'	=> 'yes',
                        'type' 		=> 'radio',
                        'options'   => array(
                            'yes' => __('Yes', 'web-to-print-online-designer'),
                            'no' => __('No', 'web-to-print-online-designer')
                        )                        
                    ),
                    array(
                        'title' => __( 'Disable NONCE in the submit form', 'web-to-print-online-designer'),
                        'id' 		=> 'nbdesigner_disable_nonce',
                        'description' 	=> __('Disable NONCE in the submit form if your site has a problem with cache plugins.', 'web-to-print-online-designer'),
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