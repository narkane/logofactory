<?php
require NBT_PDF_PATH . 'inc/vendor/autoload.php';

use Dompdf\Dompdf;
use Dompdf\Options;

class NBT_Pdf_Creator_Ajax{

	protected static $initialized = false;

	protected static $settings;

	protected static $order_id;
	
    /**
     * Initialize functions.
     *
     * @return  void
     */
    public static function initialize() {
        if ( self::$initialized ) {
            return;
        }

	    self::admin_hooks();
        self::$initialized = true;
    }


    public static function admin_hooks(){
		add_action( 'wp_ajax_nopriv_nbtpdf_download', array( __CLASS__, 'nbtpdf_download') );
		add_action( 'wp_ajax_nbtpdf_download', array( __CLASS__, 'nbtpdf_download') );
    }

    public static function nbtpdf_download(){
    	$order_id = absint($_REQUEST['order_id'] );
    	if(is_numeric($order_id)){
    		self::$order_id = $order_id;
			$order = wc_get_order($order_id);
			self::$settings = NB_Solution::get_setting('pdf-creator');

			$func = 'get_template_' . self::$settings['nbt_pdf_template'];
			if( ! method_exists('NBT_Pdf_Creator_Ajax', $func) ) {
				

				wp_die('Template exists!');
			}

			$html = self::$func();

			$filename = 'invoice-'.$order->get_id().'-'. time() .'.pdf';

			// set options
			$options = new Options();
			// $options->setdefaultFont( 'dejavu sans');
			// $options->setTempDir( NBT_Solutions_Pdf_Creator::get_temp('dompdf') );
			// $options->setLogOutputFile( NBT_Solutions_Pdf_Creator::get_temp('dompdf') . "/log.htm");
			// $options->setFontDir( NBT_Solutions_Pdf_Creator::get_temp('fonts') );
			// $options->setFontCache( NBT_Solutions_Pdf_Creator::get_temp('fonts') );
			$options->setIsRemoteEnabled( true );
			// $options->setIsFontSubsettingEnabled( false );
							
			// instantiate and use the dompdf class
			$dompdf = new Dompdf($options);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', self::$settings['nbt_'.NBT_Pdf_Creator_Settings::$id.'_page_orientation']);
			$dompdf->render();

			$upload_dir = wp_upload_dir();
			$file_to_save = $upload_dir['path'] . '/' . $filename;
			// save the pdf file on the server
			file_put_contents($file_to_save, $dompdf->output()); 


			$url = NBT_PDF_URL . 'inc/download.php?order_id=' . $order->get_id() . '&filename=' . $filename;

			header("Location: $url"); 



		}

		die();

    }

    public static function get_template_temp1() {
    	$order = wc_get_order(self::$order_id);

		if(isset(self::$settings['nbt_pdf_logo'])){
			$logo = self::$settings['nbt_pdf_logo'];
		}

		if(isset(self::$settings['nbt_pdf_brands'])){
			$brands = self::$settings['nbt_pdf_brands'];
		}

		if(isset(self::$settings['nbt_pdf_address'])){
			$address = self::$settings['nbt_pdf_address'];
		}


		$primary_color = '#cd3334';
		if(isset(self::$settings['nbt_pdf_primary_color'])){
			$primary_color = self::$settings['nbt_pdf_primary_color'];
		}

		$text_color = '#000';
		if(isset(self::$settings['nbt_pdf_text_color'])){
			$text_color = self::$settings['nbt_pdf_text_color'];
		}
		
		$fullname_order = get_post_meta($order->get_id(), '_billing_first_name', true). ' ' .get_post_meta($order->get_id(), '_billing_last_name', true);

		$nbt_pdf_fonts = 'Roboto:400,500,700&subset=vietnamese';
		if( isset( self::$settings['nbt_pdf_fonts']) ) {
			$nbt_pdf_fonts = base64_decode(self::$settings['nbt_pdf_fonts']);
		}

		$array_font_name = explode(':', $nbt_pdf_fonts);
		$font_name = $array_font_name[0];

	
		$order_item_totals_show = array('cart_subtotal', 'order_total');

    	$html = '
		<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<link href="https://fonts.googleapis.com/css?family='. $nbt_pdf_fonts .'" rel="stylesheet">
			<link rel="stylesheet" href="'. NBT_PDF_URL .'temp/css/pdf_temp1.css" type="text/css" media="all" />

			<style type="text/css">
				body {
					font-family: \''. $font_name .'\', sans-serif;
				}
			</style>
		</head>
		<body class="preview">
			<div class="clearfix">
				<div class="col-left">
					<p style="color: '. $primary_color.';font-size: 16px;margin: 0;padding: 0;font-weight: 500">'. $brands .'</p>
					<p class="info">'. $address .'</p>
				</div>
				<div class="col-right">
					<img class="logo" src="'. $logo .'" />
				</div>
			</div>
			<hr />
			<div class="clearfix">
				<div class="col-left-to">
					<div class="col-left-to-wrap">
						<h2>'. __( 'Bill to:', 'nbt-solution' ) .'</h2>';
						if ( $order->get_formatted_billing_address() ) {
							$html .= '<p>' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
						} else {
							$html .= '<p class="none_set"><strong>' . __( 'Address:', 'nbt-solution' ) . '</strong> ' . __( 'No billing address set.', 'nbt-solution' ) . '</p>';
						}
					$html .= '</div>
				</div>
				<div class="col-left-to">
					<div class="col-left-to-wrap">
						<h2>'. __( 'Ship to:', 'nbt-solution' ).'</h2>';
						if ( $order->get_formatted_shipping_address() ) {
							$html .= '<p>' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
						} else {
							$html .= '<p>' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
						}
					$html .= '</div>
				</div>
				<div class="col-left-to">
					<div class="col-right-to-wrap">
						<table>
							<tr>
								<td class="label">'. __( 'Order number:', 'nbt-solution' ) .'</td>
								<td class="span">'. $order->get_order_number() .'</td>
							</tr>
							<tr>
								<td class="label">Invoice Date</td>
								<td class="span">'. wc_format_datetime( $order->get_date_created() ) .'</td>
							</tr>
							<tr>
								<td class="label">'. __( 'Payment:', 'nbt-solution' ) .'</td>
								<td class="span">'. wp_kses_post( $order->get_payment_method_title() ) .'</td>
							</tr>
						</table>
					</div>
				</div>
			</div>
			<div class="clearfix">
				<table class="table table-bordered">
					<thead>
					  <tr>
					    <th>'. __('Product Name', 'nbt-solution') .'</th>
					    <th class="qty">'. __('Qty', 'nbt-solution') .'</th>
					    <th>'. __('Price', 'nbt-solution') .'</th>
					    <th>'. __('Total', 'nbt-solution') .'</th>
					  </tr>
					</thead>
					<tbody>';
						foreach ( $order->get_items() as $item ) {
							$product_id = $item['product_id'];
							$_product = wc_get_product( $product_id );
						$html .= '
						<tr>
							<td>'. $item->get_name() .'</td>
							<td class="text-center">'. $item->get_quantity() .'</td>
							<td class="padding_r10" style="text-align: center">'. wc_price($_product->get_price()) .'</td>
							<td class="padding_r10" style="text-align: center">'.  wc_price($item->get_total()) .'</td>
						</tr>';
						}
					$html .= '</tbody>
				</table>
			</div>

			<div class="right-table">
				<table>';
					if( $subtotal = (float)$order->get_subtotal() ){
						$html .= '<tr class="cart_subtotal">
							<td class="label">'. esc_html__( 'Subtotal', 'woocommerce' ) .'</td>
							<td class="span">'. wc_price($subtotal) .'</td>
						</tr>';
					}
					if( $gettotals = (float)$order->get_total() ) {
						$html .= '<tr class="order_total">
							<td class="label">'. esc_html__( 'Total', 'woocommerce' ) .'</td>
							<td class="span">'. wc_price($gettotals) .'</td>
						</tr>';
					}
				$html .= '</table>
		</div>
		</body>
	</html>';

	return $html;
	}

    public static function get_template_temp2() {
    	$order = wc_get_order(self::$order_id);

		if(isset(self::$settings['nbt_pdf_logo'])){
			$logo = self::$settings['nbt_pdf_logo'];
		}

		if(isset(self::$settings['nbt_pdf_brands'])){
			$brands = self::$settings['nbt_pdf_brands'];
		}

		if(isset(self::$settings['nbt_pdf_address'])){
			$address = self::$settings['nbt_pdf_address'];
		}


		$primary_color = '#cd3334';
		if(isset(self::$settings['nbt_pdf_primary_color'])){
			$primary_color = self::$settings['nbt_pdf_primary_color'];
		}

		$text_color = '#000';
		if(isset(self::$settings['nbt_pdf_text_color'])){
			$text_color = self::$settings['nbt_pdf_text_color'];
		}
		
		$fullname_order = get_post_meta($order->get_id(), '_billing_first_name', true). ' ' .get_post_meta($order->get_id(), '_billing_last_name', true);

		$nbt_pdf_fonts = 'Roboto:400,500,700&subset=vietnamese';
		if( isset( self::$settings['nbt_pdf_fonts']) ) {
			$nbt_pdf_fonts = base64_decode(self::$settings['nbt_pdf_fonts']);
		}

		$array_font_name = explode(':', $nbt_pdf_fonts);
		$font_name = $array_font_name[0];

	
		$order_item_totals_show = array('cart_subtotal', 'order_total');

    	$html = '
		<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<link href="https://fonts.googleapis.com/css?family='. $nbt_pdf_fonts .'" rel="stylesheet">
			<link rel="stylesheet" href="'. NBT_PDF_URL .'temp/css/pdf_temp2.css" type="text/css" media="all" />

			<style type="text/css">
				body {
					font-family: \''. $font_name .'\', sans-serif;
				}
			</style>
		</head>
		<body class="preview">
	<div class="clearfix">
		<div class="col-left">
			<h2 class="uppercase">'. __('Bill to', 'nbt-solution') .'</h2>
			<div class="left-bill-to">
				<h3 class="uppercase">'. $fullname_order .'</h3>';
				if ( $order->get_formatted_billing_address() ) {
					$order_address = str_replace($fullname_order, '', $order->get_formatted_billing_address());
					$html .= '<p>' . ltrim(wp_kses( $order_address, array( 'br' => array() ) ), '<br />') . '</p>';
				} else {
					$html .= '<p class="none_set"><strong>' . __( 'Address:', 'woocommerce' ) . '</strong> ' . __( 'No billing address set.', 'woocommerce' ) . '</p>';
				}
			$html .= '</div>

			<h1 class="uppercase heading-invoice">'. __('Invoice', 'nbt-solution') .'</h1>
			<p class="uppercase invoice">'. __('Invoice', 'nbt-solution').' #'. $order->get_order_number().'</p>
			<p class="uppercase date">'. __('Date', 'nbt-solution').' '. wc_format_datetime( $order->get_date_created() ) .'</p>
		</div>

		<div class="col-right">
			<div class="right-brands">
				<img class="logo" src="'. $logo .'" />
				<p>'. $address .'</p>
			</div>
		</div>
	</div>

	<div class="clearfix group-table">
		<table class="table table-bordered">
			<thead>
			  <tr>
			    <th style="width: 50%" class="text-left">'. __('Product Name', 'nbt-solution') .'</th>
			    <th style="width: 10%" class="qty">'. __('Qty', 'nbt-solution') .'</th>
			    <th style="width: 20%">'. __('Price', 'nbt-solution') .'</th>
			    <th style="width: 20%">'. __('Total', 'nbt-solution') .'</th>
			  </tr>
			</thead>
			<tbody>';
				foreach ( $order->get_items() as $item ) {
					$product_id = $item['product_id'];
					$_product = wc_get_product( $product_id );
				$html .= '
				<tr>
					<td class="text-left first-child">'. $item->get_name() .'</td>
					<td class="text-center">'. $item->get_quantity() .'</td>
					<td class="padding_r10">'. wc_price($_product->get_price()) .'</td>
					<td class="padding_r10 last-child">'. wc_price($item->get_total()) .'</td>
				</tr>';
				}
			$html .= '</tbody>
		</table>


		<table class="subtotal-table">';
			if( $subtotal = (float)$order->get_subtotal() ) {
				$html .= '
				<tr class="cart_subtotal">
					<td class="subtotal-empty">&nbsp;</td>
					<td class="label text-right">'. esc_html__( 'Subtotal', 'woocommerce' ) .'</td>
					<td class="span text-right">' . wc_price($subtotal) .'</td>
				</tr>';
			}

				$html .= '<tr class="order_total">
					<td class="subtotal-empty">&nbsp;</td>
					<td class="label text-right">'. esc_html__( 'Tax Rate', 'nbt-solution' ) .'</td>
					<td class="span text-right">'. number_format((float)get_post_meta($order_id, '_order_tax', true), 2) .'%</td>
				</tr>
		</table>';

		if( $gettotals = (float)$order->get_total() ) {
		$html .= '
		<div class="total-full clearfix">
			<div class="total-col1">&nbsp;</div>
			<div class="total-label"><label>'.  esc_html__( 'Total', 'woocommerce' ) .'</label></div>
			<div class="total-price">'. wc_price($gettotals) .'</div>
		</div>';
		}
	$html .= '</div>
	
	<p class="note-temp">'. sprintf(__('Thank you for your business<br />payment is due max 7 days after invoice without deduction.', 'nbt-solution')) .'</p>
	</body>
	</html>

		';

		return $html;
	}

    public static function get_template_temp3() {
    	$order = wc_get_order(self::$order_id);

		if(isset(self::$settings['nbt_pdf_logo'])){
			$logo = self::$settings['nbt_pdf_logo'];
		}

		if(isset(self::$settings['nbt_pdf_brands'])){
			$brands = self::$settings['nbt_pdf_brands'];
		}

		if(isset(self::$settings['nbt_pdf_address'])){
			$address = self::$settings['nbt_pdf_address'];
		}


		$primary_color = '#cd3334';
		if(isset(self::$settings['nbt_pdf_primary_color'])){
			$primary_color = self::$settings['nbt_pdf_primary_color'];
		}

		$text_color = '#000';
		if(isset(self::$settings['nbt_pdf_text_color'])){
			$text_color = self::$settings['nbt_pdf_text_color'];
		}
		
		$fullname_order = get_post_meta($order->get_id(), '_billing_first_name', true). ' ' .get_post_meta($order->get_id(), '_billing_last_name', true);

		$nbt_pdf_fonts = 'Roboto:400,500,700&subset=vietnamese';
		if( isset(self::$settings['nbt_pdf_fonts']) ) {
			$nbt_pdf_fonts = base64_decode(self::$settings['nbt_pdf_fonts']);
		}

		$array_font_name = explode(':', $nbt_pdf_fonts);
		$font_name = $array_font_name[0];

	
		$order_item_totals_show = array('cart_subtotal', 'order_total');

    	$html = '
		<!DOCTYPE html>
		<html>
		<head>
			<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
			<link href="https://fonts.googleapis.com/css?family='. $nbt_pdf_fonts .'" rel="stylesheet">
			<link rel="stylesheet" href="'. NBT_PDF_URL .'temp/css/pdf_temp3.css" type="text/css" media="all" />

			<style type="text/css">
				body {
					font-family: \''. $font_name .'\', sans-serif;
				}
				.top-header div:last-child span {
					font-family: \''. $font_name .'\', sans-serif !important;
				}
			</style>
		</head>
		<body class="preview">
			<div class="clearfix">
				<div class="col-left">
					<img class="logo" src="'. $logo .'" />
					<p style="color: #606060; line-height: 23px;">'. $address .'</p>
				</div>

				<div class="col-right">
					<h1 style="color: #363a43;">'. __('Invoice', 'nbt-solution') .'</h1>
				</div>
			</div>

			<div class="height30"></div>

			<div class="clearfix">
				<div class="top-header" style="margin-bottom: -1px;padding: 13px 15px 3px;">
					<div><span>'. __('Invoice', 'nbt-solution').' #'. $order->get_order_number() .'</span></div>
					<div class="separator"><span>|</span></div>
					<div><span>'. __('Date', 'nbt-solution') . ' ' . wc_format_datetime( $order->get_date_created() ) .'</span></div>
				</div>
				<div class="clear"></div>
			</div>

			<table class="body-detail">
				<tr>
					<td class="body-heading body-first"><h2>'. __('Bill to', 'nbt-solution') .'</h2></td>
					<td>
						<div class="bodydetail">
							<h3>'. $fullname_order .'</h3>';
							
							if ( $order->get_formatted_billing_address() ) {
								$order_address = str_replace($fullname_order, '', $order->get_formatted_billing_address());
								$html .= '<p>' . ltrim(wp_kses( $order_address, array( 'br' => array() ) ), '<br />') . '</p>';
							} else {
								$html .= '<p class="none_set"><strong>' . __( 'Address:', 'woocommerce' ) . '</strong> ' . __( 'No billing address set.', 'woocommerce' ) . '</p>';
							}
						$html .= '</div>
					</td>
					<td class="space">&nbsp;</td>
					<td class="body-heading"><h2>'. __('Ship to', 'nbt-solution') .'</h2></td>
					<td class="body-last">
						<div class="bodydetail">
							<h3>'. $fullname_order .'</h3>';
							if ( $order->get_formatted_shipping_address() ) {
								$ship_address = str_replace($fullname_order, '', $order->get_formatted_shipping_address()); 
								$html .= '<p>' . wp_kses( $ship_address , array( 'br' => array() ) ) . '</p>';
							} else {
								$html .= '<p>' . ltrim(wp_kses( $order_address, array( 'br' => array() ) ), '<br />') . '</p>';
							}
						$html .= '</div>
					</td>
				</tr>
			</table>


	<div class="clearfix group-table">
		<table class="table table-bordered">
			<thead>
			  <tr>
			    <th style="width: 50%" class="text-left">'. __('Product Name', 'nbt-solution') .'</th>
			    <th style="width: 10%" class="qty">'. __('Qty', 'nbt-solution') .'</th>
			    <th style="width: 20%">'. __('Price', 'nbt-solution') .'</th>
			    <th style="width: 20%">'. __('Total', 'nbt-solution') .'</th>
			  </tr>
			</thead>
			<tbody>';
				foreach ( $order->get_items() as $item ) {
					$product_id = $item['product_id'];
					$_product = wc_get_product( $product_id );
				$html .= '
				<tr>
					<td class="text-left first-child">'. $item->get_name() .'</td>
					<td class="text-center">'. $item->get_quantity() .'</td>
					<td class="text-right padding_r10">'. wc_price($_product->get_price()) .'</td>
					<td class="text-right padding_r10 last-child">'. wc_price($item->get_total()) .'</td>
				</tr>';
				}
			$html .= '</tbody>
		</table>


		<table class="subtotal-table">';
			if( $subtotal = (float)$order->get_subtotal() ){
				$html .= '<tr class="cart_subtotal">
					<td class="subtotal-empty">&nbsp;</td>
					<td class="label text-right">'. esc_html__( 'Subtotal', 'woocommerce' ) .'</td>
					<td class="span text-right">'. wc_price($subtotal) .'</td>
				</tr>';
			}

				$html .= '
				<tr class="order_total">
					<td class="subtotal-empty">&nbsp;</td>
					<td class="label text-right">'. esc_html__( 'Tax Rate', 'nbt-solution' ) .'</td>
					<td class="span text-right">'. number_format((float)get_post_meta($order->get_id(), '_order_tax', true), 2) .'%</td>
				</tr>
		</table>';

		if( $gettotals = (float)$order->get_total() ) {
		$html .= '<div class="total-full clearfix">
			<div class="total-col1">&nbsp;</div>
			<div class="total-label"><label>'. esc_html__( 'Total', 'woocommerce' ) .'</label></div>
			<div class="total-price">'. wc_price($gettotals) .'</div>
		</div>';
		}
	$html .= '</div>
	
		<p class="note-temp">'. sprintf(__('Thank you for your business<br />payment is due max 7 days after invoice without deduction.', 'nbt-solution')) .'</p>

		</body>
		</html>';

		return $html;
    }

 
}