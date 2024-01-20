<?php
/**
 * Single Product title
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/title.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see        https://docs.woocommerce.com/document/template-structure/
 * @package    WooCommerce\Templates
 * @version    1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $wpdb;

$categories = $wpdb->get_results("SELECT tt.term_id, tt.parent FROM {$wpdb->term_taxonomy} tt INNER JOIN {$wpdb->term_relationships} tr ON (tt.term_id = tr.term_taxonomy_id) INNER JOIN {$wpdb->termmeta} tm ON (tt.term_id = tm.term_id) WHERE tr.object_id = " . get_the_ID() ." AND tm.meta_key = 'qr_category' AND tm.meta_value = 0");

if ($categories) {
    echo '<ul class="tags">';

    foreach ($categories as $cat) {
        if ($cat->parent) {
            $term = get_term($cat->parent);
            echo '<li><a href="'. get_term_link($term) .'">'. $term->name .'</a></li>';
        }
    }
    foreach ($categories as $cat) {
        $term = get_term($cat->term_id);
        echo '<li><a href="'. get_term_link($term) .'">'. $term->name .'</a></li>';
    }

    echo '</ul>';
}

the_title( '<h1 class="product_title entry-title">', '</h1>' );
