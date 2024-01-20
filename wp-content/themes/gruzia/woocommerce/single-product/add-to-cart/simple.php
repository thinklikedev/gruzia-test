<?php
/**
 * Simple product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/simple.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! $product->is_purchasable() ) {
	return;
}

$quantity = 0;

foreach (WC()->cart->get_cart() as $cart_item) {

    if($cart_item && $cart_item['product_id'] == $product->get_id()) {
        $quantity = $cart_item['quantity'];
    }
}

// echo wc_get_stock_html( $product ); // WPCS: XSS ok.

if ( $product->is_in_stock() ) : ?>

	<?php do_action( 'woocommerce_before_add_to_cart_form' ); ?>

	<?php do_action( 'woocommerce_before_add_to_cart_button' ); ?>

	<?php do_action( 'woocommerce_before_add_to_cart_quantity' ); ?>

	<?php
	// $min_value = apply_filters('woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product);
	// $max_value = $product->get_max_purchase_quantity();
	// $max_value = $max_value == -1 ? 1000 : $max_value;
	?>

	<div class="quantity-btns">
        <div class="quantity-minus" data-cart_item_key="<?= WC()->cart->generate_cart_id($product->get_id()); ?>"></div>
        <div class="quantity-number"><span><?= $quantity; ?></span></div>
        <div class="quantity-plus add_btn add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo esc_attr( $product->get_id() ); ?>"></div>
    </div>

	<?php do_action( 'woocommerce_after_add_to_cart_quantity' ); ?>

	<?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

	<?php do_action( 'woocommerce_after_add_to_cart_form' ); ?>

<?php endif; ?>
