<?php
/**
 * Single Product Up-Sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $wpdb;

$product_id = get_the_ID();
$current_cat = get_the_terms($product_id, 'product_cat');

if ($current_cat && !is_wp_error($current_cat)) {
    if (is_array($current_cat)) {
        $current_cat = end($current_cat);
    } else {
        $current_cat = $current_cat ? array_shift($current_cat) : false;
    }
} else {
    $cat_napitki = get_term_by('slug', 'napitki', 'product_cat');
    $current_cat = $cat_napitki; 
}

$cat_napitki = get_term_by('slug', 'napitki', 'product_cat');

if ($current_cat && isset($current_cat->term_id)) {
    $term_id = $current_cat->term_id;
} else {
    $term_id = $cat_napitki ? $cat_napitki->term_id : 0;
}

$same_cat_ids = $wpdb->get_col("
    SELECT p.ID
    FROM {$wpdb->posts} p
    INNER JOIN {$wpdb->term_relationships} tr ON (p.ID = tr.object_id)
    WHERE p.ID <> $product_id
    AND p.post_type = 'product'
    AND p.post_status = 'publish'
    AND tr.term_taxonomy_id = $term_id
    ORDER BY RAND()
    LIMIT 3
");
$napitki = [];

$upsells = array_merge($same_cat_ids, $napitki);

if ($upsells) : ?>

	<h2 class="block-title">Также заказывают:</h2>
		
	<div class="container products-grid">
		<?php foreach ( $upsells as $product_id ) : ?>
			<?php
			$post_object = get_post( $product_id );

			setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

			wc_get_template_part( 'content', 'product' );
			?>

		<?php endforeach; ?>
	</div>

	<?php
endif;

wp_reset_postdata();
