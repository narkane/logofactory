<?php
/**
 * Custom Woocommerce shop page.
 *
 * @package Netbase
 */

global $woocommerce;
$page_layout = esc_html( get_post_meta( wc_get_page_id('shop'), 'sidebar_option', true) );

printshop_get_header() ?>
		
		<?php
		if(is_shop() || is_product_category() || is_product_tag() || is_product()) {
			printshop_get_page_header(wc_get_page_id('shop'));
		}
		
		global $post;
		printshop_get_page_header($post->ID);
		
		if ( $woocommerce && is_shop() || $woocommerce && is_product() || $woocommerce && is_product_category() || $woocommerce && is_product_tag() ) {
				?>
				<div class="page-title-wrap">
					<div class="container">
						<h1 class="page-entry-title left">
							<?php woocommerce_page_title(); ?>
						</h1>
						<?php printshop_breadcrumb(); ?>
					</div>
				</div>
		<?php
			}		
		?>	
		
		<div id="content-wrap" class="<?php echo ( $page_layout == 'full-screen' ) ? '' : 'container'; ?> <?php echo esc_html(printshop_get_layout_class()); ?>">
			<div id="primary" class="<?php echo ( $page_layout == 'full-screen' ) ? 'content-area-full' : 'content-area'; ?>">
				<main id="main" class="site-main">

					<?php woocommerce_content(); ?>
	
				</main><!-- #main -->
			</div><!-- #primary -->
			
			<?php echo printshop_get_sidebar(); ?>
				
		</div> <!-- /#content-wrap -->

<?php
get_footer();
