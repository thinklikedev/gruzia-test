<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

$quantity = 0;

foreach ( WC()->cart->get_cart() as $cart_item ) {
	if ($cart_item['data']->get_id() == $product->get_id()) {
		$quantity = $cart_item['quantity'];
	}
   
}

?>

<div class="quantity-btns">
	<div class="quantity-minus" data-cart_item_key="<?= WC()->cart->generate_cart_id($product->get_id()); ?>"></div>
	<div class="quantity-number"><span><?= $quantity; ?></span></div>
	<div class="quantity-plus add_btn add_to_cart_button ajax_add_to_cart" data-product_id="<?= $product->get_id(); ?>"></div>
</div>