<?php
if(isset($_REQUEST['key']) && $key = $_REQUEST['key'] ){
	global $wpdb;
	$order_id = $wpdb->get_var( "SELECT post_id FROM $wpdb->postmeta WHERE meta_key = '_order_key' AND meta_value = '".$key."'" );

	if($order_id && is_numeric($order_id)){
		$order = wc_get_order($order_id);
		
		$settings = array(
			'paper_size'		=> 'A4',
			'paper_orientation'	=> 'portrait',
			'font_subsetting'	=> false,
		);
		$pdf_settings = NB_Solution::get_setting('pdf-creator');


		if(isset($pdf_settings['nbt_pdf_logo'])){
			$logo = $pdf_settings['nbt_pdf_logo'];
		}

		if(isset($pdf_settings['nbt_pdf_brands'])){
			$brands = $pdf_settings['nbt_pdf_brands'];
		}

		if(isset($pdf_settings['nbt_pdf_address'])){
			$address = $pdf_settings['nbt_pdf_address'];
		}

		$primary_color = '#cd3334';
		if(isset($pdf_settings['nbt_pdf_primary_color'])){
			$primary_color = $pdf_settings['nbt_pdf_primary_color'];
		}

		$text_color = '#000';
		if(isset($pdf_settings['nbt_pdf_text_color'])){
			$text_color = $pdf_settings['nbt_pdf_text_color'];
		}

		$order_item_totals_show = array('cart_subtotal', 'order_total');
		$_preview = true;

		if( ! defined('PREFIX_NBT_SOL')){
			$font_css = NBT_PDF_PATH.'css/nbt-fonts.css';
		}else{
			$font_css = PREFIX_NBT_SOL_URL .'assets/frontend/css/nbt-fonts.css';
		}

		if(!$pdf_settings['nbt_pdf_template']){
			$pdf_settings['nbt_pdf_template'] = 'temp1';
		}
		
		$fullname_order = get_post_meta($order_id, '_billing_first_name', true). ' ' .get_post_meta($order_id, '_billing_last_name', true);

		if(file_exists(NBT_PDF_PATH.'temp/pdf_'.$pdf_settings['nbt_pdf_template'].'.php')){
			ob_start();
			include NBT_PDF_PATH.'temp/pdf_'.$pdf_settings['nbt_pdf_template'].'.php';
			$html = mb_convert_encoding(ob_get_clean(), 'HTML-ENTITIES', 'UTF-8');
			echo $html;
		}else{
			_e('Template '.$pdf_settings['nbt_pdf_template'].' not exists!');
		}
	}

}else{
	echo 'Page not found!';
}