<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<link href="https://fonts.googleapis.com/css?family=Roboto:400,500,700&amp;subset=vietnamese" rel="stylesheet">
		<link rel='stylesheet' id='dashicons-css'  href='<?php echo NBT_PDF_URL;?>temp/css/pdf_temp1.css' type='text/css' media='all' />
		<?php if(isset($_preview)){?>
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
			<h1><?php echo $brands;?></h1>
			<p class="info"><?php echo $address;?></p>
		</div>
		<div class="col-right">
			<img class="logo" src="<?php echo $logo;?>" />
		</div>
	</div>
	<hr />
	<div class="clearfix">
		<div class="col-left-to">
			<div class="col-left-to-wrap">
				<h2><?php _e( 'Bill to:', 'nbt-solution' ); ?></h2>
				<?php
				if ( $order->get_formatted_billing_address() ) {
					echo '<p>' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
				} else {
					echo '<p class="none_set"><strong>' . __( 'Address:', 'nbt-solution' ) . '</strong> ' . __( 'No billing address set.', 'nbt-solution' ) . '</p>';
				}?>
			</div>
		</div>
		<div class="col-left-to">
			<div class="col-left-to-wrap">
				<h2><?php _e( 'Ship to:', 'nbt-solution' ); ?></h2>
				<?php
				if ( $order->get_formatted_shipping_address() ) {
					echo '<p>' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
				} else {
					echo '<p>' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
				}
				?>
			</div>
		</div>
		<div class="col-left-to">
			<div class="col-right-to-wrap">
				<table>
					<tr>
						<td class="label"><?php _e( 'Order number:', 'nbt-solution' ); ?></td>
						<td class="span"><?php echo $order->get_order_number(); ?></td>
					</tr>
					<tr>
						<td class="label">Invoice Date</td>
						<td class="span"><?php echo wc_format_datetime( $order->get_date_created() ); ?></td>
					</tr>
					<tr>
						<td class="label"><?php _e( 'Payment:', 'nbt-solution' ); ?></td>
						<td class="span"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></td>
					</tr>
				</table>
			</div>
		</div>
	</div>
	<div class="clearfix">
		<table class="table table-bordered">
			<thead>
			  <tr>
			    <th><?php _e('Product Name', 'nbt-solution');?></th>
			    <th class="qty"><?php _e('Qty', 'nbt-solution');?></th>
			    <th><?php _e('Price', 'nbt-solution');?></th>
			    <th><?php _e('Total', 'nbt-solution');?></th>
			  </tr>
			</thead>
			<tbody>
				<?php foreach ( $order->get_items() as $item ) {
					$product_id = $item['product_id'];
					$_product = wc_get_product( $product_id );
					?>
				<tr>
					<td><?php echo $item->get_name();?></td>
					<td class="text-center"><?php echo $item->get_quantity();?></td>
					<td class="padding_r10" style="text-align: center"><?php echo wc_price($_product->get_price());?></td>
					<td class="padding_r10" style="text-align: center"><?php echo wc_price($item->get_total());?></td>
				</tr>
				<?php }?>
			</tbody>
		</table>
	</div>

	<div class="right-table">
		<table>
			<?php if( $subtotal = (float)$order->get_subtotal() ){?>
				<tr class="cart_subtotal">
					<td class="label"><?php esc_html_e( 'Subtotal', 'nbt-solution' ); ?></td>
					<td class="span"><?php echo wc_price($subtotal); ?></td>
				</tr>
			<?php }?>
			<?php if( $gettotals = (float)$order->get_total() ){?>
				<tr class="order_total">
					<td class="label"><?php esc_html_e( 'Total', 'nbt-solution' ); ?></td>
					<td class="span"><?php echo wc_price($gettotals); ?></td>
				</tr>
			<?php }?>
		</table>
		<?php
		if(isset($_preview)){?>
			<button type="button" class="btn btn-link btn-print-pdf"><i class="nbt-icon-file-pdf"></i> Print PDF</button>
		<?php }?>

	</div>
</body>
</html>
