<?php
$primary_color = '#363a45';
?>
<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="https://fonts.googleapis.com/css?family=Sunflower:300,500,700" rel="stylesheet">
		<?php if(isset($_preview)){?>
		<link rel='stylesheet' id='dashicons-css'  href='<?php echo NBT_PDF_URL;?>temp/css/fonts.css' type='text/css' media='all' />
		<link rel='stylesheet' id='dashicons-css'  href='<?php echo $font_css;?>' type='text/css' media='all' />
		<script type='text/javascript' src='<?php echo home_url();?>/wp-includes/js/jquery/jquery.js?ver=1.12.4'></script>
		<script type='text/javascript' src='<?php echo home_url();?>/wp-includes/js/jquery/jquery-migrate.js?ver=1.4.1' defer onload=''></script>
		<script type='text/javascript' src='<?php echo NBT_PDF_URL;?>assets/js/jquery.blockUI.js' defer onload=''></script>
		<script type='text/javascript'>
			var admin_ajax = '<?php echo admin_url('/admin-ajax.php');?>';
			var order_id = <?php echo $order->get_id();?>;
		</script>
		<script type='text/javascript' src='<?php echo NBT_PDF_URL;?>assets/js/frontend.js' defer onload=''></script>
		<?php }?>
		<link rel='stylesheet' id='dashicons-css'  href='<?php echo NBT_PDF_URL;?>temp/css/pdf_temp3.css' type='text/css' media='all' />

		<style type="text/css">
			body{
				color: <?php echo $text_color;?>
			}
			.table thead{
				background: <?php echo $primary_color;?>
			}
			hr{
				border-color: <?php echo $primary_color;?>;
			}
			.col-left-to h2, .col-right-to-wrap .label, .col-left h1{
				color: <?php echo $primary_color;?>;
			}
		</style>
	</head>
<body class="preview">
	<div class="clearfix">
		<div class="col-left">
			<img class="logo" src="<?php echo $logo;?>" />
			<p style="color: #606060; line-height: 23px;"><?php echo $address;?></p>
		</div>

		<div class="col-right">
			<h1 style="color: #363a43;"><?php _e('Invoice', 'nbt-solution');?></h1>
		</div>
	</div>

	<div class="height30"></div>

	<div class="clearfix">
		<div class="top-header">
			<div><span><?php _e('Invoice', 'nbt-solution');?> #<?php echo $order->get_order_number();?></span></div>
			<div class="separator"><span>|</span></div>
			<div><span><?php _e('Date', 'nbt-solution');?> <?php echo wc_format_datetime( $order->get_date_created() ); ?></span></div>
		</div>
		<div class="clear"></div>
	</div>

	<table class="body-detail">
		<tr>
			<td class="body-heading body-first"><h2><?php _e('Bill to', 'nbt-solution');?></h2></td>
			<td>
				<div class="bodydetail">
					<h3><?php echo $fullname_order;?></h3>
					<?php
					if ( $order->get_formatted_billing_address() ) {
						$order_address = str_replace($fullname_order, '', $order->get_formatted_billing_address());
						echo '<p>' . ltrim(wp_kses( $order_address, array( 'br' => array() ) ), '<br />') . '</p>';
					} else {
						echo '<p class="none_set"><strong>' . __( 'Address:', 'woocommerce' ) . '</strong> ' . __( 'No billing address set.', 'woocommerce' ) . '</p>';
					}?>
				</div>
			</td>
			<td class="space">&nbsp;</td>
			<td class="body-heading"><h2><?php _e('Ship to', 'nbt-solution');?></h2></td>
			<td class="body-last">
				<div class="bodydetail">
					<h3><?php echo $fullname_order;?></h3>
					<?php
					if ( $order->get_formatted_shipping_address() ) {
						$ship_address = str_replace($fullname_order, '', $order->get_formatted_shipping_address()); 
						echo '<p>' . wp_kses( $ship_address , array( 'br' => array() ) ) . '</p>';
					} else {
						echo '<p>' . ltrim(wp_kses( $order_address, array( 'br' => array() ) ), '<br />') . '</p>';
					}
					?>
				</div>
			</td>
		</tr>
	</table>


	<div class="clearfix group-table">
		<table class="table table-bordered">
			<thead>
			  <tr>
			    <th style="width: 50%" class="text-left"><?php _e('Product Name', 'nbt-solution');?></th>
			    <th style="width: 10%" class="qty"><?php _e('Qty', 'nbt-solution');?></th>
			    <th style="width: 20%"><?php _e('Price', 'nbt-solution');?></th>
			    <th style="width: 20%"><?php _e('Total', 'nbt-solution');?></th>
			  </tr>
			</thead>
			<tbody>
				<?php foreach ( $order->get_items() as $item ) {
					$product_id = $item['product_id'];
					$_product = wc_get_product( $product_id );
					?>
				<tr>
					<td class="text-left first-child"><?php echo $item->get_name();?></td>
					<td class="text-center"><?php echo $item->get_quantity();?></td>
					<td class="text-right padding_r10"><?php echo wc_price($_product->get_price());?></td>
					<td class="text-right padding_r10 last-child"><?php echo wc_price($item->get_total());?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>


		<table class="subtotal-table">
			<?php if( $subtotal = (float)$order->get_subtotal() ){?>
				<tr class="cart_subtotal">
					<td class="subtotal-empty">&nbsp;</td>
					<td class="label text-right"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></td>
					<td class="span text-right"><?php echo wc_price($subtotal); ?></td>
				</tr>
			<?php }?>

				<tr class="order_total">
					<td class="subtotal-empty">&nbsp;</td>
					<td class="label text-right"><?php esc_html_e( 'Tax Rate', 'woocommerce' ); ?></td>
					<td class="span text-right"><?php echo number_format((float)get_post_meta($order_id, '_order_tax', true), 2); ?>%</td>
				</tr>
		</table>

		<?php if( $gettotals = (float)$order->get_total() ){?>
		<div class="total-full clearfix">
			<div class="total-col1">&nbsp;</div>
			<div class="total-label"><label><?php esc_html_e( 'Total', 'woocommerce' ); ?></label></div>
			<div class="total-price"><?php echo wc_price($gettotals); ?></div>
		</div>
		<?php }?>
	</div>
	
	<p class="note-temp"><?php echo sprintf(__('Thank you for your business<br />payment is due max 7 days after invoice without deduction.', 'nbt-solution'));?></p>


		<?php
		if(isset($_preview)){?>
			<button type="button" class="btn btn-link btn-print-pdf"><i class="nbt-icon-file-pdf"></i> Print PDF</button>
		<?php }?>
</body>
</html>
