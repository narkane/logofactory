<?php
/**
 * Dokan Seller Single product tab Template
 *
 * @since 2.4
 *
 * @package dokan
 */
?>

<h2><?php _e( 'Vendor Information', 'dokan' ); ?></h2>

<ul class="list-unstyled">

    <?php if ( !empty( $store_info['store_name'] ) ) { ?>
        <li class="store-name">
            <span><?php _e( 'Store Name:', 'dokan' ); ?></span>
            <span class="details">
                <?php echo esc_html( $store_info['store_name'] ); ?>
            </span>
        </li>
    <?php } ?>

    <li class="seller-name">
        <span>
            <?php _e( 'Vendor:', 'dokan' ); ?>
        </span>

        <span class="details">
            <?php printf( '<a href="%s">%s</a>', dokan_get_store_url( $author->ID ), $author->display_name ); ?>
        </span>
    </li>
    <?php if ( !empty( $store_info['address'] ) ) { ?>
        <li class="store-address">
            <span><b><?php _e( 'Address:', 'dokan' ); ?></b></span>
            <span class="details">
                <?php echo dokan_get_seller_address( $author->ID ) ?>
            </span>
        </li>
    <?php } ?>

    <li class="clearfix">
        <?php dokan_get_readable_seller_rating( $author->ID ); ?>
    </li>
</ul>
