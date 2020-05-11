<?php
global $post;

$tax_classes = array_filter( array_map( 'trim', explode( "\n", get_option( 'woocommerce_tax_classes' ) ) ) );
$classes_options = array();
$classes_options[''] = __( 'Standard', 'dokan' );

if ( $tax_classes ) {

    foreach ( $tax_classes as $class ) {
        $classes_options[ sanitize_title( $class ) ] = esc_html( $class );
    }
}

?>
<div class="dokan-form-horizontal">
    <div class="row form-group">
        <div class="col-md-3"><label><?php _e( 'SKU', 'dokan' ); ?></label></div>
        <div class="col-md-9">
            <?php dokan_post_input_box( $post->ID, '_sku', array( 'placeholder' => __( 'SKU', 'dokan' ) ) ); ?>
        </div>
    </div>

    <div class="row form-group">
        <div class="col-md-3"><label><?php _e( 'Manage Stock?', 'dokan' ); ?></label></div>
        <div class="col-md-9">
            <?php dokan_post_input_box( $post->ID, '_manage_stock', array('label' => __( 'Enable stock management at product level', 'dokan' ) ), 'checkbox' ); ?>
        </div>
    </div>

    <div class="row form-group show_if_stock">
        <div class="col-md-3"><label><?php _e( 'Stock Qty', 'dokan' ); ?></label></div>
        <div class="col-md-9">
            <input type="number" name="_stock" id="_stock" step="any" placeholder="10" value="<?php echo wc_stock_amount( get_post_meta( $post->ID, '_stock', true ) ); ?>">
        </div>
    </div>

    <div class="row form-group hide_if_variable <?php echo ( $product_type == 'simple' ) ? 'show_if_stock' : ''; ?>">
        <div class="col-md-3"><label><?php _e( 'Stock Status', 'dokan' ); ?></label></div>
        <div class="col-md-9">
            <?php dokan_post_input_box( $post->ID, '_stock_status', array( 'options' => array(
                'instock' => __( 'In Stock', 'dokan' ),
                'outofstock' => __( 'Out of Stock', 'dokan' )
                ) ), 'select'
            ); ?>
        </div>
    </div>


    <div class="row form-group show_if_stock">
        <div class="col-md-3"><label><?php _e( 'Allow Backorders', 'dokan' ); ?></label></div>
        <div class="col-md-9">
            <?php dokan_post_input_box( $post->ID, '_backorders', array( 'options' => array(
                'no' => __( 'Do not allow', 'dokan' ),
                'notify' => __( 'Allow but notify customer', 'dokan' ),
                'yes' => __( 'Allow', 'dokan' )
                ) ), 'select'
            ); ?>
        </div>
    </div>

    <?php if ( 'yes' == get_option( 'woocommerce_calc_taxes' ) ) { ?>

    <div class="row form-group">
        <div class="col-md-3"><label><?php _e( 'Tax Status', 'dokan' ); ?></label></div>
        <div class="col-md-9">
                <?php dokan_post_input_box( $post->ID, '_tax_status', array( 'options' => array(
                    'taxable'   => __( 'Taxable', 'dokan' ),
                    'shipping'  => __( 'Shipping only', 'dokan' ),
                    'none'      => _x( 'None', 'Tax status', 'dokan' )
                    ) ), 'select'
                ); ?>
            </div>
        </div>

    <div class="row form-group">
        <div class="col-md-3"><label><?php _e( 'Tax Class', 'dokan' ); ?></label></div>
        <div class="col-md-9">
                <?php dokan_post_input_box( $post->ID, '_tax_class', array( 'options' => $classes_options ), 'select' ); ?>
            </div>
        </div>

    <?php } ?>
</div> <!-- .form-horizontal -->