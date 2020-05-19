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
	<div style="float: left; width: 50%;">
		<h1 style="margin: 0; pdading: 0; color: #cd3334; font-size: 16px;"><?php echo $brands;?></h1>
		<p style="margin: 0; color: #606060; font-size: 14px; line-height: 19px;"><?php echo $address;?></p>
	</div>

	<div style="float: right; width: 50%; text-align: right;">
		<img src="<?php echo $logo;?>" />
	</div>
</div>

<hr style="border-color: #cd3334; margin-top: 60px;" />
<div class="clearfix">
	<div style="float: left; width: 30%;">
		<h3 style="margin: 0; padding: 0; color: #cd3334; font-size: 14px;"><?php _e('Bill to', 'nbt-solution');?></h3>
		<?php
		if ( $order->get_formatted_billing_address() ) {
			echo '<p style="margin: 0; padding: 0; font-size: 14px;">' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
		} else {
			echo '<p style="margin: 0; padding: 0; font-size: 14px;" class="none_set"><strong>' . __( 'Address:', 'nbt-solution' ) . '</strong> ' . __( 'No billing address set.', 'nbt-solution' ) . '</p>';
		}?>
	</div>
	
	<div style="float: left; width: 30%;">
		<h3 style="margin: 0; padding: 0; color: #cd3334; font-size: 14px;"><?php _e('Ship to', 'nbt-solution');?></h3>
		<?php
		if ( $order->get_formatted_shipping_address() ) {
			echo '<p style="margin: 0; padding: 0; font-size: 13px;">' . wp_kses( $order->get_formatted_shipping_address(), array( 'br' => array() ) ) . '</p>';
		} else {
			echo '<p style="margin: 0; padding: 0; font-size: 13px;">' . wp_kses( $order->get_formatted_billing_address(), array( 'br' => array() ) ) . '</p>';
		}
		?>
	</div>
	
	<div style="float: left; width: 40%;">
		<table style="border-spacing: 0; border-collapse: collapse; width: 100%;">
			<tbody>
				<tr>
					<td style="color: #cd3334; font-size: 14px; font-weight: 700; text-align: right; white-space: nowrap;"><?php _e( 'Order number:', 'nbt-solution' ); ?></td>
					<td style="padding-left: 25px; font-size: 13px; text-align: right; white-space: nowrap;"><?php echo $order->get_order_number(); ?></td>
				</tr>
				<tr>
					<td style="color: #cd3334; font-size: 14px; font-weight: 700; text-align: right; white-space: nowrap;"><?php _e( 'Invoice Date:', 'nbt-solution' ); ?></td>
					<td style="padding-left: 25px; font-size: 13px; text-align: right; white-space: nowrap;"><?php echo wc_format_datetime( $order->get_date_created() ); ?></td>
				</tr>
				<tr>
					<td style="color: #cd3334; font-size: 14px; font-weight: 700; text-align: right; white-space: nowrap;"><?php _e( 'Payment:', 'nbt-solution' ); ?></td>
					<td style="padding-left: 25px; font-size: 13px; text-align: right; white-space: nowrap;"><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>


<div style="border-left: 1px solid #8a8a8a; border-right: 1px solid #8a8a8a; border-bottom: 1px solid #8a8a8a; margin-top: 30px;">
	<table style="border-spacing: 0; border-collapse: collapse; font-size: 14px; width: 100%;">
		<thead style="background: #cd3334; color: #fff; text-transform: uppercase;">
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
				<td style="border: 1px solid #d4d4d4; border-left: 0; padding: 6px 10px 9px; background-color: #f2f2f2;"><?php echo $item->get_name();?></td>
				<td style="border: 1px solid #d4d4d4; padding: 6px 10px 9px; background-color: #f2f2f2;"><?php echo $item->get_quantity();?></td>
				<td style="border: 1px solid #d4d4d4; padding: 6px 10px 9px; background-color: #f2f2f2;"><?php echo wc_price($_product->get_price());?></td>
				<td style="border: 1px solid #d4d4d4; border-right: 0; padding: 6px 10px 9px; background-color: #f2f2f2;"><?php echo wc_price($item->get_total());?></td>
			</tr>
			<?php }?>
		</tbody>
	</table>
</div>


	<div style="margin-top: 15px; text-align: right;">
		<table style="border-spacing: 0; border-collapse: collapse; font-size: 14px; width: 100%;">
			<?php if( $subtotal = (float)$order->get_subtotal() ){?>
				<tr class="cart_subtotal">
					<td style="width: 60%">&nbsp;</td>
					<td style="width: 20%; padding-left: 10px;"><?php esc_html_e( 'Subtotal', 'nbt-solution' ); ?></td>
					<td style="width: 20%; padding-left: 10px;"><?php echo wc_price($subtotal); ?></td>
				</tr>
			<?php }?>
			<?php if( $gettotals = (float)$order->get_total() ){?>
				<tr class="order_total">
					<td style="width: 60%">&nbsp;</td>
					<td style="width: 20%; padding-left: 10px; font-weight: 700; font-size: 18px;"><?php esc_html_e( 'Total', 'nbt-solution' ); ?></td>
					<td style="width: 20%; padding-left: 10px;"><?php echo wc_price($gettotals); ?></td>
				</tr>
			<?php }?>
		</table>
	</div>
