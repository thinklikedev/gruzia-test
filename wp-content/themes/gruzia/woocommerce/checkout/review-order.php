<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 5.2.0
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="woocommerce-checkout-review-order-table">
	<table class="shop_table">
		<thead>
			<tr>
				<th class="product-name">Наименование</th>
				<th class="product-total">Шт</th>
				<th class="product-total">Цена</th>
			</tr>
		</thead>
		<tbody>
			<?php
			do_action( 'woocommerce_review_order_before_cart_contents' );

			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$image = wp_get_attachment_image_url($_product->get_image_id(), 'woocommerce_gallery_thumbnail') ?: wc_placeholder_img_src();
					?>
					<tr class="<?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						<td class="product-name">
							<img src="<?= $image; ?>">
							<span>
								<?= wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) ) ?: '&nbsp;'; ?>
							</span>
						</td>
						<td class="product-quantity">
							×<?php echo $cart_item['quantity']; ?>
						</td>
						<td class="product-total">
							<?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
						</td>
					</tr>
					<?php
				}
			}

			do_action( 'woocommerce_review_order_after_cart_contents' );
			?>
		</tbody>
	</table>

	<div class="totals_block">

		<?php if (0 < WC()->cart->get_shipping_total()) : ?>
			<div class="totals_row">
				Доставка: <?= WC()->cart->get_shipping_total(); ?>р
			</div>
		<?php endif; ?>

		<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
			<div class="totals_row cart-discount">
				<div><?php wc_cart_totals_coupon_label( $coupon ); ?></div>
				<div><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>

		<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

	</div>
</div>