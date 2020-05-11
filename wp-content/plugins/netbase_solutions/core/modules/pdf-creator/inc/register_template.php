<?php
if (!class_exists('NBT_Solutions_PDF_Template')) {
	class NBT_Solutions_PDF_Template{
		public function __construct() {
			$this->templates = array();

			add_filter( 'theme_page_templates', array( $this, 'theme_page_templates_callback' ) );

			add_filter('template_include', array($this, 'pdf_preview_page_templates'));

            // Add your templates to this array.
            $this->templates = array(
                'pdf_preview.php' => __('PDF Preview', 'nbt-solution'),
            );

		}

		public function theme_page_templates_callback($post_templates){
			$post_templates = array_merge($this->templates, $post_templates);
			return $post_templates;
		}

		public function pdf_preview_page_templates($template){
            global $post;

            if (!isset($post)) {
                return $template;
			}
			
            if (!isset($this->templates[get_post_meta($post->ID, '_wp_page_template', true)])) {
                return $template;
            }

            if ( 'pdf_preview.php' === get_post_meta( $post->ID, '_wp_page_template', true ) ) {
            	$file = NBT_PDF_PATH . 'temp/' . get_post_meta( $post->ID, '_wp_page_template', true );
				if ( file_exists( $file ) ) {
					return $file;
				}
	        }
		}
	}
}
new NBT_Solutions_PDF_Template();