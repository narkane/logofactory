<style>
.clearfix:before, .clearfix:after {
    display: table;
    content: ' ';
}
.clearfix:after {
    clear: both;
}
</style>

<div class="clearfix">
	<div style="float: left; width: 60%;">
		<h2 style="margin: 0; padding: 0; text-transform: uppercase; color: #464646; font-size: 18px;"><?php _e('Bill to', 'nbt-solution');?></h2>
		<div style="margin-left: 30px; margin-top: 5px;">
			<h3 style="margin: 0; padding: 0; text-transform: uppercase; color: #464646; font-size: 16px;"><?php echo $fullname_order;?></h3>
			<?php
			if ( $order->get_formatted_billing_address() ) {
				$order_address = str_replace($fullname_order, '', $order->get_formatted_billing_address());
				echo '<p style="margin: 0; padding: 0; font-size: 14px;">' . ltrim(wp_kses( $order_address, array( 'br' => array() ) ), '<br />') . '</p>';
			} else {
				echo '<p style="margin: 0; padding: 0; font-size: 14px;" class="none_set"><strong>' . __( 'Address:', 'woocommerce' ) . '</strong> ' . __( 'No billing address set.', 'woocommerce' ) . '</p>';
			}?>
		</div>
		<h1 style="margin: 30px 0 5px; padding: 0; color: #464646; font-size: 26px; text-transform: uppercase;"><?php _e('Invoice', 'nbt-solution');?></h1>
		<p style="margin: 0 0 2px; padding: 0; text-transform: uppercase; font-size: 14px;"><?php _e('Invoice', 'nbt-solution');?> #<?php echo $order->get_order_number();?></p>
		<p style="margin: 0; padding: 0; text-transform: uppercase; font-size: 14px;"><?php _e('Date', 'nbt-solution');?> <?php echo wc_format_datetime( $order->get_date_created() ); ?></p>
	</div>
	
	<div style="float: left; width: 40%; border-left: 3px solid #d7d7d7; padding-left: 15px;">
		<img class="logo" src="<?php echo $logo;?>" />
		<p style="margin: 0; color: #636363; font-size: 14px; text-transform: uppercase;"><?php echo $address;?></p>
	</div>
</div>

<div style="border-left: 1px solid #8a8a8a; border-right: 1px solid #8a8a8a; border-bottom: 1px solid #8a8a8a; margin-top: 30px;">
	<table style="border-spacing: 0; border-collapse: collapse; font-size: 14px; width: 100%;">
		<thead style="background: #363a43; color: #fff; text-transform: uppercase;">
			<tr>
				<th style="width: 50%; border: 1px solid #d4d4d4; border-left: 0; border-top: 0; border-bottom: 0;vertical-align: top; padding: 6px 10px 7px; text-align: left; font-weight: 600;">Product Name</th>
				<th style="width: 10%; border: 1px solid #d4d4d4; border-top: 0; border-bottom: 0;vertical-align: top; padding: 6px 10px 7px; text-align: left; font-weight: 600;">Qty</th>
				<th style="width: 20%; border: 1px solid #d4d4d4; border-top: 0; border-bottom: 0;vertical-align: top; padding: 6px 10px 7px; text-align: left; font-weight: 600;">Price</th>
				<th style="width: 20%; border: 1px solid #d4d4d4; border-right: 0; border-top: 0; border-bottom: 0;vertical-align: top; padding: 6px 10px 7px; text-align: left; font-weight: 600;">Total</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ( $order->get_items() as $item ) {
				$product_id = $item['product_id'];
				$_product = wc_get_product( $product_id );
				?>
			<tr>
				<td style="border: 1px solid #d4d4d4; border-left: 0; padding: 6px 10px 9px; background-color: #fff;"><?php echo $item->get_name();?></td>
				<td style="border: 1px solid #d4d4d4; padding: 6px 10px 9px; background-color: #fff;"><?php echo $item->get_quantity();?></td>
				<td style="border: 1px solid #d4d4d4; padding: 6px 10px 9px; background-color: #fff;"><?php echo wc_price($_product->get_price());?></td>
				<td style="border: 1px solid #d4d4d4; border-right: 0; padding: 6px 10px 9px; background-color: #fff;"><?php echo wc_price($item->get_total());?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>

	<table style="background-color: #fff; border-spacing: 0; border-collapse: collapse; font-size: 14px; width: 100%; padding-top: 20px; padding-bottom: 20px;">
		<?php if( $subtotal = (float)$order->get_subtotal() ){?>
		<tr>
			<td style="width: 60%; padding: 0px 10px;">&nbsp;</td>
			<td style="width: 20%; padding: 0px 10px; text-transform: uppercase;"><?php esc_html_e( 'Subtotal', 'nbt-solution' ); ?></td>
			<td style="width: 20%; padding: 0px 10px;"><?php echo wc_price($subtotal); ?></td>
		</tr>
		<?php }?>

		<tr>
			<td style="width: 60%; padding: 0px 10px;">&nbsp;</td>
			<td style="width: 20%; padding: 0px 10px; text-transform: uppercase;"><?php esc_html_e( 'Tax Rate', 'nbt-solution' ); ?></td>
			<td style="width: 20%; padding: 0px 10px;"><?php echo number_format((float)get_post_meta($order_id, '_order_tax', true), 2); ?>%</td>
		</tr>
	</table>
	
	<table style="background-color: #363a43; border-spacing: 0; border-collapse: collapse; font-size: 14px; width: 100%; color: #fff;">
		<tr>
			<td style="width: 60%; padding: 0px 10px; padding: 6px 10px 8px;">&nbsp;</td>
			<td style="width: 20%; padding: 0px 10px; text-align: left; text-transform: uppercase; padding: 6px 10px 8px; font-weight: 700;"><?php esc_html_e( 'Total', 'nbt-solution' ); ?></td>
			<td style="width: 20%; padding: 0px 10px; padding: 6px 10px 8px; font-weight: 700;"><?php echo wc_price($gettotals); ?></td>
		</tr>
	</table>
</div>

<p style="margin: 20px 0; font-size: 13px; color: #666666;"><?php echo sprintf(__('Thank you for your business<br />payment is due max 7 days after invoice without deduction.', 'nbt-solution'));?></p>
