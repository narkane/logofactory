<?php
if (!defined('ABSPATH')) exit;
$product_id = $product->get_id();
$product_id = get_wpml_original_id($product_id);
$url = esc_url( get_permalink($product->get_id()) );
$layout = nbd_get_product_layout( $product_id );
$without_design = get_post_meta($product_id, '_nbdesigner_enable_upload_without_design', true);
if($layout != 'v'){
    $url =  add_query_arg(array(
        'product_id'    =>  $product_id,
        'view'    =>  $layout
    ),  getUrlPageNBD('create'));
}
if( $without_design ){
    $label = __('Upload design', 'web-to-print-online-designer');
    if( $product->get_type() != 'variable' ){
        $url =  add_query_arg(array(
            'product_id'    =>  $product_id,
            'view'    =>  'c'
        ),  getUrlPageNBD('create'));
    }else{
        $url = esc_url( get_permalink($product->get_id()) );
    }
}
echo sprintf( '<a rel="nofollow" href="%s" data-quantity="%s" data-product_id="%s" data-product_sku="%s" class="%s %s">%s</a>',
    $url,
    esc_attr( isset( $quantity ) ? $quantity : 1 ),
    esc_attr( $product->get_id() ),
    esc_attr( $product->get_sku() ),
    esc_attr( isset( $class ) ? $class : 'button' ),
    nbdesigner_get_option('nbdesigner_class_design_button_catalog'),
    esc_html( $label )
);
