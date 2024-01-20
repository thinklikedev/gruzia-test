<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}

if (@$_SERVER['HTTP_REFERER'] && strpos($_SERVER['HTTP_REFERER'], 'product-category/') !== false) {
	$back_category = $_SERVER['HTTP_REFERER'];
} else {
	$back_category = get_term_link($GLOBALS['curr_cat']);
}

?>
<div id="single-product" <?php wc_product_class( '', $product ); ?>>
	<div class="container main">
		<!--
		<a href="<?= $back_category; ?>" class="back-to-list">
			<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
			<path d="M0.434314 7.43431C0.121895 7.74673 0.121895 8.25327 0.434314 8.56569C0.746734 8.87811 1.25327 8.87811 1.56569 8.56569L0.434314 7.43431ZM6.56569 3.56569C6.8781 3.25327 6.8781 2.74673 6.56569 2.43431C6.25327 2.1219 5.74673 2.1219 5.43431 2.43431L6.56569 3.56569ZM1.56569 8.56569L6.56569 3.56569L5.43431 2.43431L0.434314 7.43431L1.56569 8.56569Z" fill="#ebebeb"/>
			<path d="M1 8H13" stroke="#ebebeb" stroke-width="1.6" stroke-linecap="round"/>
			<path d="M1.56572 7.43434C1.2533 7.12192 0.746764 7.12192 0.434345 7.43434C0.121926 7.74676 0.121926 8.2533 0.434345 8.56572L1.56572 7.43434ZM5.43431 13.5657C5.74673 13.8781 6.25327 13.8781 6.56569 13.5657C6.8781 13.2533 6.8781 12.7467 6.56569 12.4343L5.43431 13.5657ZM0.434345 8.56572L5.43431 13.5657L6.56569 12.4343L1.56572 7.43434L0.434345 8.56572Z" fill="#ebebeb"/>
			</svg>
			Вернуться к списку
		</a>
		-->

		<?php wc_get_template('single-product/extra-products.php'); ?>
				
		<div class="single-product-data">
			<?php
			/**
			 * Hook: woocommerce_before_single_product_summary.
			 *
			 * @hooked woocommerce_show_product_sale_flash - 10
			 * @hooked woocommerce_show_product_images - 20
			 */
			do_action( 'woocommerce_before_single_product_summary' );
			?>

			<div class="single-content">
				<div class="summary entry-summary">
					<?php
					/**
					 * Hook: woocommerce_single_product_summary.
					 *
					 * @hooked woocommerce_template_single_title - 5
					 * @hooked woocommerce_template_single_rating - 10
					 * @hooked woocommerce_template_single_weight - 7
					 * @hooked woocommerce_template_single_description - 8
					 * @hooked woocommerce_template_single_price - 10
					 * @hooked woocommerce_template_single_excerpt - 20
					 * @hooked woocommerce_template_single_add_to_cart - 30
					 * @hooked woocommerce_template_single_meta - 40
					 * @hooked woocommerce_template_single_sharing - 50
					 * @hooked WC_Structured_Data::generate_product_data() - 60
					 */
					do_action( 'woocommerce_single_product_summary' );
					?>
				</div>
			</div>

			<?php
			/**
			 * Hook: woocommerce_after_single_product_summary.
			 *
			 * @hooked woocommerce_output_product_data_tabs - 10
			 * @hooked woocommerce_upsell_display - 15
			 * @hooked woocommerce_output_related_products - 20
			 */
			// do_action( 'woocommerce_after_single_product_summary' );
			?>
		</div>


		<?php wc_get_template('single-product/up-sells.php'); ?>
	</div>
</div>

<?php //do_action( 'woocommerce_after_single_product' ); ?>
